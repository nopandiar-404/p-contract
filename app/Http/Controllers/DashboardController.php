<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Client;
use App\Models\ContractNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // Tambahkan ini

class DashboardController extends Controller
{
    public function index()
    {
        // Dashboard route sudah dilindungi oleh middleware 'auth'

        $today = Carbon::today();

        try {
            $userId = Auth::id();
            $isAdmin = Auth::user() && Auth::user()->role === 'admin';

            // Statistik - pastikan semua variabel didefinisikan
            $totalContracts = Contract::when(!$isAdmin, function ($q) use ($userId) {
                return $q->where('created_by', $userId);
            })->count();

            $activeContracts = Contract::when(!$isAdmin, function ($q) use ($userId) {
                return $q->where('created_by', $userId);
            })->where('status', 'active')->count();

            $gracePeriodContracts = Contract::when(!$isAdmin, function ($q) use ($userId) {
                return $q->where('created_by', $userId);
            })->where('status', 'grace_period')->count();

            $expiringThisMonth = Contract::when(!$isAdmin, function ($q) use ($userId) {
                return $q->where('created_by', $userId);
            })->where('status', 'active')
                ->whereDate('end_date', '<=', $today->copy()->addDays(30))
                ->whereDate('end_date', '>=', $today)
                ->count();

            $expiredContracts = Contract::when(!$isAdmin, function ($q) use ($userId) {
                return $q->where('created_by', $userId);
            })->where('status', 'expired')->count();

            // Pastikan variabel unreadNotifications didefinisikan
            $unreadNotifications = ContractNotification::where('is_read', false)->count();

            // Kontrak yang akan berakhir segera - PASTIKAN INI ADA!
            $expiringSoon = Contract::with('client')
                ->when(!$isAdmin, function ($q) use ($userId) { return $q->where('created_by', $userId); })
                ->where('status', 'active')
                ->whereDate('end_date', '<=', $today->copy()->addDays(30))
                ->whereDate('end_date', '>=', $today)
                ->orderBy('end_date')
                ->limit(10)
                ->get();

            // Kontrak dalam masa tenggang
            $inGracePeriod = Contract::with('client')
                ->when(!$isAdmin, function ($q) use ($userId) { return $q->where('created_by', $userId); })
                ->where('status', 'grace_period')
                ->orderBy('end_date')
                ->get();

            // Notifikasi terbaru
            $notifications = ContractNotification::with('contract')
                ->latest()
                ->limit(10)
                ->get();

            // Chart data
            $monthlyExpiring = $this->getMonthlyExpiringData();
        } catch (\Exception $e) {
            // Jika ada error database, set nilai default
            $totalContracts = 0;
            $activeContracts = 0;
            $gracePeriodContracts = 0;
            $expiringThisMonth = 0;
            $expiredContracts = 0;
            $unreadNotifications = 0;
            $expiringSoon = collect(); // Koleksi kosong
            $inGracePeriod = collect(); // Koleksi kosong
            $notifications = collect(); // Koleksi kosong
            $monthlyExpiring = [];
        }

        return view('dashboard.index', compact(
            'totalContracts',
            'activeContracts',
            'gracePeriodContracts',
            'expiringThisMonth',
            'expiredContracts',
            'unreadNotifications',
            'expiringSoon', // PASTIKAN INI DIKOMPAK
            'inGracePeriod',
            'notifications',
            'monthlyExpiring'
        ));
    }

    public function reports(Request $request)
    {
        try {
            // Respect optional filters from query string: status, start_date, end_date
            $statusFilter = $request->query('status');
            $start = $request->query('start_date');
            $end = $request->query('end_date');

            $userId = Auth::id();
            $isAdmin = Auth::user() && Auth::user()->role === 'admin';

            $baseQuery = Contract::with('client')->when(!$isAdmin, function ($q) use ($userId) {
                return $q->where('created_by', $userId);
            });

            // Apply date range filter (on end_date) if provided
            if ($start && $end) {
                try {
                    $startDt = Carbon::parse($start)->startOfDay();
                    $endDt = Carbon::parse($end)->endOfDay();
                    $baseQuery->whereBetween('end_date', [$startDt, $endDt]);
                } catch (\Exception $e) {
                    // ignore parse errors
                }
            } elseif ($start) {
                try {
                    $startDt = Carbon::parse($start)->startOfDay();
                    $baseQuery->where('end_date', '>=', $startDt);
                } catch (\Exception $e) {
                }
            } elseif ($end) {
                try {
                    $endDt = Carbon::parse($end)->endOfDay();
                    $baseQuery->where('end_date', '<=', $endDt);
                } catch (\Exception $e) {
                }
            }

            // Build the three collections, but if a specific statusFilter is provided, apply it so the result
            // reflects the user's selection (other sections will be empty).
            $gracePeriodContracts = (clone $baseQuery)->when(!$statusFilter || $statusFilter === 'all', function ($q) {
                return $q->where('status', 'grace_period');
            }, function ($q) use ($statusFilter) {
                return $q->where('status', $statusFilter);
            })->orderBy('end_date')->get();

            $expiringSoon = (clone $baseQuery)->when(!$statusFilter || $statusFilter === 'all', function ($q) {
                return $q->where('status', 'active')
                    ->whereDate('end_date', '<=', Carbon::now()->addDays(30))
                    ->whereDate('end_date', '>=', Carbon::now());
            }, function ($q) use ($statusFilter) {
                return $q->where('status', $statusFilter)
                    ->when(true, function ($q) {
                        // keep ordering
                        return $q;
                    });
            })->orderBy('end_date')->get();

            $expiredContracts = (clone $baseQuery)->when(!$statusFilter || $statusFilter === 'all', function ($q) {
                return $q->where('status', 'expired');
            }, function ($q) use ($statusFilter) {
                return $q->where('status', $statusFilter);
            })->orderBy('end_date')->get();

            // Hitung total per status (apply same date filter via clones)
            $totalActive = (clone $baseQuery)->where('status', 'active')->count();
            $totalGracePeriod = (clone $baseQuery)->where('status', 'grace_period')->count();
            $totalExpired = (clone $baseQuery)->where('status', 'expired')->count();
            $totalAll = $totalActive + $totalGracePeriod + $totalExpired;

            $totalByStatus = [
                'active' => $totalActive,
                'grace_period' => $totalGracePeriod,
                'expired' => $totalExpired,
                'total' => $totalAll
            ];
        } catch (\Exception $e) {
            // Fallback jika ada error
            $gracePeriodContracts = collect();
            $expiringSoon = collect();
            $expiredContracts = collect();
            $totalByStatus = [
                'active' => 0,
                'grace_period' => 0,
                'expired' => 0,
                'total' => 0
            ];
        }

        return view('dashboard.reports', compact(
            'gracePeriodContracts',
            'expiringSoon',
            'expiredContracts',
            'totalByStatus'
        ));
    }

    private function getMonthlyExpiringData()
    {
        $data = [];

        try {
            for ($i = 0; $i < 6; $i++) {
                $month = Carbon::now()->addMonths($i);
                $start = $month->copy()->startOfMonth();
                $end = $month->copy()->endOfMonth();

                $count = Contract::whereBetween('end_date', [$start, $end])
                    ->when(Auth::user() && Auth::user()->role !== 'admin', function ($q) {
                        return $q->where('created_by', Auth::id());
                    })
                    ->where('status', 'active')
                    ->count();

                $data[] = [
                    'month' => $month->format('M Y'),
                    'count' => $count
                ];
            }
        } catch (\Exception $e) {
            // Return data kosong jika ada error
            $data = [];
        }

        return $data;
    }

    public function gracePeriodReport()
    {
        try {
            $userId = Auth::id();
            $isAdmin = Auth::user() && Auth::user()->role === 'admin';

            $contracts = Contract::with('client')
                ->when(!$isAdmin, function ($q) use ($userId) { return $q->where('created_by', $userId); })
                ->where('status', 'grace_period')
                ->orderBy('end_date')
                ->get()
                ->map(function ($contract) {
                    // Hitung sisa hari masa tenggang
                    $endDate = Carbon::parse($contract->end_date);
                    $graceEndDate = $endDate->addDays($contract->grace_period_days);
                    $remainingDays = Carbon::now()->diffInDays($graceEndDate, false);

                    $contract->remaining_days = max(0, $remainingDays);
                    return $contract;
                });
        } catch (\Exception $e) {
            $contracts = collect();
        }

        return view('dashboard.grace-period-report', compact('contracts'));
    }

    /**
     * Return JSON distribution counts for contracts filtered by date range and status.
     * Used by AJAX when applying report filters.
     */
    public function distribution(Request $request)
    {
        try {
            $period = $request->query('period'); // monthly or quarterly (time-series)
            $start = $request->query('start_date');
            $end = $request->query('end_date');
            $statusFilter = $request->query('status');

            // If period param is present, return time-series data (labels + counts)
            if ($period && in_array($period, ['monthly', 'quarterly'])) {
                $series = $this->getTimeSeries($period, $start, $end, $statusFilter);
                return response()->json(['success' => true, 'data' => $series]);
            }

            $userId = Auth::id();
            $isAdmin = Auth::user() && Auth::user()->role === 'admin';

            $baseQuery = Contract::query()->when(!$isAdmin, function ($q) use ($userId) {
                return $q->where('created_by', $userId);
            });

            // Apply date range filter if provided (based on end_date)
            if ($start && $end) {
                try {
                    $startDt = Carbon::parse($start)->startOfDay();
                    $endDt = Carbon::parse($end)->endOfDay();
                    $baseQuery->whereBetween('end_date', [$startDt, $endDt]);
                } catch (\Exception $e) {
                    // ignore parse errors and don't apply date filter
                }
            } elseif ($start) {
                try {
                    $startDt = Carbon::parse($start)->startOfDay();
                    $baseQuery->where('end_date', '>=', $startDt);
                } catch (\Exception $e) {
                }
            } elseif ($end) {
                try {
                    $endDt = Carbon::parse($end)->endOfDay();
                    $baseQuery->where('end_date', '<=', $endDt);
                } catch (\Exception $e) {
                }
            }

            // If a specific status is requested, only return counts for that status
            if ($statusFilter && $statusFilter !== 'all') {
                $count = (clone $baseQuery)->where('status', $statusFilter)->count();
                $counts = [
                    'active' => $statusFilter === 'active' ? $count : 0,
                    'grace_period' => $statusFilter === 'grace_period' ? $count : 0,
                    'expired' => $statusFilter === 'expired' ? $count : 0,
                ];
            } else {
                $counts = [
                    'active' => (clone $baseQuery)->where('status', 'active')->count(),
                    'grace_period' => (clone $baseQuery)->where('status', 'grace_period')->count(),
                    'expired' => (clone $baseQuery)->where('status', 'expired')->count(),
                ];
            }

            $counts['total'] = array_sum($counts);

            return response()->json(['success' => true, 'data' => $counts]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Build a time-series for the given period.
     * Returns array with keys `labels` and `counts`.
     */
    private function getTimeSeries(string $period, $start = null, $end = null, $statusFilter = null)
    {
        $labels = [];
        $values = [];

        try {
            // Parse dates if provided
            if ($start) {
                $startDt = Carbon::parse($start)->startOfMonth();
            } else {
                $startDt = Carbon::now()->startOfMonth();
            }

            if ($end) {
                $endDt = Carbon::parse($end)->endOfMonth();
            } else {
                // default window: 6 months from start
                $endDt = $startDt->copy()->addMonths(5)->endOfMonth();
            }

            // Ensure sensible order
            if ($endDt->lessThan($startDt)) {
                $tmp = $startDt;
                $startDt = $endDt;
                $endDt = $tmp;
            }

            $userId = Auth::id();
            $isAdmin = Auth::user() && Auth::user()->role === 'admin';

            $queryBase = Contract::query()->when(!$isAdmin, function ($q) use ($userId) {
                return $q->where('created_by', $userId);
            });

            // default to counting active contracts unless specific status was requested
            $defaultStatus = 'active';
            $statusToUse = ($statusFilter && $statusFilter !== 'all') ? $statusFilter : $defaultStatus;

            if ($period === 'monthly') {
                $cursor = $startDt->copy();
                while ($cursor->lessThanOrEqualTo($endDt)) {
                    $startOfMonth = $cursor->copy()->startOfMonth();
                    $endOfMonth = $cursor->copy()->endOfMonth();
                    $count = (clone $queryBase)
                        ->whereBetween('end_date', [$startOfMonth, $endOfMonth])
                        ->when($statusToUse, function ($q) use ($statusToUse) {
                            if ($statusToUse !== null) {
                                $q->where('status', $statusToUse);
                            }
                        })
                        ->count();

                    $labels[] = $cursor->format('M Y');
                    $values[] = $count;
                    $cursor->addMonth();
                }
            } elseif ($period === 'quarterly') {
                // Build quarters between start and end
                $cursor = $startDt->copy()->startOfMonth();
                // Move cursor to first month of its quarter
                $monthIndex = $cursor->month - 1; // 0-based
                $quarterStartMonth = $monthIndex - ($monthIndex % 3) + 1; // 1-based
                $cursor->month = $quarterStartMonth;

                while ($cursor->lessThanOrEqualTo($endDt)) {
                    $qStart = $cursor->copy()->startOfMonth();
                    $qEnd = $cursor->copy()->addMonths(2)->endOfMonth();

                    $count = (clone $queryBase)
                        ->whereBetween('end_date', [$qStart, $qEnd])
                        ->when($statusToUse, function ($q) use ($statusToUse) {
                            if ($statusToUse !== null) {
                                $q->where('status', $statusToUse);
                            }
                        })
                        ->count();

                    // label like 'Q1 2026'
                    $quarter = intval(floor(($cursor->month - 1) / 3) + 1);
                    $labels[] = 'Q' . $quarter . ' ' . $cursor->format('Y');
                    $values[] = $count;

                    $cursor->addMonths(3)->startOfMonth();
                }
            }
        } catch (\Exception $e) {
            // fallback empty series
            $labels = [];
            $values = [];
        }

        return ['labels' => $labels, 'counts' => $values];
    }

    /**
     * Show comprehensive analytics dashboard with detailed insights
     */
    public function analytics()
    {
        try {
            $userId = Auth::id();
            $isAdmin = Auth::user() && Auth::user()->role === 'admin';
            // Get overall statistics
            $stats = [
                'total' => Contract::when(!$isAdmin, function ($q) use ($userId) { return $q->where('created_by', $userId); })->count(),
                'active' => Contract::when(!$isAdmin, function ($q) use ($userId) { return $q->where('created_by', $userId); })->where('status', 'active')->count(),
                'grace_period' => Contract::when(!$isAdmin, function ($q) use ($userId) { return $q->where('created_by', $userId); })->where('status', 'grace_period')->count(),
                'expired' => Contract::when(!$isAdmin, function ($q) use ($userId) { return $q->where('created_by', $userId); })->where('status', 'expired')->count(),
                'total_dpp' => Contract::when(!$isAdmin, function ($q) use ($userId) { return $q->where('created_by', $userId); })->sum('dpp') ?? 0,
            ];

            // Get top vendors by contract count
            $topVendors = Client::withCount(['contracts' => function ($q) use ($isAdmin, $userId) {
                    if (!$isAdmin) $q->where('created_by', $userId);
                }])
                ->withSum(['contracts' => function ($q) use ($isAdmin, $userId) {
                    if (!$isAdmin) $q->where('created_by', $userId);
                }], 'dpp')
                ->orderByDesc('contracts_count')
                ->limit(5)
                ->get()
                ->map(function ($client) {
                    return (object) [
                        'name' => $client->name,
                        'contracts_count' => $client->contracts_count,
                        'total_value' => $client->contracts_sum_dpp ?? 0,
                    ];
                });

            // Get upcoming expirations (next 30 days)
            $today = Carbon::now();
            $upcomingExpirations = Contract::when(!$isAdmin, function ($q) use ($userId) { return $q->where('created_by', $userId); })
                ->where('status', 'active')
                ->whereDate('end_date', '>', $today)
                ->whereDate('end_date', '<=', $today->copy()->addDays(30))
                ->orderBy('end_date')
                ->limit(10)
                ->get();

            return view('dashboard.analytics', compact(
                'stats',
                'topVendors',
                'upcomingExpirations'
            ));
        } catch (\Exception $e) {
            // Fallback if database error
            return view('dashboard.analytics', [
                'stats' => [
                    'total' => 0,
                    'active' => 0,
                    'grace_period' => 0,
                    'expired' => 0,
                    'total_dpp' => 0,
                ],
                'topVendors' => collect(),
                'upcomingExpirations' => collect(),
            ]);
        }
    }
}

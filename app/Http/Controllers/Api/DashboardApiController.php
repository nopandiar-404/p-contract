<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\ContractNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    public function getStats()
    {
        $contracts = $this->visibleContracts();

        return response()->json([
            'activeContracts' => (clone $contracts)->active()->count(),
            'gracePeriodContracts' => (clone $contracts)->inGracePeriod()->count(),
            'expiringThisMonth' => (clone $contracts)->expiringSoon(30)->count(),
            'expiredContracts' => (clone $contracts)->expired()->count(),
            'unreadNotifications' => $this->visibleUnreadNotifications()->count(),
            'updated_at' => now()->toDateTimeString(),
        ]);
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => $this->visibleUnreadNotifications()->count(),
        ]);
    }

    private function visibleContracts(): Builder
    {
        $user = auth()->user();

        return Contract::query()->when(
            $user->role !== 'admin',
            fn (Builder $query) => $query->where('created_by', $user->id)
        );
    }

    private function visibleUnreadNotifications(): Builder
    {
        $user = auth()->user();
        $query = ContractNotification::query()->where('is_read', false);

        if ($user->role !== 'admin') {
            $query->whereHas('contract', fn (Builder $contractQuery) => $contractQuery->where('created_by', $user->id));
        }

        return $query;
    }

    public function chartData(Request $request)
    {
        $type = $request->get('type', 'monthly');
        $data = [];
        
        if ($type === 'monthly') {
            for ($i = 0; $i < 6; $i++) {
                $month = Carbon::now()->addMonths($i);
                $start = $month->copy()->startOfMonth();
                $end = $month->copy()->endOfMonth();
                
                $data['labels'][] = $month->format('M Y');
                $data['data'][] = Contract::whereBetween('end_date', [$start, $end])
                    ->where('status', 'active')
                    ->count();
            }
        } else {
            // Quarterly data
            for ($i = 0; $i < 4; $i++) {
                $quarter = ceil((Carbon::now()->addMonths($i*3)->month) / 3);
                $year = Carbon::now()->addMonths($i*3)->year;
                $start = Carbon::create($year, ($quarter-1)*3+1, 1)->startOfMonth();
                $end = $start->copy()->addMonths(2)->endOfMonth();
                
                $data['labels'][] = "Q{$quarter} {$year}";
                $data['data'][] = Contract::whereBetween('end_date', [$start, $end])
                    ->where('status', 'active')
                    ->count();
            }
        }
        
        return response()->json($data);
    }

    public function sendReminder($contractId)
    {
        $contract = Contract::findOrFail($contractId);
        
        // In real app, send email notification
        ContractNotification::create([
            'contract_id' => $contract->id,
            'type' => 'reminder_sent',
            'message' => "Reminder dikirim ke {$contract->client->name}",
            'sent_date' => Carbon::today(),
            'is_read' => true
        ]);
        
        return response()->json(['success' => true]);
    }
}
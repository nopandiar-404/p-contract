<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\ContractNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    public function getStats()
    {
        return response()->json([
            'activeContracts' => Contract::active()->count(),
            'gracePeriodContracts' => Contract::inGracePeriod()->count(),
            'expiringThisMonth' => Contract::expiringSoon(30)->count(),
            'expiredContracts' => Contract::expired()->count(),
            'unreadNotifications' => ContractNotification::where('is_read', false)->count(),
            'updated_at' => now()->toDateTimeString()
        ]);
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => ContractNotification::where('is_read', false)->count()
        ]);
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
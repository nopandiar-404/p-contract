// app/Http/Controllers/GracePeriodReportController.php
namespace App\Http\Controllers;

use App\Models\Contract;
use Carbon\Carbon;

class GracePeriodReportController extends Controller
{
    public function index()
    {
        // Kontrak yang statusnya grace_period atau aktif dengan tanggal end_date sudah lewat
        $contracts = Contract::with('client')
            ->where('status', 'grace_period')
            ->orWhere(function($query) {
                $query->where('status', 'active')
                      ->where('end_date', '<', Carbon::now());
            })
            ->orderBy('end_date', 'asc')
            ->get()
            ->map(function($contract) {
                // Hitung grace period end date
                $contract->grace_period_end_date = Carbon::parse($contract->end_date)
                    ->addDays($contract->grace_period_days ?? 30);
                
                // Hitung sisa hari
                $contract->remaining_days = max(0, 
                    Carbon::now()->diffInDays($contract->grace_period_end_date, false)
                );
                
                return $contract;
            });

        return view('dashboard.grace-period-report', compact('contracts'));
    }
}
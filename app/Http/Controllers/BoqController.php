<?php

namespace App\Http\Controllers;

use App\Models\ContractBoqItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoqController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = ContractBoqItem::query()
            ->with(['contract:id,contract_number,contract_name,client_id,created_by', 'contract.client:id,name'])
            ->whereHas('contract', function ($contractQuery) use ($user) {
                if ($user->role !== 'admin') {
                    $contractQuery->where('created_by', $user->id);
                }
            });

        $search = trim((string) $request->query('q', ''));
        if ($search !== '') {
            $query->where(function ($itemQuery) use ($search) {
                $itemQuery->where('description', 'like', "%{$search}%")
                    ->orWhereHas('contract', function ($contractQuery) use ($search) {
                        $contractQuery->where('contract_number', 'like', "%{$search}%")
                            ->orWhere('contract_name', 'like', "%{$search}%")
                            ->orWhereHas('client', function ($clientQuery) use ($search) {
                                $clientQuery->where('name', 'like', "%{$search}%");
                            });
                    });
            });
        }

        $totalItems = (clone $query)->count();
        $totalValue = (clone $query)->sum('subtotal');
        $perPage = (int) $request->query('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $items = $query->orderBy('contract_id')->orderBy('id')->paginate($perPage)->withQueryString();

        return view('boq.index', compact('items', 'search', 'totalItems', 'totalValue'));
    }
}
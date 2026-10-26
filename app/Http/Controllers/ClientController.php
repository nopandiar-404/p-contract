<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $isAdmin = auth()->user() && auth()->user()->role === 'admin';

        // Query dasar dengan eager loading
        $query = Client::withCount('contracts')
            ->when($request->has('search') && $request->search != '', function($q) use ($request) {
                $search = $request->search;
                $q->where(function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%")
                          ->orWhere('phone', 'like', "%{$search}%")
                          ->orWhere('pic_name', 'like', "%{$search}%")
                          ->orWhere('pic_email', 'like', "%{$search}%");
                });
            })
            ->when($request->has('status') && in_array($request->status, ['active', 'inactive']), function($q) use ($request) {
                $q->where('status', $request->status);
            });

        // Jika bukan admin, batasi ke klien yang dibuat oleh user ini
        if (!$isAdmin) {
            $query->where('created_by', $userId);
        }

        // Sorting
        if ($request->has('sort')) {
            $order = $request->get('order', 'asc');
            $query->orderBy($request->sort, $order);
        } else {
            $query->orderBy('name', 'asc');
        }

        // Pagination
        $clients = $query->paginate(10)->appends($request->all());

        // Hitung statistik untuk footer (hanya milik user jika bukan admin)
        $activeCountQuery = Client::where('status', 'active');
        $inactiveCountQuery = Client::where('status', 'inactive');
        if (!$isAdmin) {
            $activeCountQuery->where('created_by', $userId);
            $inactiveCountQuery->where('created_by', $userId);
        }
        $activeCount = $activeCountQuery->count();
        $inactiveCount = $inactiveCountQuery->count();

        return view('clients.index', compact('clients', 'activeCount', 'inactiveCount'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateClientData($request);
        
        DB::beginTransaction();
        
        try {
            // set created_by
            $validated['created_by'] = auth()->id() ?? null;
            $client = Client::create($validated);
            
            DB::commit();
            
            return redirect()->route('clients.index')
                ->with('success', 'Klien berhasil ditambahkan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan klien: ' . $e->getMessage());
        }
    }

    public function show(Client $client)
    {
        $userId = auth()->id();
        $isAdmin = auth()->user() && auth()->user()->role === 'admin';
        if (!$isAdmin && $client->created_by && $client->created_by != $userId) {
            abort(403);
        }
        // Eager load contracts with count
        $client->load(['contracts' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);
        
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        $userId = auth()->id();
        $isAdmin = auth()->user() && auth()->user()->role === 'admin';
        if (!$isAdmin && $client->created_by && $client->created_by != $userId) {
            abort(403);
        }
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $userId = auth()->id();
        $isAdmin = auth()->user() && auth()->user()->role === 'admin';
        if (!$isAdmin && $client->created_by && $client->created_by != $userId) {
            abort(403);
        }
        $validated = $this->validateClientData($request, $client->id);
        
        DB::beginTransaction();
        
        try {
            $client->update($validated);
            
            DB::commit();
            
            return redirect()->route('clients.index')
                ->with('success', 'Klien berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui klien: ' . $e->getMessage());
        }
    }

    public function destroy(Client $client)
    {
        $userId = auth()->id();
        $isAdmin = auth()->user() && auth()->user()->role === 'admin';
        if (!$isAdmin && $client->created_by && $client->created_by != $userId) {
            abort(403);
        }
        // Cek apakah klien memiliki kontrak
        if ($client->contracts()->exists()) {
            return back()
                ->with('error', 'Tidak dapat menghapus klien karena masih memiliki kontrak terkait.');
        }
        
        DB::beginTransaction();
        
        try {
            $client->delete();
            
            DB::commit();
            
            return redirect()->route('clients.index')
                ->with('success', 'Klien berhasil dihapus.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->with('error', 'Gagal menghapus klien: ' . $e->getMessage());
        }
    }

    // Bulk actions
    public function bulkActions(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'client_ids' => 'required|array|min:1',
            'client_ids.*' => 'required|integer|exists:clients,id',
        ]);
        
        $clientIds = $request->input('client_ids');
        $action = $request->input('action');
        
        DB::beginTransaction();
        
        try {
            $query = Client::whereIn('id', $clientIds);
            // Jika bukan admin, batasi ke milik user
            $userId = auth()->id();
            $isAdmin = auth()->user() && auth()->user()->role === 'admin';
            if (!$isAdmin) {
                $query->where('created_by', $userId);
            }
            
            switch ($action) {
                case 'activate':
                    $query->update(['status' => 'active']);
                    $message = 'Klien berhasil diaktifkan.';
                    break;
                    
                case 'deactivate':
                    $query->update(['status' => 'inactive']);
                    $message = 'Klien berhasil dinonaktifkan.';
                    break;
                    
                case 'delete':
                    $clientsWithContracts = (clone $query)->whereHas('contracts')->pluck('name');
                        
                    if ($clientsWithContracts->isNotEmpty()) {
                        $names = $clientsWithContracts->take(3)->implode(', ');
                        $suffix = $clientsWithContracts->count() > 3 ? ' dan lainnya' : '';
                        return back()->with('error', "Tidak dapat menghapus beberapa klien karena masih memiliki kontrak: {$names}{$suffix}.");
                    }
                    
                    $query->delete();
                    $message = 'Klien berhasil dihapus.';
                    break;
                    
                default:
                    return back()->with('error', 'Aksi tidak valid.');
            }
            
            DB::commit();
            
            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Gagal melakukan aksi: ' . $e->getMessage());
        }
    }

    // Export data
    public function export(Request $request)
    {
        $type = $request->get('type', 'csv');
        $status = $request->get('status');
        $search = $request->get('search');
        
        $clients = Client::when($status, function($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($search, function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name', 'asc')
            ->get();
        
        // Implementasi export sesuai tipe
        // Ini adalah placeholder - Anda perlu mengimplementasi library export
        return response()->json([
            'type' => $type,
            'count' => $clients->count(),
            'data' => $clients->toArray()
        ]);
    }

    // Helper method untuk validasi
    private function validateClientData(Request $request, $clientId = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email' . ($clientId ? ',' . $clientId : ''),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'pic_name' => 'nullable|string|max:255',
            'pic_phone' => 'nullable|string|max:20',
            'pic_email' => 'nullable|email',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ];
        
        return $request->validate($rules);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use Illuminate\Http\Request;

class CoaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Accept optional search query `q` for filtering by code or name
        $q = request()->get('q');

        $coas = Coa::when($q, function ($query, $q) {
            $query->where('code', 'like', "%{$q}%")
                ->orWhere('name', 'like', "%{$q}%");
        })->orderBy('code')
            ->paginate(15)
            ->withQueryString();

        return view('coa.index', compact('coas', 'q'));
    }

    public function create()
    {
        return view('coa.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:coas,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Coa::create($data);

        return redirect()->route('coa.index')->with('success', 'Jenis COA berhasil dibuat.');
    }

    public function show(Coa $coa)
    {
        return view('coa.show', compact('coa'));
    }

    public function edit(Coa $coa)
    {
        return view('coa.edit', compact('coa'));
    }

    public function update(Request $request, Coa $coa)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:coas,code,' . $coa->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $coa->update($data);

        return redirect()->route('coa.index')->with('success', 'Jenis COA berhasil diperbarui.');
    }

    public function destroy(Coa $coa)
    {
        $coa->delete();
        return redirect()->route('coa.index')->with('success', 'Jenis COA berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContractTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $contractTypes = ContractType::orderBy('id')->get();

        return view('contract-types.index', [
            'contractTypes' => $contractTypes,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $baseSlug = Str::slug($data['name'], '_');
        if (empty($baseSlug)) {
            $baseSlug = 'type_' . time();
        }
        $slug    = $baseSlug;
        $counter = 1;
        while (ContractType::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '_' . $counter++;
        }
        $data['slug'] = $slug;

        ContractType::create($data);

        return redirect()->route('contract-types.index')->with('success', 'Jenis kontrak berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $contractType = ContractType::findOrFail($id);

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $contractType->update([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return redirect()->route('contract-types.index')->with('success', 'Jenis kontrak berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $contractType = ContractType::findOrFail($id);

        $contractCount = Contract::where('contract_type', $contractType->slug)->count();

        if ($contractCount > 0) {
            return redirect()->route('contract-types.index')
                ->with('error', "Jenis kontrak '{$contractType->name}' tidak dapat dihapus karena masih digunakan oleh {$contractCount} data kontrak.");
        }

        $contractType->delete();

        return redirect()->route('contract-types.index')->with('success', 'Jenis kontrak berhasil dihapus.');
    }
}

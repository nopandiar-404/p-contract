<?php

namespace App\Http\Controllers;

class ContractTypeController extends Controller
{
    public function index()
    {
        return view('contract-types.index', [
            'contractTypes' => config('contract_types.types', []),
        ]);
    }
}
<?php

namespace App\Exports;

use App\Models\Contract;

// Simple safe export helper: does not depend on external packages.
class ContractsExport
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function get()
    {
        return $this->query->get();
    }
}

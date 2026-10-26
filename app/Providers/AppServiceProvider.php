<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (Schema::hasTable('contract_types')) {
                $dbTypes = \App\Models\ContractType::all();
                if ($dbTypes->isNotEmpty()) {
                    $typesConfig = [];
                    foreach ($dbTypes as $type) {
                        $typesConfig[$type->slug] = [
                            'name' => $type->name,
                            'description' => $type->description,
                        ];
                    }
                    config(['contract_types.types' => $typesConfig]);
                }
            }
        } catch (\Throwable $e) {
            // Fallback gracefully during artisan commands or early bootstrap
        }
    }
}


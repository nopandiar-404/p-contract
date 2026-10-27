<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
<<<<<<< HEAD
use Illuminate\Support\Facades\Schema;
=======
>>>>>>> 8913ad198d6cee56e8e071c1e7ad62e855d82fe8

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
<<<<<<< HEAD
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

=======
        //
    }
}
>>>>>>> 8913ad198d6cee56e8e071c1e7ad62e855d82fe8

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('contracts') && !Schema::hasColumn('contracts', 'contract_term')) {
            Schema::table('contracts', function (Blueprint $table) {
                $table->string('contract_term')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('contracts') && Schema::hasColumn('contracts', 'contract_term')) {
            Schema::table('contracts', function (Blueprint $table) {
                $table->dropColumn('contract_term');
            });
        }
    }
};
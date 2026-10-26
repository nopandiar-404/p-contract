<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('contracts')) {
            Schema::table('contracts', function (Blueprint $table) {
                if (!Schema::hasColumn('contracts', 'tahun_1')) {
                    $table->decimal('tahun_1', 15, 2)->nullable()->after('contract_term');
                }
                if (!Schema::hasColumn('contracts', 'tahun_2')) {
                    $table->decimal('tahun_2', 15, 2)->nullable()->after('tahun_1');
                }
                if (!Schema::hasColumn('contracts', 'jumlah')) {
                    $table->decimal('jumlah', 15, 2)->nullable()->after('tahun_2');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('contracts')) {
            Schema::table('contracts', function (Blueprint $table) {
                if (Schema::hasColumn('contracts', 'jumlah')) {
                    $table->dropColumn('jumlah');
                }
                if (Schema::hasColumn('contracts', 'tahun_2')) {
                    $table->dropColumn('tahun_2');
                }
                if (Schema::hasColumn('contracts', 'tahun_1')) {
                    $table->dropColumn('tahun_1');
                }
            });
        }
    }
};


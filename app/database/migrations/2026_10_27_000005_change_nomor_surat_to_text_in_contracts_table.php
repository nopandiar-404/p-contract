<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('contracts') && Schema::hasColumn('contracts', 'nomor_surat')) {
            Schema::table('contracts', function (Blueprint $table) {
                $table->text('nomor_surat')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('contracts') && Schema::hasColumn('contracts', 'nomor_surat')) {
            Schema::table('contracts', function (Blueprint $table) {
                $table->string('nomor_surat', 100)->nullable()->change();
            });
        }
    }
};


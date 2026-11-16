<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('contract_types')) {
            Schema::create('contract_types', function (Blueprint $table) {
                $table->id();
                $table->string('slug', 100)->unique();
                $table->string('name', 255);
                $table->text('description')->nullable();
                $table->timestamps();
            });

            // Seed initial contract types from config
            DB::table('contract_types')->insert([
                [
                    'slug' => 'contract_barang',
                    'name' => 'Kontrak Barang',
                    'description' => 'Pengadaan atau penyediaan barang.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'slug' => 'contract_jasa',
                    'name' => 'Kontrak Jasa',
                    'description' => 'Penyediaan jasa atau layanan.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'slug' => 'license',
                    'name' => 'License',
                    'description' => 'Lisensi penggunaan perangkat lunak atau hak kekayaan intelektual.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_types');
    }
};


// database/migrations/xxxx_create_contracts_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Pastikan tabel clients sudah ada
        if (!Schema::hasTable('clients')) {
            throw new \Exception('Tabel clients harus dibuat terlebih dahulu!');
        }

        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique();
            $table->string('contract_name');
            $table->string('vendor_name')->nullable();

            // Pastikan tipe data sama dengan tabel clients
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')
                  ->references('id')
                  ->on('clients')
                  ->onDelete('cascade');
            
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'expired', 'grace_period', 'terminated'])->default('active');
            $table->integer('grace_period_days')->default(30);
            $table->date('actual_end_date')->nullable();
            $table->text('termination_reason')->nullable();
            
            // Pastikan tabel users sudah ada
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index untuk performa
            $table->index(['status', 'end_date']);
            $table->index('client_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contracts');
    }
};
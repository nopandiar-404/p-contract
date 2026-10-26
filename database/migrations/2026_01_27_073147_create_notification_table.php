<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');
            $table->string('type'); // reminder, grace_period_start, contract_expired, contract_terminated
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->json('data')->nullable(); // Additional data
            $table->timestamps();
            
            $table->index(['contract_id', 'is_read', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
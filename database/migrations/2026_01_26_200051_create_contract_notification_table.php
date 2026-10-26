<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contract_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('type');
            $table->text('message');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->string('action_url')->nullable();
            $table->string('action_text')->nullable();
            $table->json('metadata')->nullable();
            $table->datetime('sent_date');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            
            // Indexes
            $table->index(['contract_id', 'sent_date']);
            $table->index(['user_id', 'is_read']);
            $table->index(['type', 'priority']);
            $table->index('sent_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contract_notifications');
    }
};
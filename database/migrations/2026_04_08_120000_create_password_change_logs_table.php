<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordChangeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_change_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('email')->index();
            $table->unsignedBigInteger('performed_by')->nullable()->index();
            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('source')->nullable()->comment('e.g. direct-update, reset-link');
            $table->timestamps();

            // optional foreign keys can be enabled if desired
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('performed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_change_logs');
    }
}

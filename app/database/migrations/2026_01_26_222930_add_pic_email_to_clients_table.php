<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'pic_email')) {
                $table->string('pic_email')->nullable()->after('pic_phone');
            }
            if (!Schema::hasColumn('clients', 'pic_name')) {
                $table->string('pic_name')->nullable()->after('address');
            }
            if (!Schema::hasColumn('clients', 'pic_phone')) {
                $table->string('pic_phone')->nullable()->after('pic_name');
            }
            if (!Schema::hasColumn('clients', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active');
            }
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            // Optional: remove columns if you want to rollback
            // $table->dropColumn(['pic_email', 'pic_name', 'pic_phone', 'status']);
        });
    }
};
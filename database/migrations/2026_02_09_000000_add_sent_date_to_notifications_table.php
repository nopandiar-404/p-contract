<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('notifications') && !Schema::hasColumn('notifications', 'sent_date')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->timestamp('sent_date')->nullable()->after('data');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('notifications') && Schema::hasColumn('notifications', 'sent_date')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropColumn('sent_date');
            });
        }
    }
};

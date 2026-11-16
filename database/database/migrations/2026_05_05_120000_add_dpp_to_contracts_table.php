<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('contracts')) {
            Schema::table('contracts', function (Blueprint $table) {
                if (!Schema::hasColumn('contracts', 'dpp')) {
                    $table->decimal('dpp', 15, 2)->nullable()->after('description');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('contracts')) {
            Schema::table('contracts', function (Blueprint $table) {
                if (Schema::hasColumn('contracts', 'dpp')) {
                    $table->dropColumn('dpp');
                }
            });
        }
    }
};

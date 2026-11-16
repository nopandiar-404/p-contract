<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('contracts')) {
            Schema::table('contracts', function (Blueprint $table) {
                if (!Schema::hasColumn('contracts', 'account_name')) {
                    $table->string('account_name')->nullable()->after('created_by');
                }
                if (!Schema::hasColumn('contracts', 'account_position')) {
                    $table->string('account_position')->nullable()->after('account_name');
                }
            });

            if (Schema::hasTable('users')) {
                DB::table('contracts')
                    ->whereNotNull('created_by')
                    ->orderBy('id')
                    ->chunkById(100, function ($contracts) {
                        foreach ($contracts as $contract) {
                            $user = DB::table('users')->where('id', $contract->created_by)->first();
                            if ($user) {
                                DB::table('contracts')
                                    ->where('id', $contract->id)
                                    ->update([
                                        'account_name' => $user->name,
                                        'account_position' => $user->position ?? null,
                                    ]);
                            }
                        }
                    });
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('contracts')) {
            Schema::table('contracts', function (Blueprint $table) {
                if (Schema::hasColumn('contracts', 'account_position')) {
                    $table->dropColumn('account_position');
                }
                if (Schema::hasColumn('contracts', 'account_name')) {
                    $table->dropColumn('account_name');
                }
            });
        }
    }
};

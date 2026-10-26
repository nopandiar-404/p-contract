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
                if (!Schema::hasColumn('contracts', 'vendor_name')) {
                    $table->string('vendor_name')->nullable()->after('contract_name');
                }
            });

            if (Schema::hasTable('clients')) {
                DB::table('contracts')
                    ->whereNotNull('client_id')
                    ->chunkById(100, function ($contracts) {
                        foreach ($contracts as $contract) {
                            $client = DB::table('clients')->where('id', $contract->client_id)->first();
                            if ($client) {
                                DB::table('contracts')
                                    ->where('id', $contract->id)
                                    ->update(['vendor_name' => $client->name]);
                            }
                        }
                    });
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('contracts') && Schema::hasColumn('contracts', 'vendor_name')) {
            Schema::table('contracts', function (Blueprint $table) {
                $table->dropColumn('vendor_name');
            });
        }
    }
};

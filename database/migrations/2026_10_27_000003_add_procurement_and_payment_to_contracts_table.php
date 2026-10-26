<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('contracts')) {
            Schema::table('contracts', function (Blueprint $table) {
                if (!Schema::hasColumn('contracts', 'pr')) {
                    $table->string('pr', 50)->nullable()->after('total');
                }
                if (!Schema::hasColumn('contracts', 'po')) {
                    $table->string('po', 50)->nullable()->after('pr');
                }
                if (!Schema::hasColumn('contracts', 'ttb')) {
                    $table->string('ttb', 50)->nullable()->after('po');
                }
                if (!Schema::hasColumn('contracts', 'berita_acara')) {
                    $table->string('berita_acara', 255)->nullable()->after('ttb');
                }
                if (!Schema::hasColumn('contracts', 'payment_status')) {
                    $table->string('payment_status', 30)->default('BELUM BAYAR')->after('berita_acara');
                }
                if (!Schema::hasColumn('contracts', 'payment_date')) {
                    $table->date('payment_date')->nullable()->after('payment_status');
                }
                if (!Schema::hasColumn('contracts', 'keterangan')) {
                    $table->text('keterangan')->nullable()->after('payment_date');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('contracts')) {
            Schema::table('contracts', function (Blueprint $table) {
                $columns = ['pr', 'po', 'ttb', 'berita_acara', 'payment_status', 'payment_date', 'keterangan'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('contracts', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};


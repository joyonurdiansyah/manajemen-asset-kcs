<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('asset_statuses', function (Blueprint $table) {
            $table->foreignId('lokasi_awal_id')
            ->nullable()
            ->after('serial_number')
            ->constrained('warehouse_master_sites')
            ->onDelete('set null');

            $table->foreignId('lokasi_tujuan_id')
            ->nullable()
            ->after('lokasi_awal_id')
            ->constrained('warehouse_master_sites')
            ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_statuses', function (Blueprint $table) {
            $table->dropForeign(['lokasi_awal_id']);
            $table->dropForeign(['lokasi_tujuan_id']);
            $table->dropColumn(['lokasi_awal_id', 'lokasi_tujuan_id']);
        });
    }
};

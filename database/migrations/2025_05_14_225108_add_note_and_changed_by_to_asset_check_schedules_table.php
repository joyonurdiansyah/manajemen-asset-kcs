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
        Schema::table('asset_check_schedules', function (Blueprint $table) {
            $table->text('note')->nullable()->after('status');
            $table->string('changed_by')->nullable()->after('note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_check_schedules', function (Blueprint $table) {
            $table->dropColumn(['note', 'changed_by']);
        });
    }
};

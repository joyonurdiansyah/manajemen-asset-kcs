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
            $table->foreignId('pic')->nullable()->after('notes')->constrained('users')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_statuses', function (Blueprint $table) {
            $table->dropForeign(['pic']);
            $table->dropColumn('pic');
        });
    }
};

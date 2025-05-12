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
    Schema::create('asset_check_schedules', function (Blueprint $table) {
        $table->id();
        $table->foreignId('warehouse_master_site_id')->constrained()->onDelete('cascade');
        
        $table->string('request_subject'); 
        $table->text('description')->nullable(); 
        $table->enum('priority', ['low', 'medium', 'high'])->default('medium'); 
        
        $table->date('arrival_date')->nullable();
        $table->date('arrival_completed_date')->nullable(); 

        $table->enum('status', ['unassigned', 'open', 'waiting', 'resolved'])->default('waiting'); 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_check_schedules');
    }
};

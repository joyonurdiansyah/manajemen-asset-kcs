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
        Schema::create('asset_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_master_site_id')->constrained('warehouse_master_site')->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            
            $table->string('asset_code')->nullable();
            $table->string('brand')->nullable();
            $table->string('tipe')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('type')->nullable();
            $table->date('tanggal_visit')->nullable();
            $table->enum('status_barang', ['oke', 'rusak'])->default('oke');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_statuses');
    }
};

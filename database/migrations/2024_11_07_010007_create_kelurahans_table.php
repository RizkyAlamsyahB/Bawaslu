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
        Schema::create('kelurahans', function (Blueprint $table) {
            $table->uuid('id')->primary();  // Gunakan UUID untuk ID utama
            $table->string('kode_kelurahan')->unique();
            $table->string('nama_kelurahan');
            $table->uuid('kecamatan_id'); // Foreign key dengan UUID
            $table->foreign('kecamatan_id')->references('id')->on('kecamatans')->onDelete('cascade'); // Foreign key constraint
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelurahans');
    }
};

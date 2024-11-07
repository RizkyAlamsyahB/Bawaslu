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
        Schema::create('data_suara_sah', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_pasangan_calon');
            $table->integer('jumlah_suara_sah');
            $table->integer('jumlah_suara_tidak_sah')->nullable();
            $table->integer('total_suara_sah_dan_tidak_sah')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_suara_sah');
    }
};

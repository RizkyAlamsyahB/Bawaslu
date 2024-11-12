<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_suara_sah', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pasangan_calon_id'); // Ubah dari string ke uuid reference
            $table->integer('jumlah_suara_sah');
            $table->integer('jumlah_suara_tidak_sah')->nullable();
            $table->integer('total_suara_sah_dan_tidak_sah')->nullable();
            $table->timestamps();

            $table->foreign('pasangan_calon_id')->references('id')->on('pasangan_calons')->onDelete('cascade');
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

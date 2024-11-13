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
        Schema::create('pasangan_calons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_pasangan');
            $table->string('nomor_urut');
            $table->uuid('tipe_pemilihan_id');
            $table->timestamps();
            
            $table->foreign('tipe_pemilihan_id')->references('id')->on('tipe_pemilihans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasangan_calons');
    }
};

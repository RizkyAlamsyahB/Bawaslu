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
        Schema::create('pengguna_hak_pilih_dptb', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('laki_laki');
            $table->integer('perempuan');
            $table->integer('jumlah');
            $table->uuid('tipe_pemilihan_id'); // Ensure this matches the type in the referenced table
            $table->timestamps();

            $table->foreign('tipe_pemilihan_id')->references('id')->on('tipe_pemilihans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengguna_hak_pilih_dptb');
    }
};

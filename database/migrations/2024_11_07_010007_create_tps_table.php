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
        Schema::create('tps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('no_tps');
            $table->uuid('kelurahan_id')->nullable();
            $table->uuid('kecamatan_id')->nullable();

            // Tambahkan constraint unik pada kombinasi no_tps, kelurahan_id, dan kecamatan_id
            $table->unique(['no_tps', 'kelurahan_id', 'kecamatan_id'], 'unique_no_tps_kelurahan_kecamatan');

            $table->foreign('kelurahan_id')->references('id')->on('kelurahans')->onDelete('set null');
            $table->foreign('kecamatan_id')->references('id')->on('kecamatans')->onDelete('set null');

            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tps');
    }
};

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
            $table->uuid('kelurahan_id');
            $table->uuid('kecamatan_id');

            // Foreign keys
            $table->foreign('kelurahan_id')->references('id')->on('kelurahans')->onDelete('cascade');
            $table->foreign('kecamatan_id')->references('id')->on('kecamatans')->onDelete('cascade');

            $table->timestamps();

            // Add a unique constraint on the combination of no_tps, kelurahan_id, and kecamatan_id
            $table->unique(['no_tps', 'kelurahan_id', 'kecamatan_id'], 'unique_no_tps_kelurahan_kecamatan');
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

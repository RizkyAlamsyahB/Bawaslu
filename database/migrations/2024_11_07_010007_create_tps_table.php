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
            $table->uuid('id')->primary();  // Gunakan UUID untuk ID utama
            $table->string('no_tps')->unique();
            $table->uuid('kelurahan_id'); // Foreign key dengan UUID
            $table->foreign('kelurahan_id')->references('id')->on('kelurahans')->onDelete('cascade'); // Foreign key constraint
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

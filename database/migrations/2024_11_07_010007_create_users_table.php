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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('phone');
            $table->string('password');
            $table->string('username')->unique();
            $table->enum('role', ['tps', 'kecamatan', 'kelurahan', 'kota', 'super_admin']);
            $table->uuid('kecamatan_id')->nullable();
            $table->uuid('kelurahan_id')->nullable();
            $table->uuid('tps_id')->nullable();

            $table->foreign('kecamatan_id')->references('id')->on('kecamatans')->onDelete('set null');
            $table->foreign('kelurahan_id')->references('id')->on('kelurahans')->onDelete('set null');
            $table->foreign('tps_id')->references('id')->on('tps')->onDelete('set null');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

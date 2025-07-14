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
        Schema::create('master_instansis', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->integer('last_id')->nullable()->unique();
            $table->string('instansi');
            $table->string('kepala');
            $table->string('alamat');
            $table->string('kota');
            $table->string('telp');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_instansis');
    }
};

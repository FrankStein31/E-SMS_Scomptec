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
        Schema::create('entry_surat_tujuans', function (Blueprint $table) {
            $table->ulid('id')->primary(); // Buat ID utama baru untuk tabel ini
            $table->string('satkerid_tujuan', 255)->nullable();
            $table->boolean('dibaca')->default(false);
            $table->boolean('is_tembusan')->default(false);
            $table->ulid('entrysurat_id');
            $table->unsignedInteger('userid_tujuan');

            $table->foreign('entrysurat_id')->references('id')->on('entry_surat_isis')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry_surat_tujuans');
    }
};

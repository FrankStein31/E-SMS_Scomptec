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
        Schema::create('entry_surat_lampirans', function (Blueprint $table) {
            $table->ulid('lampiran_id')->primary();
            $table->ulid('entrysurat_id');
            $table->string('nama_lampiran', 255)->nullable();
            $table->string('nama_file', 255)->nullable();
            $table->string('size', 255)->nullable();
            $table->dateTime('tgl_upload')->nullable();

            $table->foreign('entrysurat_id')->references('id')->on('entry_surat_isis')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry_surat_lampirans');
    }
};

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
        Schema::create('entry_surat_scans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('entrysurat_id');
            $table->foreign('entrysurat_id')->references('id')->on('entry_surat_isis')->onDelete('cascade');
            $table->integer('nourut')->nullable();
            $table->string('nama_scan')->nullable();
            $table->string('nama_file')->nullable();
            $table->float('size')->nullable();
            $table->date('tgl_upload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry_surat_scans');
    }
};

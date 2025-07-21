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
        Schema::create('draft_surats', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->ulid('entrisurat_id'); // relasi ke surat (optional tambahkan FK nanti)
            $table->string('parent_id')->nullable(); // jika nested disposisi

            $table->string('kepada')->nullable();
            $table->date('tgl_disposisi')->nullable();
            $table->date('tgl_remiten')->nullable();

            $table->text('isi')->nullable();
            $table->text('tindakan')->nullable();

            $table->unsignedBigInteger('userid_pembuat'); // FK ke users
            $table->unsignedBigInteger('userid_tujuan');  // FK ke users

            $table->string('file_original')->nullable();
            $table->string('file_rename')->nullable();
            $table->string('file_size')->nullable(); // bisa juga integer jika dalam bytes

            $table->timestamps();

            // Foreign Key (optional — bisa disesuaikan jika tabel parent sudah dibuat)
            $table->foreign('userid_pembuat')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('userid_tujuan')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('entrisurat_id')->references('id')->on('entry_surat_isis')->onDelete('cascade'); // jika tersedia
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draft_surats');
    }
};

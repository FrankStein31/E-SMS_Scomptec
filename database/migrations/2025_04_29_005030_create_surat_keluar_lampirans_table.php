<?php

use App\Models\SuratKeluarIsi;
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
        Schema::create('surat_keluar_lampirans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->integer('lampiran_id')->unique()->nullable();
            $table->integer('surat_keluar_id')->nullable();
            $table->foreignIdFor(SuratKeluarIsi::class, 'surat_keluar_isi_id')->nullable()->constrained();
            $table->integer('revisi_id')->nullable();
            $table->foreignIdFor(SuratKeluarIsi::class, 'revisi_data_id')->nullable()->constrained();
            $table->longText('nama_lapiran');
            $table->string('nama_file');
            $table->string('size');
            $table->timestamp('tanggal_upload');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluar_lampirans');
    }
};

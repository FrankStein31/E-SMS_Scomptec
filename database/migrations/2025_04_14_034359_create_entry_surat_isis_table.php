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
        Schema::create('entry_surat_isis', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('jenis_id')->default(0)->comment('ini table dari master_jenissurat_join');
            $table->string('nomor_surat')->nullable();
            $table->string('kode_klasifikasi')->nullable();
            $table->string('hal');
            $table->string('kepada');
            $table->string('dari');
            $table->string('alamat');
            $table->date('tgl_surat');
            $table->date('tgl_diterima')->nullable();
            $table->date('tgl_diarahkan')->nullable();
            $table->tinyInteger('sifat')->default(0);
            $table->string('isi')->nullable();
            $table->string('tembusan')->nullable();
            $table->tinyInteger('isfinal')->nullable()->comment('0=draft, 1=selesai');
            $table->bigInteger('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->string('satkerid_pembuat')->nullable();
            $table->string('jumlah_lampiran')->nullable();
            $table->string('referensi_id')->nullable()->comment('ini dari id table entry_surat_isis ini sendiri');
            $table->integer('noagenda')->nullable();
            $table->date('tgl_update')->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->string('satkerid_update')->nullable();
            $table->tinyInteger('terdisposisi')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry_surat_isis');
    }
};

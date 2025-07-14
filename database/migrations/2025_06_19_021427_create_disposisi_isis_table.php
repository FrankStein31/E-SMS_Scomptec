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
        Schema::create('disposisi_isis', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('entrysurat_id');
            $table->foreign('entrysurat_id')->references('id')->on('entry_surat_isis')->onDelete('cascade');
            $table->string('parent_id')->nullable();
            $table->string('kodeklasifikasi');
            $table->string('kepada');
            $table->string('hal');
            $table->date('tgl_disposisi')->nullable();
            $table->date('tgl_remitten')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->text('isi')->nullable();
            $table->text('tindakan')->nullable();
            $table->bigInteger('userid_pembuat')->unsigned();
            $table->foreign('userid_pembuat')->references('id')->on('users')->onDelete('cascade');
            $table->string('satkerid_pembuat')->nullable();
            $table->tinyInteger('terdisposisi')->nullable();
            $table->string('mig_nourut')->nullable();
            $table->string('mig_satkerasalid')->nullable();
            $table->string('mig_satkertujuanid')->nullable();
            $table->string('mig_terbaca')->nullable();
            $table->string('mig_nourutasal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisi_isis');
    }
};

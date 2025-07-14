<?php

use App\Models\SuratKeluarIsi;
use App\Models\User;
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
        Schema::create('surat_keluar_riwayats', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->integer('suratkeluar_id')->unique()->nullable();
            $table->foreignIdFor(SuratKeluarIsi::class, 'surat_keluar_isi_id')->nullable()->constrained();
            $table->integer('revisi_id');
            $table->foreignIdFor(SuratKeluarIsi::class, 'revisi_data_id')->nullable()->constrained();
            $table->integer('nourut_riw');
            $table->integer('nourut_kirim')->default(1);
            $table->integer('userid_pembuat');
            $table->foreignIdFor(User::class, 'user_id_pembuat')->nullable()->constrained();
            $table->longText('satkerid_pembuat', 255)->nullable();
            $table->integer('userid_tujuan')->nullable();
            $table->foreignIdFor(User::class, 'user_id_tujuan')->nullable()->constrained();
            $table->longText('satkerid_tujuan', 255)->nullable();
            $table->integer('userid_final')->nullable();
            $table->foreignIdFor(User::class, 'user_id_final')->nullable()->constrained();
            $table->longText('satkerid_final', 255)->nullable();
            $table->tinyInteger('dibaca')->default(0);
            $table->tinyInteger('last_sent')->default(0);
            $table->tinyInteger('isfinal')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->dateTime('tgl_update')->nullable();
            $table->dateTime('tgl_final')->nullable();
            $table->tinyInteger('status_lama')->default(2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluar_riwayats');
    }
};

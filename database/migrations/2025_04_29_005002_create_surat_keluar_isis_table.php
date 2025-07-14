<?php

use App\Models\EntrySuratIsi;
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
        Schema::create('surat_keluar_isis', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->integer('suratkeluar_id')->unique()->nullable();
            $table->unsignedBigInteger('revisi_id')->nullable();
            $table->foreignIdFor(SuratKeluarIsi::class, 'revisi_data_id')->nullable()->constrained();
            $table->dateTime('tgl_revisi')->nullable();
            $table->unsignedTinyInteger('jenis_id')->nullable();
            $table->unsignedInteger('no_generate')->nullable();
            $table->string('nosurat');
            $table->string('kodeklasifikasi');
            $table->dateTime('tgl_surat')->nullable();
            $table->string('hal');
            $table->string('jml_lampiran');
            $table->unsignedTinyInteger('sifat')->nullable();
            $table->longText('kepada');
            $table->longText('isi');
            $table->longText('tembusan');
            $table->unsignedTinyInteger('jenisref_id')->nullable();
            $table->unsignedInteger('referensi_id')->nullable();
            $table->foreignIdFor(EntrySuratIsi::class, 'entry_surat_isi_id')->nullable()->constrained();
            $table->string('ttd_nama');
            $table->unsignedInteger('ttd_id')->nullable();
            $table->foreignIdFor(User::class, 'user_ttd_id')->nullable()->constrained();
            $table->unsignedTinyInteger('isfinal')->default(1)->comment('0 = draft, 1 = posisi terakhir');
            $table->unsignedInteger('userid_pembuat')->nullable();
            $table->foreignIdFor(User::class, 'user_id_pembuat')->constrained();
            $table->longText('satkerid_pembuat');
            $table->unsignedInteger('userid_tujuan')->nullable();
            $table->foreignIdFor(User::class, 'user_id_tujuan')->constrained();
            $table->longText('satkerid_tujuan');
            $table->unsignedTinyInteger('status')->default(1)->comment('1 = konsep, 2 = kirim acc, 3 = kirim revisi, 4 = final, 5 = cetak');
            $table->unsignedTinyInteger('dibaca')->default(0);
            $table->unsignedTinyInteger('last_sent')->default(0)->comment('1 = yg terakhir dikirim');
            $table->unsignedInteger('userid_final')->nullable();
            $table->foreignIdFor(User::class, 'user_id_final')->nullable()->constrained();
            $table->longText('satkerid_final');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluar_isis');
    }
};

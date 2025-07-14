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
        Schema::create('master_klasifikasis', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('kodeklasifikasi');
            $table->longText('klasifikasi');
            $table->tinyInteger('retensi_aktif');
            $table->tinyInteger('retensi_inaktif');
            $table->enum('keterangan', [1,2,3])->default(1)->comment('1 dinilai kembali, 2 musnah, 3 permanen');
            $table->integer('retensi')->nullable();
            $table->string('parent')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_klasifikasis');
    }
};

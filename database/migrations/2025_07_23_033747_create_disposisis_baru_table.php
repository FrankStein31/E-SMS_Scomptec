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
        Schema::create('disposisis_baru', function (Blueprint $table) {
            $table->id();
            $table->integer('last_id')->nullable()->unique()->comment('ID lama dari database esms');
            $table->ulid('entrysurat_id');
            $table->foreign('entrysurat_id')->references('id')->on('entry_surat_isis')->onDelete('cascade');
            $table->text('kepada'); // multi user, id user dipisah koma
            $table->date('remitten')->nullable();
            $table->text('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisis_baru');
    }
};

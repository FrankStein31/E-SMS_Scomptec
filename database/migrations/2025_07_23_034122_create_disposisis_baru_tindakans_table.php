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
        Schema::create('disposisis_baru_tindakans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disposisis_baru_id')->constrained('disposisis_baru')->onDelete('cascade');
            $table->string('tindakan_id');
            $table->foreign('tindakan_id')->references('id')->on('master_tindakan_disposisis')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisis_baru_tindakans');
    }
};

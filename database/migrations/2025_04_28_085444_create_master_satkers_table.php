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
        Schema::create('master_satkers', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->longText('satkerid')->nullable();
            $table->longText('kodesatker')->nullable();
            $table->longText('satker')->nullable();
            $table->integer('eselon')->nullable();
            $table->bigInteger('userid')->nullable()->unsigned();
            $table->foreign('userid')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_satkers');
    }
};

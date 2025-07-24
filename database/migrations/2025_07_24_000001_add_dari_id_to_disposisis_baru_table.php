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
        Schema::table('disposisis_baru', function (Blueprint $table) {
            $table->unsignedBigInteger('dari_id')->nullable()->after('entrysurat_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disposisis_baru', function (Blueprint $table) {
            $table->dropColumn('dari_id');
        });
    }
}; 
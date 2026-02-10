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
        Schema::create('peminjaman_details', function (Blueprint $table) {
            $table->id();
            $table->uuid('peminjaman_detail_id')->unique();
            $table->uuid('peminjaman_id');
            $table->uuid('alat_id');
            $table->unsignedInteger('jumlah');
            $table->timestamps();

            $table->foreign('peminjaman_id')->references('peminjaman_id')->on('peminjamans')->onDelete('cascade');
            $table->foreign('alat_id')->references('alat_id')->on('alats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_details');
    }
};

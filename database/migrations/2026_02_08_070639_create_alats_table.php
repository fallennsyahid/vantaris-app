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
        Schema::create('alats', function (Blueprint $table) {
            $table->id();
            $table->uuid('alat_id')->unique();

            // Foreign key to kategoris table
            $table->uuid('kategori_id');
            $table->foreign('kategori_id')->references('kategori_id')->on('kategoris')->onDelete('cascade');

            $table->string('nama_alat', 100); // Maksimal 100 karakter
            $table->unsignedInteger('stok');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alats');
    }
};

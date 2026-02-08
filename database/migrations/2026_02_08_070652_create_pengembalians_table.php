<?php

use App\Enums\KondisiAlat;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengembalians', function (Blueprint $table) {
            $table->id();
            $table->uuid('pengembalian_id')->unique();
            $table->uuid('peminjaman_id')->unique();
            $table->uuid('received_by');
            $table->timestamp('tanggal_pengembalian_sebenarnya')->useCurrent();
            $table->enum('kondisi', [KondisiAlat::getAllKondisi()]);
            $table->text('catatan')->nullable();
            $table->boolean('is_tanggung_jawab_selesai')->default(true)->comment('False jika kondisi selain baik, True jika sudah diperbaiki/diganti');
            $table->timestamps();

            $table->foreign('peminjaman_id')->references('peminjaman_id')->on('peminjaman')->onDelete('cascade');
            $table->foreign('received_by')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalians');
    }
};

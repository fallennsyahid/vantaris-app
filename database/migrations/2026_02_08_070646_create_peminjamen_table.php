<?php

use App\Enums\StatusPeminjaman;
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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->uuid('peminjaman_id')->unique();
            $table->uuid('user_id');
            $table->uuid('approved_by')->nullable();
            $table->timestamp('tanggal_pengajuan')->useCurrent();
            $table->date('tanggal_pengambilan_rencana');
            $table->date('tanggal_pengembalian_rencana');
            $table->timestamp('tanggal_pengambilan_sebenarnya')->nullable();
            $table->text('alasan_meminjam');
            $table->enum('status', StatusPeminjaman::getAllStatuses())->default(StatusPeminjaman::PENDING);
            $table->text('note')->nullable()->comment('Alasan jika ditolak');
            $table->string('qr_token')->unique()->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};

<?php

namespace App\Http\Controllers\Peminjam;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use App\Enums\StatusPeminjaman;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PengembalianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::user()->user_id;

        $peminjamans = Peminjaman::where('user_id', $userId)
            ->where('status', StatusPeminjaman::DIAMBIL->value)
            ->orderBy('created_at', 'desc')
            ->get();

        $pengembalians = Pengembalian::whereHas('peminjaman', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->with(['peminjaman.details.alat', 'penerima'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalBelumKembali = $peminjamans->count();
        $totalSudahKembali = $pengembalians->count();
        $totalTerlambat = $pengembalians->filter(function ($pengembalian) {
            return $pengembalian->tanggal_kembali_sebenarnya > $pengembalian->peminjaman->tanggal_pengembalian_rencana;
        })->count();

        return view('peminjam.pengembalian.index', compact(
            'peminjamans',
            'pengembalians',
            'totalBelumKembali',
            'totalSudahKembali',
            'totalTerlambat'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pengembalian = Pengembalian::where('pengembalian_id', $id)
            ->with(['peminjaman.details.alat', 'peminjaman.peminjam', 'penerima'])
            ->firstOrFail();

        // Pastikan user hanya bisa melihat pengembalian sendiri
        if ($pengembalian->peminjaman->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized access');
        }

        return view('peminjam.pengembalian.show', compact('pengembalian'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

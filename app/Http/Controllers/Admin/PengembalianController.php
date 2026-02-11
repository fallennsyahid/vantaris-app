<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusPeminjaman;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PengembalianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengembalians = Pengembalian::with(['peminjaman.details.alat', 'peminjaman.peminjam', 'penerima'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung total pengembalian
        $totalPengembalian = $pengembalians->count();

        // Hitung jumlah peminjam unik
        $totalPeminjam = $pengembalians->pluck('peminjaman.user_id')->unique()->count();

        // Hitung total peminjaman dengan status 
        $totalPeminjaman = Peminjaman::with('status', StatusPeminjaman::DIAMBIL)->count();

        return view('admin.pengembalian.index', compact(
            'pengembalians',
            'totalPengembalian',
            'totalPeminjaman',
            'totalPeminjam'
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
        //
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

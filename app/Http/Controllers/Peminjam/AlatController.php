<?php

namespace App\Http\Controllers\Peminjam;

use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alats = Alat::with('kategori')->paginate(9);
        $kategoris = Kategori::where('status', 'active')->get();
        $totalAlat = Alat::count();
        $alatTersedia = Alat::where('stok', '>', 0)->count();
        $alatMenipis = Alat::where('stok', '<=', 5)->where('stok', '>', 0)->count();
        $alatTidakTersedia = Alat::where('stok', '=', 0)->count();
        return view('peminjam.alat.index', compact('alats', 'kategoris', 'totalAlat', 'alatTersedia', 'alatMenipis', 'alatTidakTersedia'));
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

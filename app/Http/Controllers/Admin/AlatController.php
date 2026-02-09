<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        return view('admin.alat.index', compact('alats', 'kategoris', 'totalAlat', 'alatTersedia', 'alatMenipis', 'alatTidakTersedia'));
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
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,kategori_id',
            'stok' => 'required|integer|min:1',
            'foto_alat' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'nama_alat' => $request->nama_alat,
            'kategori_id' => $request->kategori_id,
            'stok' => $request->stok,
        ];

        if ($request->hasFile('foto_alat')) {
            $data['foto_alat'] = $request->file('foto_alat')->store('alat-images', 'public');
        }

        Alat::create($data);

        return redirect()->route('admin.alat.index')->with('success', 'Alat berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Alat $alat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alat $alat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alat $alat)
    {
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,kategori_id',
            'stok' => 'required|integer|min:1',
            'foto_alat' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'nama_alat' => $request->nama_alat,
            'kategori_id' => $request->kategori_id,
            'stok' => $request->stok,
        ];

        if ($request->hasFile('foto_alat')) {
            // Delete old image if exists
            if ($alat->foto_alat) {
                Storage::disk('public')->delete($alat->foto_alat);
            }
            $data['foto_alat'] = $request->file('foto_alat')->store('alat-images', 'public');
        }

        $alat->update($data);

        return redirect()->route('admin.alat.index')->with('success', 'Alat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alat $alat)
    {
        // Delete image if exists
        if ($alat->foto_alat) {
            Storage::disk('public')->delete($alat->foto_alat);
        }

        $alat->delete();
        return redirect()->route('admin.alat.index')->with('success', 'Alat berhasil dihapus.');
    }
}

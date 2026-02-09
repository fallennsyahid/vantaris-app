<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserPetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userPetugas = User::where('role', 'petugas')->latest()->get();
        $totalPetugas = User::where('role', 'petugas')->count();
        $totalPetugasActive = User::where('role', 'petugas')->where('status_blokir', false)->count();
        return view('admin.user-petugas.index', compact('userPetugas', 'totalPetugas', 'totalPetugasActive'));
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
        $defaultPassword = 'password123';

        $request->validate([
            'nama_petugas' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:users,email',
        ]);

        $username = Str::slug($request->nama_petugas);
        $originalUsername = $username;
        $count = 1;
        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . $count;
            $count++;
        }

        User::create([
            'nama_lengkap' => $request->nama_petugas,
            'username' => $username,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => Hash::make($defaultPassword),
            'role' => 'petugas',
            'status_blokir' => false,
            'durasi_blokir' => null,
        ]);

        return redirect()->route('admin.user-petugas.index')->with('success', "Akun petugas berhasil ditambahkan. Username: $username, Password: $defaultPassword");
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
    public function update(Request $request, $id)
    {
        $userPetugas = User::findOrFail($id);

        $request->validate([
            'edit_nama_petugas' => 'required|string|max:255',
            'edit_no_telp' => 'nullable|string|max:15',
            'edit_email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($id)
            ]
        ]);

        $userPetugas->nama_lengkap = $request->edit_nama_petugas;
        $userPetugas->email = $request->edit_email;
        $userPetugas->no_telp = $request->edit_no_telp;
        $userPetugas->save();

        return redirect()->route('admin.user-petugas.index')
            ->with('success', "Data petugas {$userPetugas->nama_lengkap} berhasil diperbarui.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $userPetugas = User::findOrFail($id);
        $userPetugas->delete();
        return redirect()->route('admin.user-petugas.index')->with('success', 'Akun petugas berhasil dihapus.');
    }

    public function toggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status_akun = !$user->status_akun;
        $user->save();

        return redirect()->back()->with('success', 'Status berhasil diubah!');
    }
}

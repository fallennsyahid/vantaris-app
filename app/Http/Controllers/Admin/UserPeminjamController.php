<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserPeminjamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userPeminjam = User::where('role', 'peminjam')->latest()->get();
        $totalAkunPeminjam = User::where('role', 'peminjam')->count();
        $totalPeminjamActive = User::where('role', 'peminjam')->where('status_akun', true)->count();
        return view('admin.user-peminjam.index', compact('userPeminjam', 'totalAkunPeminjam', 'totalPeminjamActive'));
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
            'nama_peminjam' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:users,email',
        ]);

        $username = Str::slug($request->nama_peminjam);
        $originalUsername = $username;
        $count = 1;
        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . $count;
            $count++;
        }

        User::create([
            'nama_lengkap' => $request->nama_peminjam,
            'username' => $username,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => Hash::make($defaultPassword),
            'role' => 'peminjam',
            'status_blokir' => false,
            'status_akun' => true,
            'durasi_blokir' => null,
        ]);

        return redirect()->route('admin.user-peminjam.index')->with('success', "Akun peminjam berhasil ditambahkan. Username: $username, Password: $defaultPassword");
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
        $userPeminjam = User::findOrFail($id);

        $request->validate([
            'edit_nama_peminjam' => 'required|string|max:255',
            'edit_no_telp' => 'nullable|string|max:15',
            'edit_email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($id)
            ]
        ]);

        $userPeminjam->nama_lengkap = $request->edit_nama_peminjam;
        $userPeminjam->email = $request->edit_email;
        $userPeminjam->no_telp = $request->edit_no_telp;
        $userPeminjam->save();

        return redirect()->route('admin.user-peminjam.index')
            ->with('success', "Data peminjam {$userPeminjam->nama_lengkap} berhasil diperbarui.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $userPeminjam)
    {
        $userPeminjam->delete();
        return redirect()->route('admin.user-peminjam.index')->with('success', 'Akun peminjam berhasil dihapus.');
    }

    public function toggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status_akun = !$user->status_akun;
        $user->save();

        return redirect()->back()->with('success', 'Status berhasil diubah!');
    }
}

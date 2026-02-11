<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogAktifitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $logs = LogAktivitas::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalLogs = $logs->count();
        $totalHariIni = LogAktivitas::whereDate('created_at', today())->count();

        return view('admin.log.index', compact('logs', 'totalLogs', 'totalHariIni'));
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

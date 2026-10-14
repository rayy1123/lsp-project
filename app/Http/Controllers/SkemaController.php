<?php

namespace App\Http\Controllers;

use App\Models\Skema;
use Illuminate\Http\Request;

class SkemaController extends Controller
{
    // Menampilkan daftar seluruh skema sertifikasi
    public function index()
    {
        $skemas = Skema::latest()->get();

        return view('skemas.index', compact('skemas'));
    }

    // Menampilkan formulir penambahan skema baru oleh Admin
    public function create()
    {
        return view('skemas.create');
    }

    // Menyimpan data skema sertifikasi baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_skema' => 'required|max:50|unique:skemas,kode_skema',
            'nama_skema' => 'required|max:255',
            'deskripsi' => 'nullable',
        ]);

        Skema::create($validated);

        return redirect()
            ->route('skemas.index')
            ->with('success', 'Skema berhasil ditambahkan.');
    }

    // Menampilkan formulir edit skema sertifikasi
    public function edit(Skema $skema)
    {
        return view('skemas.edit', compact('skema'));
    }

    // Memproses dan menyimpan pembaruan data skema
    public function update(Request $request, Skema $skema)
    {
        $validated = $request->validate([
            'kode_skema' => 'required|max:50|unique:skemas,kode_skema,' . $skema->id,
            'nama_skema' => 'required|max:255',
            'deskripsi' => 'nullable',
        ]);

        $skema->update($validated);

        return redirect()
            ->route('skemas.index')
            ->with('success', 'Skema berhasil diperbarui.');
    }

    // Menghapus data skema sertifikasi dari database
    public function destroy(Skema $skema)
    {
        $skema->delete();

        return redirect()
            ->route('skemas.index')
            ->with('success', 'Skema berhasil dihapus.');
    }

    // Menampilkan detail lengkap suatu skema sertifikasi
    public function show(Skema $skema)
    {
        return view('skemas.show', compact('skema'));
    }
}
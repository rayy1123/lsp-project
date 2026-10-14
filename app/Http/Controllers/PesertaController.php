<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\Skema;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    // Menampilkan daftar seluruh peserta sertifikasi dengan fitur pencarian nama/nik/email
    public function index(Request $request)
    {
        $query = Peserta::with('skema');

        if ($request->filled('search')) {
            $search = (string) $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pesertas = $query->latest()->get();

        return view('pesertas.index', compact('pesertas'));
    }

    // Menampilkan halaman formulir tambah peserta baru oleh Admin
    public function create()
    {
        $skemas = Skema::all();

        return view('pesertas.create', compact('skemas'));
    }

    // Menyimpan data peserta baru ke database dengan validasi
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:50',
                "regex:/^[\pL\s\'`\"]+$/u",
            ],
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nik' => 'required|digits:16|unique:pesertas,nik',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'email' => 'required|email|max:255',
            'no_hp' => 'required|regex:/^[0-9+]+$/|max:13',
            'alamat' => 'required',
            'skema_id' => 'required|exists:skemas,id',
            'tanggal_daftar' => 'required|date',
        ], [
            'nama.max' => 'Nama lengkap maksimal 50 karakter.',
            'nama.regex' => 'Nama tidak boleh mengandung angka. Hanya boleh huruf, spasi, dan tanda kutip.',
            'nik.digits' => 'NIK harus tepat 16 digit angka.',
            'no_hp.max' => 'Nomor Handphone maksimal 13 karakter.',
            'no_hp.regex' => 'Nomor Handphone hanya boleh berisi angka.',
        ]);

        Peserta::create($validated);

        return redirect()
            ->route('pesertas.index')
            ->with('success', 'Data peserta berhasil ditambahkan.');
    }

    // Menampilkan detail lengkap satu peserta sertifikasi
    public function show(Peserta $peserta)
    {
        $peserta->load('skema');

        return view('pesertas.show', compact('peserta'));
    }

    // Menampilkan formulir edit data peserta
    public function edit(Peserta $peserta)
    {
        $skemas = Skema::all();

        return view('pesertas.edit', compact('peserta', 'skemas'));
    }

    // Memproses dan menyimpan pembaruan data peserta
    public function update(Request $request, Peserta $peserta)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:50',
                "regex:/^[\pL\s\'`\"]+$/u",
            ],
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nik' => "required|digits:16|unique:pesertas,nik,{$peserta->getKey()}",
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'email' => 'required|email|max:255',
            'no_hp' => 'required|regex:/^[0-9+]+$/|max:13',
            'alamat' => 'required',
            'skema_id' => 'required|exists:skemas,id',
            'tanggal_daftar' => 'required|date',
        ], [
            'nama.max' => 'Nama lengkap maksimal 50 karakter.',
            'nama.regex' => 'Nama tidak boleh mengandung angka. Hanya boleh huruf, spasi, dan tanda kutip.',
            'nik.digits' => 'NIK harus tepat 16 digit angka.',
            'no_hp.max' => 'Nomor Handphone maksimal 13 karakter.',
            'no_hp.regex' => 'Nomor Handphone hanya boleh berisi angka.',
        ]);

        $peserta->update($validated);

        return redirect()
            ->route('pesertas.index')
            ->with('success', 'Data peserta berhasil diperbarui.');
    }

    // Menghapus data peserta dari database
    public function destroy(Peserta $peserta)
    {
        $peserta->delete();

        return redirect()
            ->route('pesertas.index')
            ->with('success', 'Data peserta berhasil dihapus.');
    }
}
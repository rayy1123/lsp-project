<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\Skema;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesertaProfileController extends Controller
{
    // Menampilkan dashboard peserta
    public function dashboard()
    {
        $user = Auth::user();

        if ($user->role === 'admin' || $user->email === 'admin@gmail.com') {
            return redirect()->route('dashboard');
        }

        $peserta = $user->peserta;

        if (!$peserta) {
            return redirect()->route('peserta.profil.create');
        }

        $skemas = Skema::all();

        return view('pesertas.dashboard', compact('peserta', 'skemas'));
    }

    // Mengganti / memilih skema sertifikasi baru dari dashboard peserta
    public function pilihSkemaBaru(Request $request)
    {
        $user = Auth::user();
        $peserta = $user->peserta;

        if (!$peserta) {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'skema_id' => 'required|exists:skemas,id',
        ], [
            'skema_id.required' => 'Pilih skema sertifikasi yang ingin ditempuh.',
            'skema_id.exists' => 'Skema sertifikasi yang dipilih tidak valid.',
        ]);

        if ($peserta->skema_id != $validated['skema_id']) {
            $peserta->skema_id = $validated['skema_id'];
            $peserta->status_kompetensi = 'Belum Mengumpulkan';
            $peserta->save();

            $skemaBaru = Skema::find($validated['skema_id']);

            return redirect()
                ->route('dashboard')
                ->with('success', 'Skema sertifikasi berhasil diperbarui ke: ' . $skemaBaru->nama_skema);
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'Skema yang dipilih saat ini sudah aktif.');
    }

    // Menampilkan formulir pelengkapan profil
    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'admin' || $user->email === 'admin@gmail.com') {
            return redirect()->route('dashboard');
        }

        // Jika profil sudah ada, arahkan langsung ke dashboard
        if ($user->peserta) {
            return redirect()->route('dashboard');
        }

        $skemas = Skema::all();

        return view('pesertas.profil', compact('user', 'skemas'));
    }

    // Menyimpan profil peserta (pertama kali setelah register)
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'admin' || $user->email === 'admin@gmail.com') {
            return redirect()->route('dashboard');
        }

        // Mencegah satu akun membuat profil berkali-kali
        if ($user->peserta) {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'nik' => 'required|string|max:255|unique:pesertas,nik',
            'alamat' => 'required|string|max:1000',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'skema_id' => 'required|exists:skemas,id',
        ]);

        Peserta::create([
            'user_id' => $user->id,
            'nama' => $user->name,
            'nik' => $validated['nik'],
            'email' => $user->email,
            'no_hp' => $user->no_hp ?? null,
            'alamat' => $validated['alamat'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'skema_id' => $validated['skema_id'],
            'tanggal_daftar' => now()->toDateString(),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Profil peserta berhasil dilengkapi.');
    }

    // Menampilkan form edit profil milik peserta yang login
    public function edit()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('dashboard');
        }

        $peserta = Peserta::where('user_id', $user->id)->first();

        if (!$peserta) {
            return redirect()->route('peserta.profil.create');
        }

        $skemas = Skema::all();

        return view('pesertas.edit-profile', compact(
            'peserta',
            'skemas'
        ));
    }

    // Menyimpan perubahan biodata profil peserta yang login
    public function update(Request $request)
    {
        $user = Auth::user();
        $peserta = Peserta::where('user_id', $user->id)->first();

        if (!$peserta) {
            return redirect()->route('peserta.profil.create');
        }

        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:50',
                "regex:/^[\pL\s\'`\"]+$/u",
            ],
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'nik' => 'required|digits:16|unique:pesertas,nik,' . $peserta->id,
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id . '|unique:pesertas,email,' . $peserta->id,
            'no_hp' => 'required|regex:/^[0-9+]+$/|max:13',
            'alamat' => 'required|string|max:1000',
            'skema_id' => 'required|exists:skemas,id',
        ], [
            'nama.max' => 'Nama lengkap maksimal 50 karakter.',
            'nama.regex' => 'Nama tidak boleh mengandung angka. Hanya boleh huruf, spasi, dan tanda kutip.',
            'nik.digits' => 'NIK harus tepat 16 digit angka.',
            'no_hp.max' => 'Nomor Handphone maksimal 13 karakter.',
            'no_hp.regex' => 'Nomor Handphone hanya boleh berisi angka.',
        ]);

        // Update data pada tabel pesertas
        $peserta->nama = $validated['nama'];
        $peserta->nik = $validated['nik'];
        $peserta->tempat_lahir = $validated['tempat_lahir'];
        $peserta->tanggal_lahir = $validated['tanggal_lahir'];
        $peserta->email = $validated['email'];
        $peserta->no_hp = $validated['no_hp'] ?? null;
        $peserta->alamat = $validated['alamat'];
        $peserta->jenis_kelamin = $validated['jenis_kelamin'];
        $peserta->skema_id = $validated['skema_id'];
        $peserta->save();

        // Sinkronkan perubahan ke tabel users
        User::where('id', $user->id)->update([
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
        ]);

        return redirect()
            ->route('peserta.profil.edit')
            ->with('success', 'Biodata profil berhasil diperbarui.');
    }

    // Memproses penggantian password akun peserta
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        // Update password akun di database
        User::where('id', $user->id)->update([
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('peserta.profil.edit')
            ->with('success', 'Password akun berhasil diubah! Silakan gunakan password baru untuk login berikutnya.');
    }
}
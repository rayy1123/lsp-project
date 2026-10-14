<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SkemaController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PesertaProfileController;
use App\Http\Controllers\TugasController;

// =========================================================================
// RUTE UMUM & AUTENTIKASI (Bisa diakses publik / belum login)
// =========================================================================

// Halaman utama (Welcome)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Menampilkan halaman formulir login
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

// Memproses autentikasi login (email & password)
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

// Memproses logout akun pengguna
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

// Menampilkan halaman formulir registrasi akun peserta baru
Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

// Memproses pendaftaran akun peserta baru sekaligus data biodatanya
Route::post('/register', [AuthController::class, 'register'])
    ->name('register.process');


// =========================================================================
// RUTE DASHBOARD UTAMA (Wajib Login)
// =========================================================================

// Mengarahkan ke dashboard yang sesuai (Admin ke dashboard admin, Peserta ke dashboard peserta)
Route::get('/dashboard', function () {
    $user = \Illuminate\Support\Facades\Auth::user();

    // Pastikan akun admin@gmail.com selalu memiliki role admin
    if ($user->email === 'admin@gmail.com' && $user->role !== 'admin') {
        \App\Models\User::where('id', $user->id)->update(['role' => 'admin']);
        $user->role = 'admin';
    }

    if ($user->role === 'admin' || $user->email === 'admin@gmail.com') {
        $totalPeserta = \App\Models\Peserta::count();
        $totalSkema = \App\Models\Skema::count();

        return view('dashboard', compact(
            'totalPeserta',
            'totalSkema'
        ));
    }

    $peserta = \App\Models\Peserta::where(
        'user_id',
        $user->id
    )->first();

    if (!$peserta) {
        return redirect()->route('peserta.profil.create');
    }

    $skemas = \App\Models\Skema::all();

    return view('pesertas.dashboard', compact('peserta', 'skemas'));
})->middleware('auth')->name('dashboard');


// =========================================================================
// RUTE KHUSUS PESERTA (Wajib Login sebagai Peserta)
// =========================================================================
Route::middleware('auth')->group(function () {
    // Menampilkan dashboard khusus peserta
    Route::get('/dashboard-peserta', [
        PesertaProfileController::class,
        'dashboard'
    ])->name('dashboard.peserta');

    // Memproses pemilihan / perpindahan skema sertifikasi baru dari dashboard
    Route::post('/pilih-skema-baru', [
        PesertaProfileController::class,
        'pilihSkemaBaru'
    ])->name('peserta.pilih.skema');

    // Menampilkan halaman formulir pengisian data awal peserta (jika belum ada)
    Route::get('/profil-peserta/isi', [
        PesertaProfileController::class,
        'create'
    ])->name('peserta.profil.create');

    // Menyimpan data awal peserta baru
    Route::post('/profil-peserta/isi', [
        PesertaProfileController::class,
        'store'
    ])->name('peserta.profil.store');

    // Menampilkan halaman pengaturan biodata diri dan ubah password
    Route::get('/data-peserta-saya', [
        PesertaProfileController::class,
        'edit'
    ])->name('peserta.profil.edit');

    // Memproses pembaruan biodata diri peserta
    Route::put('/data-peserta-saya', [
        PesertaProfileController::class,
        'update'
    ])->name('peserta.profil.update');

    // Memproses penggantian password akun peserta
    Route::put('/ubah-password-peserta', [
        PesertaProfileController::class,
        'updatePassword'
    ])->name('peserta.password.update');

    // Menampilkan daftar tugas skema sertifikasi milik peserta yang sedang login
    Route::get('/tugas-saya', [
        TugasController::class,
        'indexPeserta'
    ])->name('peserta.tugas.index');

    // Mengumpulkan jawaban tugas peserta (link project dan/atau upload gambar bukti)
    Route::post('/tugas-saya/{tugas}/kumpulkan', [
        TugasController::class,
        'submitPeserta'
    ])->name('peserta.tugas.submit');
});


// =========================================================================
// RUTE KHUSUS ADMINISTRATOR (Wajib Login & Memiliki Role Admin)
// =========================================================================
Route::middleware(['auth', 'admin'])->group(function () {
    // CRUD Manajemen Master Data Skema Sertifikasi
    Route::resource('skemas', SkemaController::class);

    // CRUD Manajemen Data Peserta Sertifikasi
    Route::resource('pesertas', PesertaController::class);

    // Menampilkan daftar seluruh tugas yang dikelompokkan per skema
    Route::get('/admin/tugas', [TugasController::class, 'indexAdmin'])->name('admin.tugas.index');

    // Menampilkan formulir pembuatan tugas baru untuk suatu skema
    Route::get('/admin/tugas/tambah', [TugasController::class, 'createAdmin'])->name('admin.tugas.create');

    // Menyimpan tugas baru untuk skema tertentu
    Route::post('/admin/tugas', [TugasController::class, 'storeAdmin'])->name('admin.tugas.store');

    // Menampilkan formulir edit tugas skema
    Route::get('/admin/tugas/{tugas}/edit', [TugasController::class, 'editAdmin'])->name('admin.tugas.edit');

    // Menyimpan perubahan tugas skema
    Route::put('/admin/tugas/{tugas}', [TugasController::class, 'updateAdmin'])->name('admin.tugas.update');

    // Menghapus tugas skema beserta berkas lampirannya
    Route::delete('/admin/tugas/{tugas}', [TugasController::class, 'destroyAdmin'])->name('admin.tugas.destroy');

    // Menampilkan daftar seluruh peserta dan progres tugas pada skema tersebut
    Route::get('/admin/tugas/{tugas}/penilaian', [TugasController::class, 'penilaianAdmin'])->name('admin.tugas.penilaian');

    // Menampilkan formulir penilaian dan pemeriksaan jawaban satu peserta
    Route::get('/admin/tugas/{tugas}/peserta/{peserta}', [TugasController::class, 'reviewPesertaAdmin'])->name('admin.tugas.reviewPeserta');

    // Menyimpan keputusan penilaian asesmen (Kompeten / Belum Kompeten) beserta catatan
    Route::post('/admin/tugas/{tugas}/peserta/{peserta}/nilai', [TugasController::class, 'simpanNilaiAdmin'])->name('admin.tugas.simpanNilai');
});

@extends('layouts.admin')

@section('title', 'Data Peserta Saya')
@section('page-title', 'Pengaturan Profil & Akun')

@section('css')
<style>
    .form-card {
        max-width: 800px;
        margin: 0 auto 30px;
        background: white;
        padding: 28px;
        border-radius: 10px;
        border: 1px solid #eadada;
        box-shadow: 0 2px 8px rgba(128, 0, 0, 0.06);
    }

    .form-card h2 {
        color: #800000;
        margin-top: 0;
        margin-bottom: 6px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        font-weight: bold;
        font-size: 14px;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 11px 12px;
        border: 1px solid #d9caca;
        border-radius: 6px;
        font: inherit;
        background: white;
        box-sizing: border-box;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: 2px solid #d8a0a0;
        border-color: #800000;
    }

    .error {
        color: #b42318;
        font-size: 13px;
        margin-top: 5px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 24px;
    }

    .save-btn,
    .cancel-btn {
        padding: 11px 18px;
        border-radius: 6px;
        text-decoration: none;
        border: none;
        font-size: 14px;
        cursor: pointer;
        font-weight: bold;
    }

    .save-btn {
        background: #800000;
        color: white;
    }

    .save-btn:hover {
        background: #600000;
    }

    .cancel-btn {
        background: #eee;
        color: #333;
    }

    .alert-error-box {
        background: #ffe5e5;
        color: #b00020;
        padding: 14px 18px;
        border-radius: 6px;
        margin-bottom: 25px;
        border: 1px solid #ffcccc;
    }

    .alert-error-box ul {
        margin: 5px 0 0;
        padding-left: 20px;
    }
</style>
@endsection

@section('content')
<div style="max-width: 800px; margin: 0 auto;">

    {{-- Tampilkan detail semua error jika ada --}}
    @if ($errors->any())
        <div class="alert-error-box">
            <strong>Terjadi kesalahan pada data yang dikirimkan:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- KARTU 1: EDIT BIODATA PESERTA --}}
    <div class="form-card">
        <h2>Edit Biodata Diri</h2>
        <p style="color: #666; font-size: 14px; margin-top: 0; margin-bottom: 22px;">
            Perbarui data diri Anda yang terdaftar pada sistem sertifikasi.
        </p>

        <form action="{{ route('peserta.profil.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nama">Nama Lengkap *</label>
                <input id="nama" type="text" name="nama" maxlength="50"
                    value="{{ old('nama', $peserta->nama) }}" required>
                @error('nama')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="jenis_kelamin">Jenis Kelamin *</label>
                <select id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="Laki-laki" {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                        Laki-laki
                    </option>
                    <option value="Perempuan" {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                        Perempuan
                    </option>
                </select>
                @error('jenis_kelamin')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="nik">NIK (16 Digit) *</label>
                <input id="nik" type="text" name="nik" maxlength="16" pattern="[0-9]{16}"
                    value="{{ old('nik', $peserta->nik) }}" required>
                @error('nik')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1; min-width: 200px;">
                    <label for="tempat_lahir">Tempat Lahir *</label>
                    <input id="tempat_lahir" type="text" name="tempat_lahir"
                        value="{{ old('tempat_lahir', $peserta->tempat_lahir) }}" required>
                    @error('tempat_lahir')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="flex: 1; min-width: 200px;">
                    <label for="tanggal_lahir">Tanggal Lahir *</label>
                    <input id="tanggal_lahir" type="date" name="tanggal_lahir"
                        value="{{ old('tanggal_lahir', $peserta->tanggal_lahir) }}" required>
                    @error('tanggal_lahir')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="email">Alamat Email *</label>
                <input id="email" type="email" name="email"
                    value="{{ old('email', $peserta->email) }}" required>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="no_hp">Nomor Handphone (Maks 13 Digit) *</label>
                <input id="no_hp" type="tel" name="no_hp" maxlength="13"
                    value="{{ old('no_hp', $peserta->no_hp) }}" required>
                @error('no_hp')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="alamat">Alamat Lengkap *</label>
                <textarea id="alamat" name="alamat" rows="3" required>{{ old('alamat', $peserta->alamat) }}</textarea>
                @error('alamat')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="skema_id">Skema Sertifikasi *</label>
                <select id="skema_id" name="skema_id" required>
                    @foreach ($skemas as $skema)
                        <option value="{{ $skema->id }}"
                            {{ old('skema_id', $peserta->skema_id) == $skema->id ? 'selected' : '' }}>
                            {{ $skema->kode_skema }} - {{ $skema->nama_skema }}
                        </option>
                    @endforeach
                </select>
                @error('skema_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="save-btn">Simpan Biodata</button>
                <a href="{{ route('dashboard') }}" class="cancel-btn">Kembali ke Dashboard</a>
            </div>
        </form>
    </div>

    {{-- KARTU 2: UBAH PASSWORD AKUN SECARA TERPISAH --}}
    <div class="form-card" style="border-top: 4px solid #800020;">
        <h2>Ubah Password Akun</h2>
        <p style="color: #666; font-size: 14px; margin-top: 0; margin-bottom: 22px;">
            Gunakan formulir ini untuk mengganti password login akun Anda. Password minimal 6 karakter.
        </p>

        <form action="{{ route('peserta.password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="new_password">Password Baru (Minimal 6 karakter) *</label>
                <div style="position: relative;">
                    <input
                        id="new_password"
                        type="password"
                        name="password"
                        minlength="6"
                        placeholder="Ketik password baru Anda"
                        autocomplete="new-password"
                        required
                        style="padding-right: 42px;"
                    >
                    <button type="button" onclick="togglePassword('new_password', this)" title="Lihat password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 16px; color: #666; padding: 2px;">
                        👁️
                    </button>
                </div>
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="new_password_confirmation">Konfirmasi Password Baru *</label>
                <div style="position: relative;">
                    <input
                        id="new_password_confirmation"
                        type="password"
                        name="password_confirmation"
                        minlength="6"
                        placeholder="Ketik ulang password baru Anda"
                        autocomplete="new-password"
                        required
                        style="padding-right: 42px;"
                    >
                    <button type="button" onclick="togglePassword('new_password_confirmation', this)" title="Lihat password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 16px; color: #666; padding: 2px;">
                        👁️
                    </button>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="save-btn" style="background: #222;">
                    Perbarui Password Akun
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        if (input.type === 'password') {
            input.type = 'text';
            btn.textContent = '🙈';
            btn.title = 'Sembunyikan password';
        } else {
            input.type = 'password';
            btn.textContent = '👁️';
            btn.title = 'Lihat password';
        }
    }
</script>
@endsection

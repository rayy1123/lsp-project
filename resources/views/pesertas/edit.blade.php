@extends('layouts.admin')

@section('title', 'Edit Peserta')

@section('page-title', 'Edit Data Peserta')

@section('content')

<div class="card">

    <h2>Edit Data Peserta</h2>
    <p>Perbarui data peserta sertifikasi.</p>

    @if ($errors->any())
        <div style="
            background: #ffe5e5;
            color: #b00000;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        ">
            <strong>Terjadi kesalahan:</strong>

            <ul style="margin-bottom: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pesertas.update', $peserta->id) }}" method="POST">

        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label>Nama Lengkap</label>

            <input
                type="text"
                name="nama"
                value="{{ old('nama', $peserta->nama) }}"
                style="width: 100%; padding: 10px; margin-top: 5px; box-sizing: border-box;"
            >
        </div>

        <div style="margin-bottom: 15px;">
            <label>Jenis Kelamin</label>

            <select
                name="jenis_kelamin"
                style="width: 100%; padding: 10px; margin-top: 5px; box-sizing: border-box;"
            >
                <option value="">-- Pilih Jenis Kelamin --</option>
                <option value="Laki-laki" {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label>NIK</label>

            <input
                type="text"
                name="nik"
                value="{{ old('nik', $peserta->nik) }}"
                maxlength="16"
                style="width: 100%; padding: 10px; margin-top: 5px; box-sizing: border-box;"
            >
        </div>

        <div style="display: flex; gap: 15px; margin-bottom: 15px;">
            <div style="flex: 1;">
                <label>Tempat Lahir</label>
                <input
                    type="text"
                    name="tempat_lahir"
                    value="{{ old('tempat_lahir', $peserta->tempat_lahir) }}"
                    style="width: 100%; padding: 10px; margin-top: 5px; box-sizing: border-box;"
                >
            </div>
            <div style="flex: 1;">
                <label>Tanggal Lahir</label>
                <input
                    type="date"
                    name="tanggal_lahir"
                    value="{{ old('tanggal_lahir', $peserta->tanggal_lahir) }}"
                    style="width: 100%; padding: 10px; margin-top: 5px; box-sizing: border-box;"
                >
            </div>
        </div>

        <div style="margin-bottom: 15px;">
            <label>Email</label>

            <input
                type="email"
                name="email"
                value="{{ old('email', $peserta->email) }}"
                style="width: 100%; padding: 10px; margin-top: 5px; box-sizing: border-box;"
            >
        </div>

        <div style="margin-bottom: 15px;">
            <label>No. HP</label>

            <input
                type="text"
                name="no_hp"
                value="{{ old('no_hp', $peserta->no_hp) }}"
                style="width: 100%; padding: 10px; margin-top: 5px; box-sizing: border-box;"
            >
        </div>

        <div style="margin-bottom: 15px;">
            <label>Alamat</label>

            <textarea
                name="alamat"
                rows="4"
                style="width: 100%; padding: 10px; margin-top: 5px; box-sizing: border-box;"
            >{{ old('alamat', $peserta->alamat) }}</textarea>
        </div>

        <div style="margin-bottom: 15px;">
            <label>Skema Sertifikasi</label>

            <select
                name="skema_id"
                style="width: 100%; padding: 10px; margin-top: 5px;"
            >

                <option value="">-- Pilih Skema Sertifikasi --</option>

                @foreach ($skemas as $skema)

                    <option
                        value="{{ $skema->id }}"
                        {{ old('skema_id', $peserta->skema_id) == $skema->id ? 'selected' : '' }}
                    >
                        {{ $skema->kode_skema }} - {{ $skema->nama_skema }}
                    </option>

                @endforeach

            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label>Tanggal Daftar</label>

            <input
                type="date"
                name="tanggal_daftar"
                value="{{ old('tanggal_daftar', $peserta->tanggal_daftar) }}"
                style="width: 100%; padding: 10px; margin-top: 5px; box-sizing: border-box;"
            >
        </div>

        <div>

            <a
                href="{{ route('pesertas.index') }}"
                style="
                    display: inline-block;
                    padding: 10px 15px;
                    background: #ccc;
                    color: #222;
                    text-decoration: none;
                    border-radius: 6px;
                "
            >
                Kembali
            </a>

            <button
                type="submit"
                style="
                    padding: 10px 15px;
                    background: #222;
                    color: white;
                    border: none;
                    border-radius: 6px;
                    cursor: pointer;
                "
            >
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>

@endsection
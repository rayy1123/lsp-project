@extends('layouts.admin')

@section('title', 'Buat Tugas Skema')
@section('page-title', 'Buat Tugas Berdasarkan Skema')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <h2 style="margin-top: 0; color: #800020;">Buat Tugas Baru untuk Skema Sertifikasi</h2>
    <p style="color: #666; margin-bottom: 25px;">
        Pilih skema sertifikasi. Seluruh peserta yang terdaftar pada skema ini akan otomatis mendapatkan tugas ini.
    </p>

    @if ($errors->any())
        <div class="alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.tugas.store') }}" method="POST">
        @csrf

        {{-- Pilih Skema --}}
        <div style="margin-bottom: 18px;">
            <label style="display: block; font-weight: bold; margin-bottom: 6px;">Skema Sertifikasi Target *</label>
            <select name="skema_id" required style="width: 100%; padding: 11px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px;">
                <option value="">-- Pilih Skema Sertifikasi --</option>
                @foreach ($skemas as $s)
                    <option value="{{ $s->id }}" @selected(old('skema_id', $selectedSkemaId) == $s->id)>
                        {{ $s->kode_skema }} - {{ $s->nama_skema }} ({{ $s->pesertas->count() }} Peserta)
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Judul Tugas --}}
        <div style="margin-bottom: 18px;">
            <label style="display: block; font-weight: bold; margin-bottom: 6px;">Judul Tugas / Uji Praktik *</label>
            <input
                type="text"
                name="judul"
                value="{{ old('judul') }}"
                placeholder="Contoh: Uji Kompetensi JWD - Pembuatan Aplikasi Web CRUD"
                required
                style="width: 100%; padding: 11px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
            >
        </div>

        {{-- Deskripsi / Instruksi Tugas --}}
        <div style="margin-bottom: 22px;">
            <label style="display: block; font-weight: bold; margin-bottom: 6px;">Petunjuk & Deskripsi Tugas *</label>
            <textarea
                name="deskripsi"
                rows="6"
                placeholder="Tuliskan petunjuk pengerjaan tugas, kriteria unjuk kerja, dan ketentuan pengumpulan (misal: masukkan link repository GitHub dan screenshot tampilan aplikasi)..."
                required
                style="width: 100%; padding: 11px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; font-family: inherit; box-sizing: border-box;"
            >{{ old('deskripsi') }}</textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.tugas.index') }}" style="padding: 10px 18px; background: #e0e0e0; color: #333; text-decoration: none; border-radius: 6px; font-weight: bold;">
                Kembali
            </a>
            <button type="submit" style="padding: 10px 22px; background: #800020; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
                Terbitkan Tugas Skema
            </button>
        </div>
    </form>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Edit Tugas Skema')
@section('page-title', 'Edit Tugas Skema Sertifikasi')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <h2 style="margin-top: 0; color: #800020;">Edit Tugas Skema</h2>
    <p style="color: #666; margin-bottom: 25px;">Perbarui judul atau instruksi tugas.</p>

    @if ($errors->any())
        <div class="alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.tugas.update', $tugas->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Pilih Skema --}}
        <div style="margin-bottom: 18px;">
            <label style="display: block; font-weight: bold; margin-bottom: 6px;">Skema Sertifikasi *</label>
            <select name="skema_id" required style="width: 100%; padding: 11px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px;">
                @foreach ($skemas as $s)
                    <option value="{{ $s->id }}" @selected(old('skema_id', $tugas->skema_id) == $s->id)>
                        {{ $s->kode_skema }} - {{ $s->nama_skema }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Judul Tugas --}}
        <div style="margin-bottom: 18px;">
            <label style="display: block; font-weight: bold; margin-bottom: 6px;">Judul Tugas *</label>
            <input
                type="text"
                name="judul"
                value="{{ old('judul', $tugas->judul) }}"
                required
                style="width: 100%; padding: 11px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
            >
        </div>

        {{-- Deskripsi / Petunjuk --}}
        <div style="margin-bottom: 22px;">
            <label style="display: block; font-weight: bold; margin-bottom: 6px;">Deskripsi / Petunjuk Pengerjaan *</label>
            <textarea
                name="deskripsi"
                rows="6"
                required
                style="width: 100%; padding: 11px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; font-family: inherit; box-sizing: border-box;"
            >{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.tugas.index') }}" style="padding: 10px 18px; background: #e0e0e0; color: #333; text-decoration: none; border-radius: 6px; font-weight: bold;">
                Batal
            </a>
            <button type="submit" style="padding: 10px 22px; background: #800020; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@extends('layouts.admin')

@section('content')

<div class="card">
    <h2>Edit Skema Sertifikasi</h2>

    <p>Silakan ubah data skema sertifikasi.</p>

    <form action="{{ route('skemas.update', $skema->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="kode_skema">Kode Skema</label>
            <input
                type="text"
                id="kode_skema"
                name="kode_skema"
                value="{{ old('kode_skema', $skema->kode_skema) }}"
                placeholder="Contoh: JWD-001"
            >

            @error('kode_skema')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="nama_skema">Nama Skema</label>
            <input
                type="text"
                id="nama_skema"
                name="nama_skema"
                value="{{ old('nama_skema', $skema->nama_skema) }}"
                placeholder="Contoh: Junior Web Developer"
            >

            @error('nama_skema')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea
                id="deskripsi"
                name="deskripsi"
                placeholder="Masukkan deskripsi skema..."
            >{{ old('deskripsi', $skema->deskripsi) }}</textarea>

            @error('deskripsi')
                <small class="error">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-buttons">
            <a href="{{ route('skemas.index') }}" class="btn btn-secondary">
                Kembali
            </a>

            <button type="submit" class="btn btn-primary">
                Update
            </button>
        </div>
    </form>
</div>

<style>
    .card {
        background: white;
        padding: 32px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin: 20px 32px;
    }

    .card h2 {
        margin-top: 0;
        font-size: 30px;
        color: #2c2c2c;
    }

    .card p {
        font-size: 18px;
        margin-bottom: 25px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 18px;
        margin-bottom: 8px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        box-sizing: border-box;
        padding: 14px;
        border: 1px solid #aaa;
        border-radius: 4px;
        font-size: 16px;
    }

    .form-group textarea {
        min-height: 125px;
        resize: vertical;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #800000;
    }

    .error {
        display: block;
        color: #b00020;
        margin-top: 5px;
    }

    .form-buttons {
        display: flex;
        gap: 8px;
        margin-top: 10px;
    }

    .btn {
        display: inline-block;
        padding: 12px 20px;
        border-radius: 6px;
        text-decoration: none;
        border: none;
        font-size: 16px;
        cursor: pointer;
    }

    .btn-primary {
        background: #222;
        color: white;
    }

    .btn-primary:hover {
        background: #800000;
    }

    .btn-secondary {
        background: #777;
        color: white;
    }

    .btn-secondary:hover {
        background: #555;
    }
</style>

@endsection
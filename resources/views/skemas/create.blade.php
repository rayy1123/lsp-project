@extends('layouts.admin')

@section('title', 'Tambah Skema')

@section('page-title', 'Tambah Skema Sertifikasi')

@section('content')

<div class="card">

    <h2>Tambah Skema Sertifikasi</h2>

    <p>Silakan isi data skema sertifikasi.</p>

    @if ($errors->any())
        <div style="
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        ">
            <strong>Terjadi kesalahan:</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('skemas.store') }}" method="POST">

        @csrf

        <div style="margin-bottom: 15px;">
            <label>Kode Skema</label>

            <input
                type="text"
                name="kode_skema"
                value="{{ old('kode_skema') }}"
                placeholder="Contoh: JWD-001"
                style="
                    width: 100%;
                    padding: 10px;
                    margin-top: 5px;
                "
            >
        </div>

        <div style="margin-bottom: 15px;">
            <label>Nama Skema</label>

            <input
                type="text"
                name="nama_skema"
                value="{{ old('nama_skema') }}"
                placeholder="Contoh: Junior Web Developer"
                style="
                    width: 100%;
                    padding: 10px;
                    margin-top: 5px;
                "
            >
        </div>

        <div style="margin-bottom: 15px;">
            <label>Deskripsi</label>

            <textarea
                name="deskripsi"
                rows="5"
                placeholder="Masukkan deskripsi skema..."
                style="
                    width: 100%;
                    padding: 10px;
                    margin-top: 5px;
                "
            >{{ old('deskripsi') }}</textarea>
        </div>

        <a href="{{ route('skemas.index') }}"
           style="
                display: inline-block;
                padding: 10px 15px;
                background: #777;
                color: white;
                text-decoration: none;
                border-radius: 6px;
           ">
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
            "
        >
            Simpan
        </button>

    </form>

</div>

@endsection
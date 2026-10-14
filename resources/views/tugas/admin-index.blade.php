@extends('layouts.admin')

@section('title', 'Tugas Skema & Penilaian')
@section('page-title', 'Tugas Skema Sertifikasi & Penilaian')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 10px;">
        <div>
            <h2 style="margin: 0; color: #800020;">Kelola Tugas Berdasarkan Skema</h2>
            <p style="margin: 5px 0 0; color: #666;">
                Tugas dibuat per skema sertifikasi. Semua peserta yang memilih skema tersebut akan otomatis menerima tugas ini.
            </p>
        </div>
        <a href="{{ route('admin.tugas.create') }}" style="background: #800020; color: white; padding: 11px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 14px;">
            + Buat Tugas Skema Baru
        </a>
    </div>

    @forelse($skemas as $skema)
        <div style="border: 1px solid #eadada; border-radius: 8px; margin-bottom: 25px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">
            <div style="background: #fdf8f8; padding: 14px 20px; border-bottom: 1px solid #eadada; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div>
                    <span style="background: #800020; color: white; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                        {{ $skema->kode_skema }}
                    </span>
                    <strong style="margin-left: 8px; font-size: 16px; color: #333;">
                        {{ $skema->nama_skema }}
                    </strong>
                    <span style="color: #777; font-size: 13px; margin-left: 10px;">
                        ({{ $skema->pesertas->count() }} Peserta Terdaftar)
                    </span>
                </div>
                <div>
                    <a href="{{ route('admin.tugas.create', ['skema_id' => $skema->id]) }}" style="font-size: 13px; color: #800020; font-weight: bold; text-decoration: none;">
                        + Tambah Tugas di Skema Ini
                    </a>
                </div>
            </div>

            <div style="padding: 15px 20px;">
                @if($skema->tugas->isEmpty())
                    <p style="color: #999; margin: 10px 0; font-style: italic;">
                        Belum ada tugas untuk skema ini. Klik "+ Tambah Tugas di Skema Ini" untuk membuat penugasan.
                    </p>
                @else
                    <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse; font-size: 14px;">
                        <thead>
                            <tr style="border-bottom: 2px solid #eee; text-align: left; color: #555;">
                                <th width="5%">No</th>
                                <th width="35%">Judul Tugas</th>
                                <th width="30%">Deskripsi / Petunjuk</th>
                                <th width="15%">Pengumpulan</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($skema->tugas as $tugas)
                                <tr style="border-bottom: 1px solid #f0f0f0;">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong style="color: #800020;">{{ $tugas->judul }}</strong><br>
                                        <small style="color: #888;">Dibuat: {{ $tugas->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <div style="max-height: 60px; overflow: hidden; text-overflow: ellipsis; line-height: 1.4; color: #555;">
                                            {{ Str::limit($tugas->deskripsi, 100) }}
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $totalTerkumpul = $tugas->pengumpulan->count();
                                            $totalKompeten = $tugas->pengumpulan->where('status', 'Kompeten')->count();
                                        @endphp
                                        <span style="font-size: 13px;">
                                            📥 <strong>{{ $totalTerkumpul }}</strong> terkumpul<br>
                                            <span style="color: #198754; font-size: 12px;">✓ {{ $totalKompeten }} Kompeten</span>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.tugas.penilaian', $tugas->id) }}" style="display: inline-block; padding: 6px 12px; background: #800020; color: white; text-decoration: none; border-radius: 4px; font-size: 12px; font-weight: bold; margin-bottom: 4px;">
                                            Nilai Peserta ({{ $skema->pesertas->count() }})
                                        </a>
                                        <br>
                                        <a href="{{ route('admin.tugas.edit', $tugas->id) }}" style="font-size: 12px; color: #555; text-decoration: underline;">
                                            Edit
                                        </a>
                                        |
                                        <form action="{{ route('admin.tugas.destroy', $tugas->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus tugas skema ini beserta seluruh pengumpulan peserta?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: none; border: none; color: #dc3545; font-size: 12px; cursor: pointer; padding: 0; text-decoration: underline;">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 40px; color: #777;">
            Belum ada skema sertifikasi. Silakan tambahkan data skema terlebih dahulu.
        </div>
    @endforelse
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Tugas Sertifikasi')
@section('page-title', 'Tugas & Uji Kompetensi Saya')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">

    {{-- Banner Status Kelulusan / Kompetensi --}}
    <div style="
        padding: 22px 25px;
        border-radius: 10px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        @if($peserta->status_kompetensi === 'Kompeten')
            background: linear-gradient(135deg, #198754, #146c43); color: white;
        @elseif($peserta->status_kompetensi === 'Belum Kompeten')
            background: linear-gradient(135deg, #dc3545, #b02a37); color: white;
        @elseif($peserta->status_kompetensi === 'Menunggu Penilaian')
            background: linear-gradient(135deg, #ffc107, #d39e00); color: #212529;
        @else
            background: linear-gradient(135deg, #6c757d, #495057); color: white;
        @endif
    ">
        <div>
            <span style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9;">Status Kelulusan Sertifikasi:</span>
            <h1 style="margin: 5px 0 0; font-size: 26px;">
                @if($peserta->status_kompetensi === 'Kompeten')
                    ✓ KOMPETEN (LULUS PENUH)
                @elseif($peserta->status_kompetensi === 'Belum Kompeten')
                    ✗ BELUM KOMPETEN (PERLU REVISI)
                @elseif($peserta->status_kompetensi === 'Menunggu Penilaian')
                    ⏳ SEMUA TUGAS KUMPUL (MENUNGGU PENILAIAN)
                @elseif($peserta->tugas_selesai_count > 0 && $peserta->tugas_selesai_count < $peserta->total_tugas)
                    ⏳ DALAM PROSES ({{ $peserta->tugas_selesai_count }}/{{ $peserta->total_tugas }} SELESAI)
                @elseif($peserta->status_kompetensi === 'Menunggu Pengumpulan')
                    📝 BELUM SEMUA DIKERJAKAN
                @else
                    BELUM ADA TUGAS DIBERIKAN
                @endif
            </h1>
        </div>
        <div style="font-size: 14px; text-align: right;">
            Skema: <strong>{{ $peserta->skema->nama_skema ?? '-' }}</strong><br>
            <small>Kode: {{ $peserta->skema->kode_skema ?? '-' }}</small>
        </div>
    </div>

    {{-- KARTU METRIK INDIKATOR TUGAS --}}
    <div class="card" style="margin-bottom: 25px; border-left: 5px solid #800020;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div>
                <strong style="color: #800020; font-size: 18px;">
                    Progress Tugas: {{ $peserta->tugas_selesai_count }} dari {{ $peserta->total_tugas }} Selesai ({{ $peserta->persentase_selesai }}%)
                </strong>
                <p style="margin: 4px 0 0; font-size: 13px; color: #666;">
                    *Anda tidak langsung dinyatakan kompeten jika tugas belum tuntas semua. Selesaikan seluruh tugas untuk dinilai oleh Asesor.
                </p>
            </div>
            <div style="text-align: right;">
                <span style="font-size: 13px; font-weight: bold; color: #198754;">
                    ✓ {{ $peserta->tugas_kompeten_count }} Kompeten
                </span>
                <span style="color: #bbb; margin: 0 6px;">|</span>
                <span style="font-size: 13px; font-weight: bold; color: #dc3545;">
                    ✗ {{ $peserta->tugas_belum_kompeten_count }} Belum Kompeten
                </span>
            </div>
        </div>

        {{-- Visual Progress Bar --}}
        <div style="background: #e9ecef; border-radius: 10px; height: 16px; overflow: hidden; margin: 14px 0 16px;">
            <div style="background: linear-gradient(90deg, #800020, #198754); height: 100%; width: {{ $peserta->persentase_selesai }}%; border-radius: 10px; transition: width 0.4s ease;"></div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 10px;">
            <div style="background: #f8f9fa; padding: 10px; border-radius: 6px; text-align: center; border: 1px solid #eee;">
                <span style="font-size: 11px; color: #777; display: block;">Total Tugas Skema</span>
                <strong style="font-size: 16px; color: #333;">{{ $peserta->total_tugas }}</strong>
            </div>
            <div style="background: #f0f7ff; padding: 10px; border-radius: 6px; text-align: center; border: 1px solid #d0e3ff;">
                <span style="font-size: 11px; color: #0d6efd; display: block;">Terkumpul</span>
                <strong style="font-size: 16px; color: #0d6efd;">{{ $peserta->tugas_selesai_count }}</strong>
            </div>
            <div style="background: #f0fdf4; padding: 10px; border-radius: 6px; text-align: center; border: 1px solid #bbf7d0;">
                <span style="font-size: 11px; color: #16a34a; display: block;">Dinilai Kompeten</span>
                <strong style="font-size: 16px; color: #16a34a;">{{ $peserta->tugas_kompeten_count }}</strong>
            </div>
            <div style="background: #fff5f5; padding: 10px; border-radius: 6px; text-align: center; border: 1px solid #fed7d7;">
                <span style="font-size: 11px; color: #e53e3e; display: block;">Belum Selesai</span>
                <strong style="font-size: 16px; color: #e53e3e;">{{ max(0, $peserta->total_tugas - $peserta->tugas_selesai_count) }}</strong>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Daftar Tugas --}}
    @forelse($daftarTugas as $tugas)
        @php
            $pengumpulan = $tugas->pengumpulan->first();
        @endphp
        <div class="card" style="margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid #eee; padding-bottom: 12px; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;">
                <div>
                    <h2 style="margin: 0; color: #800020; font-size: 20px;">{{ $tugas->judul }}</h2>
                    <small style="color: #777;">Diberikan pada: {{ $tugas->created_at->translatedFormat('d F Y H:i') }}</small>
                </div>
                <div>
                    @if($pengumpulan && $pengumpulan->status === 'Kompeten')
                        <span style="padding: 6px 14px; background: #d1e7dd; color: #0f5132; border-radius: 20px; font-weight: bold; font-size: 13px;">
                            ✓ KOMPETEN
                        </span>
                    @elseif($pengumpulan && $pengumpulan->status === 'Belum Kompeten')
                        <span style="padding: 6px 14px; background: #f8d7da; color: #842029; border-radius: 20px; font-weight: bold; font-size: 13px;">
                            ✗ BELUM KOMPETEN
                        </span>
                    @elseif($pengumpulan && $pengumpulan->status === 'Menunggu Penilaian')
                        <span style="padding: 6px 14px; background: #fff3cd; color: #664d03; border-radius: 20px; font-weight: bold; font-size: 13px;">
                            ⏳ Menunggu Penilaian Admin
                        </span>
                    @else
                        <span style="padding: 6px 14px; background: #e2e3e5; color: #41464b; border-radius: 20px; font-weight: bold; font-size: 13px;">
                            Belum Mengumpulkan
                        </span>
                    @endif
                </div>
            </div>

            {{-- Instruksi Tugas --}}
            <div style="background: #faf7f7; padding: 15px; border-radius: 6px; border: 1px solid #ebdada; margin-bottom: 20px;">
                <strong style="color: #800020; font-size: 14px; display: block; margin-bottom: 6px;">Deskripsi / Petunjuk Pengerjaan:</strong>
                <p style="margin: 0; line-height: 1.6; white-space: pre-wrap; font-size: 14px;">{{ $tugas->deskripsi }}</p>
            </div>

            {{-- Jika ada Catatan Admin --}}
            @if($pengumpulan?->catatan_admin)
                <div style="background: #fff8e1; border-left: 4px solid #ffb300; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px;">
                    <strong style="color: #b78103; font-size: 13px;">Catatan dari Asesor/Admin:</strong>
                    <p style="margin: 5px 0 0; color: #444; line-height: 1.5; font-size: 14px;">{{ $pengumpulan->catatan_admin }}</p>
                </div>
            @endif

            {{-- Jawaban Terkumpul Saat Ini --}}
            @if($pengumpulan && ($pengumpulan->link_jawaban || $pengumpulan->file_jawaban))
                <div style="border: 1px solid #e0e0e0; padding: 15px; border-radius: 6px; margin-bottom: 20px; background: #fff;">
                    <strong style="display: block; margin-bottom: 8px; font-size: 14px;">Jawaban yang kamu kumpulkan:</strong>
                    @if($pengumpulan->link_jawaban)
                        <div style="margin-bottom: 8px;">
                            🔗 <strong>Tautan:</strong>
                            <a href="{{ $pengumpulan->link_jawaban }}" target="_blank" style="color: #0b5ed7; text-decoration: underline; word-break: break-all;">
                                {{ $pengumpulan->link_jawaban }}
                            </a>
                        </div>
                    @endif
                    @if($pengumpulan->file_jawaban)
                        <div>
                            🖼️ <strong>Lampiran Gambar:</strong><br>
                            <a href="{{ asset($pengumpulan->file_jawaban) }}" target="_blank">
                                <img src="{{ asset($pengumpulan->file_jawaban) }}" alt="Preview Tugas" style="max-width: 250px; max-height: 180px; margin-top: 6px; border-radius: 4px; border: 1px solid #ccc;">
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Formulir Pengumpulan Tugas (Link atau Gambar) --}}
            <div style="background: #fdfdfd; border: 1px solid #d9d9d9; padding: 20px; border-radius: 8px;">
                <h3 style="margin-top: 0; color: #800020; font-size: 16px;">
                    {{ ($pengumpulan && ($pengumpulan->link_jawaban || $pengumpulan->file_jawaban)) ? 'Perbarui Jawaban Tugas' : 'Form Pengumpulan Tugas' }}
                </h3>
                <p style="font-size: 13px; color: #666; margin-bottom: 15px;">
                    Kamu dapat menyertakan link project (misal: GitHub / Website demo) dan/atau mengunggah screenshot gambar hasil tugas.
                </p>

                <form action="{{ route('peserta.tugas.submit', $tugas->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Input Link --}}
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-weight: bold; margin-bottom: 6px; font-size: 13px;">
                            Tautan Link (Website / GitHub / Google Drive):
                        </label>
                        <input
                            type="url"
                            name="link_jawaban"
                            value="{{ old('link_jawaban', $pengumpulan?->link_jawaban) }}"
                            placeholder="https://github.com/username/project-jwd"
                            style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                        >
                    </div>

                    {{-- Input Gambar --}}
                    <div style="margin-bottom: 18px;">
                        <label style="display: block; font-weight: bold; margin-bottom: 6px; font-size: 13px;">
                            Unggah Gambar Hasil Pengerjaan (Screenshot / Bukti):
                        </label>
                        <input
                            type="file"
                            name="gambar_jawaban"
                            accept="image/*"
                            style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; box-sizing: border-box; background: white;"
                        >
                        <small style="color: #777; display: block; margin-top: 4px;">
                            Format gambar: JPG, PNG, WEBP. Maksimal ukuran: 3 MB.
                        </small>
                    </div>

                    <button type="submit" style="padding: 10px 22px; background: #800020; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
                        Kumpulkan Tugas
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="card" style="text-align: center; padding: 40px;">
            <h3 style="color: #666; margin-top: 0;">Belum Ada Tugas Diberikan</h3>
            <p style="color: #888;">Asesor atau Admin belum mengirimkan penugasan sertifikasi untuk Anda. Silakan tunggu informasi lebih lanjut.</p>
        </div>
    @endforelse

</div>
@endsection

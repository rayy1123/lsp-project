
@extends('layouts.admin')

@section('title', 'Dashboard Peserta')
@section('page-title', 'Dashboard Peserta')

@section('css')
<style>
    .welcome {
        background: linear-gradient(135deg, #800000, #a52a2a);
        color: white;
        padding: 28px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 4px 12px rgba(128, 0, 0, 0.12);
    }

    .welcome h1 {
        margin: 0 0 10px;
        font-size: 25px;
    }

    .welcome p {
        margin: 0;
        line-height: 1.6;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .profile-card {
        background: white;
        padding: 24px;
        border-radius: 10px;
        border: 1px solid #eadada;
        box-shadow: 0 2px 8px rgba(128, 0, 0, 0.06);
        min-width: 0;
    }

    .profile-card h2 {
        color: #800000;
        font-size: 19px;
        margin: 0 0 20px;
        padding-bottom: 14px;
        border-bottom: 2px solid #f3e4e4;
    }

    .data-row {
        padding: 13px 0;
        border-bottom: 1px solid #f0e8e8;
    }

    .data-row:last-child {
        border-bottom: none;
    }

    .label {
        display: block;
        font-size: 12px;
        color: #777;
        margin-bottom: 6px;
    }

    .value {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #333;
        overflow-wrap: anywhere;
    }

    .empty-value {
        color: #999;
        font-weight: normal;
        font-style: italic;
    }

    @media (max-width: 800px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .welcome {
            padding: 22px;
        }

        .welcome h1 {
            font-size: 21px;
        }
    }
</style>
@endsection

@section('content')
<div class="welcome">
    <h1>Selamat datang, {{ $peserta->nama }}!</h1>
    <p>Selamat datang di Dashboard Peserta Sertifikasi JWD.
        Kamu dapat melihat informasi profil, memantau kemajuan pengerjaan tugas, dan skema sertifikasi yang sedang aktif.</p>
</div>

{{-- METRIK INDIKATOR PROGRESS TUGAS SERTIFIKASI --}}
<div class="profile-card" style="margin-bottom: 25px; border-left: 5px solid #800020;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div>
            <span style="font-size: 12px; text-transform: uppercase; color: #777; font-weight: bold; letter-spacing: 0.5px;">Indikator Penyelesaian Tugas</span>
            <h2 style="margin: 4px 0 0; color: #800020; font-size: 20px; border-bottom: none; padding-bottom: 0;">
                {{ $peserta->tugas_selesai_count }} dari {{ $peserta->total_tugas }} Tugas Selesai Dikerjakan ({{ $peserta->persentase_selesai }}%)
            </h2>
        </div>
        <div>
            @if($peserta->status_kompetensi === 'Kompeten')
                <span style="padding: 6px 14px; background: #d1e7dd; color: #0f5132; border-radius: 20px; font-weight: bold; font-size: 13px;">
                    ✓ RESMI KOMPETEN (LULUS PENUH)
                </span>
            @elseif($peserta->status_kompetensi === 'Belum Kompeten')
                <span style="padding: 6px 14px; background: #f8d7da; color: #842029; border-radius: 20px; font-weight: bold; font-size: 13px;">
                    ✗ BELUM KOMPETEN (ADA TUGAS PERLU REVISI)
                </span>
            @elseif($peserta->tugas_selesai_count > 0 && $peserta->tugas_selesai_count < $peserta->total_tugas)
                <span style="padding: 6px 14px; background: #cff4fc; color: #055160; border-radius: 20px; font-weight: bold; font-size: 13px;">
                    ⏳ BELUM LULUS (BARU SELESAI {{ $peserta->tugas_selesai_count }}/{{ $peserta->total_tugas }})
                </span>
            @elseif($peserta->total_tugas > 0 && $peserta->tugas_selesai_count === $peserta->total_tugas)
                <span style="padding: 6px 14px; background: #fff3cd; color: #664d03; border-radius: 20px; font-weight: bold; font-size: 13px;">
                    ⏳ SEMUA TUGAS KUMPUL (MENUNGGU VERIFIKASI ASESOR)
                </span>
            @else
                <span style="padding: 6px 14px; background: #e2e3e5; color: #41464b; border-radius: 20px; font-size: 13px;">
                    BELUM ADA TUGAS DIKUMPULKAN
                </span>
            @endif
        </div>
    </div>

    {{-- Progress Bar --}}
    <div style="background: #e9ecef; border-radius: 10px; height: 16px; overflow: hidden; margin: 15px 0 16px;">
        <div style="background: linear-gradient(90deg, #800020, #198754); height: 100%; width: {{ $peserta->persentase_selesai }}%; border-radius: 10px; transition: width 0.4s ease;"></div>
    </div>

    {{-- Stat Badges --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 12px; margin-top: 10px;">
        <div style="background: #f8f9fa; padding: 12px; border-radius: 6px; text-align: center; border: 1px solid #eee;">
            <span style="font-size: 12px; color: #666; display: block;">Total Tugas</span>
            <strong style="font-size: 18px; color: #333;">{{ $peserta->total_tugas }}</strong>
        </div>
        <div style="background: #f0f7ff; padding: 12px; border-radius: 6px; text-align: center; border: 1px solid #d0e3ff;">
            <span style="font-size: 12px; color: #0d6efd; display: block;">Selesai Kumpul</span>
            <strong style="font-size: 18px; color: #0d6efd;">{{ $peserta->tugas_selesai_count }}</strong>
        </div>
        <div style="background: #f0fdf4; padding: 12px; border-radius: 6px; text-align: center; border: 1px solid #bbf7d0;">
            <span style="font-size: 12px; color: #16a34a; display: block;">Dinilai Kompeten</span>
            <strong style="font-size: 18px; color: #16a34a;">{{ $peserta->tugas_kompeten_count }}</strong>
        </div>
        <div style="background: #fff5f5; padding: 12px; border-radius: 6px; text-align: center; border: 1px solid #fed7d7;">
            <span style="font-size: 12px; color: #e53e3e; display: block;">Tugas Tersisa</span>
            <strong style="font-size: 18px; color: #e53e3e;">{{ max(0, $peserta->total_tugas - $peserta->tugas_selesai_count) }}</strong>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <section class="profile-card">
        <h2>Profil Saya</h2>

        <div class="data-row">
            <span class="label">Nama Lengkap</span>
            <span class="value">{{ $peserta->nama }}</span>
        </div>

        <div class="data-row">
            <span class="label">Tempat, Tanggal Lahir</span>
            <span class="value">
                {{ $peserta->tempat_lahir ?? '-' }},
                {{ $peserta->tanggal_lahir ? \Carbon\Carbon::parse($peserta->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
            </span>
        </div>

        <div class="data-row">
            <span class="label">Jenis Kelamin</span>
            <span class="value">{{ $peserta->jenis_kelamin ?: 'Belum diisi' }}</span>
        </div>

        <div class="data-row">
            <span class="label">NIK</span>
            <span class="value">{{ $peserta->nik ?: 'Belum diisi' }}</span>
        </div>

        <div class="data-row">
            <span class="label">Email</span>
            <span class="value">{{ $peserta->email }}</span>
        </div>

        <div class="data-row">
            <span class="label">Nomor Handphone</span>
            <span class="value">{{ $peserta->no_hp ?: 'Belum diisi' }}</span>
        </div>

        <div class="data-row">
            <span class="label">Alamat</span>
            <span class="value">{{ $peserta->alamat ?: 'Belum diisi' }}</span>
        </div>
    </section>

    <section class="profile-card">
        <h2>Skema Sertifikasi Saya</h2>

        <div class="data-row">
            <span class="label">Kode Skema</span>
            <span class="value">
                {{ $peserta->skema?->kode_skema ?? 'Belum ada skema' }}
            </span>
        </div>

        <div class="data-row">
            <span class="label">Nama Skema</span>
            <span class="value">
                {{ $peserta->skema?->nama_skema ?? 'Belum ada skema' }}
            </span>
        </div>

        <div class="data-row">
            <span class="label">Deskripsi Skema</span>
            <span class="value" style="font-size: 13px; font-weight: normal; color: #555; line-height: 1.5;">
                {{ $peserta->skema?->deskripsi ?? 'Belum ada deskripsi skema.' }}
            </span>
        </div>

        <div class="data-row">
            <span class="label">Tanggal Pendaftaran</span>
            <span class="value">
                {{ $peserta->tanggal_daftar ?: 'Belum tersedia' }}
            </span>
        </div>

        <div class="data-row">
            <span class="label">Status Uji Kompetensi</span>
            <span class="value">
                @if($peserta->status_kompetensi === 'Kompeten')
                    <span style="color: #198754; font-weight: bold; background: #d1e7dd; padding: 4px 10px; border-radius: 4px; display: inline-block;">
                        ✓ KOMPETEN (LULUS PENUH)
                    </span>
                @elseif($peserta->status_kompetensi === 'Belum Kompeten')
                    <span style="color: #dc3545; font-weight: bold; background: #f8d7da; padding: 4px 10px; border-radius: 4px; display: inline-block;">
                        ✗ BELUM KOMPETEN (PERLU REVISI)
                    </span>
                @elseif($peserta->status_kompetensi === 'Menunggu Penilaian')
                    <span style="color: #856404; font-weight: bold; background: #fff3cd; padding: 4px 10px; border-radius: 4px; display: inline-block;">
                        ⏳ Menunggu Penilaian Asesor
                    </span>
                @elseif($peserta->tugas_selesai_count > 0 && $peserta->tugas_selesai_count < $peserta->total_tugas)
                    <span style="color: #055160; font-weight: bold; background: #cff4fc; padding: 4px 10px; border-radius: 4px; display: inline-block;">
                        ⏳ Dalam Proses ({{ $peserta->tugas_selesai_count }}/{{ $peserta->total_tugas }} Selesai)
                    </span>
                @elseif($peserta->status_kompetensi === 'Menunggu Pengumpulan')
                    <span style="color: #0c5460; font-weight: bold; background: #d1ecf1; padding: 4px 10px; border-radius: 4px; display: inline-block;">
                        📝 Tugas Belum Dikumpulkan
                    </span>
                @else
                    <span style="color: #6c757d; font-style: italic;">Belum Ada Tugas</span>
                @endif
            </span>
        </div>

        <div style="margin-top: 18px;">
            <a href="{{ route('peserta.tugas.index') }}" style="display: block; text-align: center; background: #800020; color: white; padding: 10px; border-radius: 6px; text-decoration: none; font-weight: bold;">
                Lihat & Kumpulkan Tugas Sertifikasi →
            </a>
        </div>
    </section>
</div>

{{-- Bagian Memilih Skema Baru --}}
<div class="profile-card" style="margin-top: 25px;">
    <h2>Pilih / Ganti Skema Sertifikasi</h2>
    <p style="color: #666; font-size: 14px; margin-top: -10px; margin-bottom: 20px;">
        Berikut adalah daftar skema sertifikasi yang tersedia. Anda dapat beralih ke skema baru sesuai kompetensi yang ingin Anda tempuh.
    </p>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 18px; margin-bottom: 25px;">
        @foreach($skemas as $s)
            @php
                $isAktif = ($peserta->skema_id == $s->id);
            @endphp
            <div style="border: 2px solid {{ $isAktif ? '#800020' : '#e0e0e0' }}; border-radius: 8px; padding: 18px; background: {{ $isAktif ? '#fff9f9' : '#fff' }}; display: flex; flex-direction: column; justify-content: space-between; position: relative;">
                @if($isAktif)
                    <span style="position: absolute; top: -11px; right: 14px; background: #800020; color: white; padding: 2px 10px; border-radius: 12px; font-size: 11px; font-weight: bold; letter-spacing: 0.5px;">
                        ★ SKEMA AKTIF ANDA
                    </span>
                @endif
                <div>
                    <span style="background: #e8d0d0; color: #800020; font-size: 12px; font-weight: bold; padding: 3px 8px; border-radius: 4px;">
                        {{ $s->kode_skema }}
                    </span>
                    <h3 style="margin: 10px 0 6px; font-size: 16px; color: #333;">
                        {{ $s->nama_skema }}
                    </h3>
                    <p style="font-size: 13px; color: #666; line-height: 1.5; margin: 0 0 15px;">
                        {{ $s->deskripsi ?? 'Tidak ada deskripsi' }}
                    </p>
                </div>

                @if($isAktif)
                    <div style="text-align: center; padding: 8px; background: #d1e7dd; color: #0f5132; border-radius: 6px; font-weight: bold; font-size: 13px;">
                        ✓ Sedang Ditempuh
                    </div>
                @else
                    <form action="{{ route('peserta.pilih.skema') }}" method="POST" onsubmit="return confirm('Yakin ingin beralih ke skema {{ $s->nama_skema }}?')">
                        @csrf
                        <input type="hidden" name="skema_id" value="{{ $s->id }}">
                        <button type="submit" style="width: 100%; padding: 8px 12px; background: #222; color: white; border: none; border-radius: 6px; font-weight: bold; font-size: 13px; cursor: pointer;">
                            Pilih Skema Ini
                        </button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
</div>
    </section>
</div>
@endsection
@extends('layouts.admin')

@section('title', 'Penilaian Tugas Skema')
@section('page-title', 'Penilaian Peserta Tugas Skema')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; border-bottom: 2px solid #f3e4e4; padding-bottom: 15px; flex-wrap: wrap; gap: 10px;">
        <div>
            <span style="background: #800020; color: white; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                {{ $tugas->skema->kode_skema }} - {{ $tugas->skema->nama_skema }}
            </span>
            <h2 style="margin: 8px 0 4px; color: #800020; font-size: 22px;">{{ $tugas->judul }}</h2>
            <p style="margin: 0; color: #666; font-size: 14px;">
                Daftar pengumpulan tugas dari semua peserta pada skema ini.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.tugas.index') }}" style="display: inline-block; padding: 9px 16px; background: #e0e0e0; color: #333; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 13px;">
                ← Kembali ke Daftar Tugas
            </a>
        </div>
    </div>

    {{-- Deskripsi Tugas --}}
    <div style="background: #faf7f7; border: 1px solid #eadada; padding: 14px 18px; border-radius: 6px; margin-bottom: 20px;">
        <strong style="color: #800020; font-size: 13px; display: block; margin-bottom: 4px;">Instruksi Tugas:</strong>
        <p style="margin: 0; font-size: 14px; line-height: 1.5; color: #444; white-space: pre-wrap;">{{ $tugas->deskripsi }}</p>
    </div>

    {{-- Tabel Peserta & Pengumpulan --}}
    <div style="overflow-x: auto;">
        <table width="100%" cellpadding="10" cellspacing="0" border="1" style="border-collapse: collapse; border-color: #ddd;">
            <thead style="background: #f7f1f1; text-align: left; font-size: 14px;">
                <tr>
                    <th width="4%">No</th>
                    <th>Nama Peserta</th>
                    <th>NIK</th>
                    <th>Jawaban (Link / Gambar)</th>
                    <th>Waktu Kumpul</th>
                    <th>Status Kelulusan</th>
                    <th width="14%">Aksi</th>
                </tr>
            </thead>
            <tbody style="font-size: 14px;">
                @forelse($pesertas as $peserta)
                    @php
                        $pengumpulan = $peserta->pengumpulanTugas->first();
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $peserta->nama }}</strong><br>
                            <small style="color: #666;">{{ $peserta->email }} | {{ $peserta->no_hp }}</small>
                        </td>
                        <td>{{ $peserta->nik }}</td>
                        <td>
                            @if($pengumpulan && ($pengumpulan->link_jawaban || $pengumpulan->file_jawaban))
                                @if($pengumpulan->link_jawaban)
                                    <div>
                                        🔗 <a href="{{ $pengumpulan->link_jawaban }}" target="_blank" style="color: #0b5ed7; text-decoration: underline; word-break: break-all;">
                                            Tautan Hasil
                                        </a>
                                    </div>
                                @endif
                                @if($pengumpulan->file_jawaban)
                                    <div style="margin-top: 4px;">
                                        🖼️ <a href="{{ asset($pengumpulan->file_jawaban) }}" target="_blank" style="color: #800020; font-weight: bold;">
                                            Lihat Gambar
                                        </a>
                                    </div>
                                @endif
                            @else
                                <span style="color: #999; font-style: italic;">Belum mengumpulkan</span>
                            @endif
                        </td>
                        <td>
                            @if($pengumpulan)
                                {{ $pengumpulan->updated_at->format('d/m/Y H:i') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($pengumpulan && $pengumpulan->status === 'Kompeten')
                                <span style="display: inline-block; padding: 4px 10px; background: #d1e7dd; color: #0f5132; border-radius: 20px; font-weight: bold; font-size: 12px;">
                                    ✓ KOMPETEN (TUGAS INI)
                                </span>
                            @elseif($pengumpulan && $pengumpulan->status === 'Belum Kompeten')
                                <span style="display: inline-block; padding: 4px 10px; background: #f8d7da; color: #842029; border-radius: 20px; font-weight: bold; font-size: 12px;">
                                    ✗ BELUM KOMPETEN
                                </span>
                            @elseif($pengumpulan && $pengumpulan->status === 'Menunggu Penilaian')
                                <span style="display: inline-block; padding: 4px 10px; background: #fff3cd; color: #664d03; border-radius: 20px; font-weight: bold; font-size: 12px;">
                                    ⏳ Perlu Dinilai
                                </span>
                            @else
                                <span style="display: inline-block; padding: 4px 10px; background: #e2e3e5; color: #41464b; border-radius: 20px; font-size: 12px;">
                                    Belum Kumpul
                                </span>
                            @endif

                            <div style="font-size: 11px; color: #555; margin-top: 4px; font-weight: bold;">
                                📊 Progress: {{ $peserta->tugas_selesai_count }}/{{ $peserta->total_tugas }} Tugas Selesai ({{ $peserta->tugas_kompeten_count }} Kompeten)
                            </div>

                            @if($pengumpulan?->catatan_admin)
                                <div style="font-size: 11px; color: #666; margin-top: 4px; font-style: italic;">
                                    "{{ Str::limit($pengumpulan->catatan_admin, 40) }}"
                                </div>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.tugas.reviewPeserta', ['tugas' => $tugas->id, 'peserta' => $peserta->id]) }}" style="display: inline-block; padding: 7px 14px; background: #800020; color: white; text-decoration: none; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                Review & Nilai →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 30px; color: #777;">
                            Belum ada peserta yang mendaftar pada skema sertifikasi ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

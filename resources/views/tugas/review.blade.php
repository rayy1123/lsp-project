@extends('layouts.admin')

@section('title', 'Review & Penilaian Tugas')
@section('page-title', 'Review Tugas & Penilaian Kompetensi')

@section('content')
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div style="border-bottom: 2px solid #f3e4e4; padding-bottom: 15px; margin-bottom: 20px;">
        <h2 style="margin: 0; color: #800020;">Review & Penilaian Tugas</h2>
        <p style="margin: 5px 0 0; color: #666;">Periksa hasil pengerjaan tugas peserta dan berikan status kelulusan (Kompeten / Belum Kompeten).</p>
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

    {{-- Detail Peserta & Tugas --}}
    <div style="background: #faf7f7; border: 1px solid #eadada; border-radius: 8px; padding: 20px; margin-bottom: 25px;">
        <h3 style="margin-top: 0; color: #800020; font-size: 16px;">Informasi Peserta & Tugas</h3>
        <table width="100%" cellpadding="6" style="font-size: 14px;">
            <tr>
                <td width="25%" style="color: #666;">Nama Peserta</td>
                <td><strong>{{ $tugas->peserta->nama }}</strong></td>
            </tr>
            <tr>
                <td style="color: #666;">NIK</td>
                <td>{{ $tugas->peserta->nik }}</td>
            </tr>
            <tr>
                <td style="color: #666;">Skema Sertifikasi</td>
                <td>{{ $tugas->peserta->skema->nama_skema ?? '-' }}</td>
            </tr>
            <tr>
                <td style="color: #666;">Judul Tugas</td>
                <td><strong>{{ $tugas->judul }}</strong></td>
            </tr>
            <tr>
                <td style="color: #666; vertical-align: top;">Instruksi Tugas</td>
                <td style="white-space: pre-wrap; line-height: 1.5;">{{ $tugas->deskripsi }}</td>
            </tr>
            <tr>
                <td style="color: #666;">Status Saat Ini</td>
                <td>
                    <strong>{{ $tugas->status }}</strong>
                </td>
            </tr>
        </table>
    </div>

    {{-- Jawaban / Bukti yang Dikumpulkan Peserta --}}
    <div style="border: 1px solid #eadada; border-radius: 8px; padding: 20px; margin-bottom: 25px;">
        <h3 style="margin-top: 0; color: #800020; font-size: 16px;">Hasil Pengerjaan dari Peserta</h3>

        @if($tugas->link_jawaban)
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 4px; font-size: 13px;">Tautan / Link Hasil Tugas:</label>
                <div style="background: #f0f4f8; padding: 10px 14px; border-radius: 6px; border: 1px solid #d0d7de;">
                    🔗 <a href="{{ $tugas->link_jawaban }}" target="_blank" style="color: #0969da; font-weight: bold; text-decoration: underline; word-break: break-all;">
                        {{ $tugas->link_jawaban }}
                    </a>
                </div>
            </div>
        @endif

        @if($tugas->file_jawaban)
            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 6px; font-size: 13px;">Lampiran Gambar / Bukti:</label>
                <div style="text-align: center; background: #fafafa; padding: 15px; border-radius: 8px; border: 1px dashed #ccc;">
                    <a href="{{ asset($tugas->file_jawaban) }}" target="_blank">
                        <img src="{{ asset($tugas->file_jawaban) }}" alt="Bukti Tugas" style="max-width: 100%; max-height: 400px; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    </a>
                    <p style="margin: 8px 0 0; font-size: 12px; color: #666;">Klik gambar untuk melihat ukuran penuh</p>
                </div>
            </div>
        @endif

        @if(!$tugas->link_jawaban && !$tugas->file_jawaban)
            <div style="color: #888; font-style: italic; padding: 10px 0;">
                Peserta belum mengumpulkan link maupun gambar tugas.
            </div>
        @endif
    </div>

    {{-- Form Keputusan Persetujuan / Penilaian Asesor --}}
    <form action="{{ route('admin.tugas.nilai', $tugas->id) }}" method="POST" style="background: #fff9f9; border: 2px solid #800020; border-radius: 8px; padding: 22px;">
        @csrf

        <h3 style="margin-top: 0; color: #800020; font-size: 17px;">Hak Persetujuan & Penilaian Admin (Asesor)</h3>

        {{-- Radio Status --}}
        <div style="margin-bottom: 18px;">
            <label style="display: block; font-weight: bold; margin-bottom: 8px;">Keputusan Penilaian: *</label>
            <div style="display: flex; gap: 25px; flex-wrap: wrap;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 600; color: #0f5132;">
                    <input type="radio" name="status" value="Kompeten" @checked(old('status', $tugas->status) === 'Kompeten') required style="width: 18px; height: 18px;">
                    <span style="background: #d1e7dd; padding: 6px 14px; border-radius: 20px;">✓ KOMPETEN (K)</span>
                </label>

                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 600; color: #842029;">
                    <input type="radio" name="status" value="Belum Kompeten" @checked(old('status', $tugas->status) === 'Belum Kompeten') required style="width: 18px; height: 18px;">
                    <span style="background: #f8d7da; padding: 6px 14px; border-radius: 20px;">✗ BELUM KOMPETEN (BK)</span>
                </label>
            </div>
        </div>

        {{-- Catatan / Feedback Admin --}}
        <div style="margin-bottom: 20px;">
            <label for="catatan_admin" style="display: block; font-weight: bold; margin-bottom: 6px;">Catatan / Masukan dari Admin (Opsional):</label>
            <textarea
                id="catatan_admin"
                name="catatan_admin"
                rows="4"
                placeholder="Berikan alasan penilaian atau catatan perbaikan jika peserta belum kompeten..."
                style="width: 100%; padding: 11px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; font-family: inherit; box-sizing: border-box;"
            >{{ old('catatan_admin', $tugas->catatan_admin) }}</textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.tugas.index') }}" style="padding: 10px 18px; background: #e0e0e0; color: #333; text-decoration: none; border-radius: 6px; font-weight: bold;">
                Batal
            </a>
            <button type="submit" style="padding: 10px 24px; background: #800020; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
                Simpan Penilaian
            </button>
        </div>
    </form>
</div>
@endsection

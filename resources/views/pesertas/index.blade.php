@extends('layouts.admin')

@section('title', 'Data Peserta')

@section('page-title', 'Data Peserta Sertifikasi')

@section('content')

<div class="card">

    <div style="
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    ">

        <div>
            <h2>Data Peserta Sertifikasi</h2>
            <p>Daftar peserta sertifikasi yang terdaftar.</p>
        </div>

        <a href="{{ route('pesertas.create') }}"
           style="
                background: #222;
                color: white;
                padding: 10px 15px;
                text-decoration: none;
                border-radius: 6px;
           ">
            + Tambah Peserta
        </a>

    </div>


    {{-- Form Search --}}
    <form action="{{ route('pesertas.index') }}" method="GET"
          style="
              display: flex;
              gap: 10px;
              margin-bottom: 20px;
          ">

        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari nama, NIK, atau email..."
            style="
                flex: 1;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 6px;
            "
        >

        <button type="submit"
                style="
                    padding: 10px 18px;
                    background: #222;
                    color: white;
                    border: none;
                    border-radius: 6px;
                    cursor: pointer;
                ">
            Cari
        </button>

    </form>


    {{-- Tabel Peserta --}}
    <table width="100%" cellpadding="10" cellspacing="0" border="1">

        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>JK</th>
                <th>NIK</th>
                <th>No. HP</th>
                <th>Skema Sertifikasi</th>
                <th>Status Kompetensi</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

            @forelse ($pesertas as $peserta)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>
                        <strong>{{ $peserta->nama }}</strong><br>
                        <small style="color: #666;">{{ $peserta->email }}</small>
                    </td>

                    <td>{{ $peserta->jenis_kelamin ?? '-' }}</td>

                    <td>{{ $peserta->nik }}</td>

                    <td>{{ $peserta->no_hp }}</td>

                    <td>
                        {{ $peserta->skema->nama_skema ?? '-' }}
                    </td>

                    <td>
                        @if($peserta->status_kompetensi === 'Kompeten')
                            <span style="display: inline-block; padding: 4px 8px; background: #d1e7dd; color: #0f5132; border-radius: 12px; font-weight: bold; font-size: 11px;">
                                ✓ KOMPETEN
                            </span>
                        @elseif($peserta->status_kompetensi === 'Belum Kompeten')
                            <span style="display: inline-block; padding: 4px 8px; background: #f8d7da; color: #842029; border-radius: 12px; font-weight: bold; font-size: 11px;">
                                ✗ BELUM KOMPETEN
                            </span>
                        @elseif($peserta->status_kompetensi === 'Menunggu Penilaian')
                            <span style="display: inline-block; padding: 4px 8px; background: #fff3cd; color: #664d03; border-radius: 12px; font-weight: bold; font-size: 11px;">
                                ⏳ Perlu Dinilai
                            </span>
                        @elseif($peserta->tugas_selesai_count > 0 && $peserta->tugas_selesai_count < $peserta->total_tugas)
                            <span style="display: inline-block; padding: 4px 8px; background: #cff4fc; color: #055160; border-radius: 12px; font-weight: bold; font-size: 11px;">
                                ⏳ Dalam Proses
                            </span>
                        @elseif($peserta->status_kompetensi === 'Menunggu Pengumpulan')
                            <span style="display: inline-block; padding: 4px 8px; background: #d1ecf1; color: #0c5460; border-radius: 12px; font-size: 11px;">
                                Menunggu Jawaban
                            </span>
                        @else
                            <span style="color: #888; font-size: 12px;">Belum Ditugaskan</span>
                        @endif

                        <div style="font-size: 11px; color: #555; margin-top: 3px; font-weight: bold;">
                            📊 {{ $peserta->tugas_selesai_count }}/{{ $peserta->total_tugas }} Tugas Selesai
                        </div>
                    </td>

                    <td>
                        <a href="{{ route('admin.tugas.create', ['peserta_id' => $peserta->id]) }}" style="color: #800020; font-weight: bold;">
                            + Tugas
                        </a>
                        |
                        <a href="{{ route('pesertas.show', $peserta->id) }}">
                            Detail
                        </a>
                        |
                        <a href="{{ route('pesertas.edit', $peserta->id) }}">
                            Edit
                        </a>
                        |
                        <form
                            action="{{ route('pesertas.destroy', $peserta->id) }}"
                            method="POST"
                            style="display: inline;"
                            onsubmit="return confirm('Yakin ingin menghapus peserta ini?')"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #dc3545; cursor: pointer; padding: 0; font: inherit;">
                                Hapus
                            </button>
                        </form>
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="8" style="text-align: center;">
                        Belum ada data peserta.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection
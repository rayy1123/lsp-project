@extends('layouts.admin')

@section('title', 'Data Skema')

@section('page-title', 'Data Skema Sertifikasi')

@section('content')

<div class="card">

    <div style="
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    ">

        <div>
            <h2>Data Skema Sertifikasi</h2>
            <p>Daftar skema sertifikasi yang tersedia.</p>
        </div>

        <a href="{{ route('skemas.create') }}"
           style="
                background: #222;
                color: white;
                padding: 10px 15px;
                text-decoration: none;
                border-radius: 6px;
           ">
            + Tambah Skema
        </a>

    </div>

    <table width="100%" cellpadding="10" cellspacing="0" border="1">

        <thead>
            <tr>
                <th>No</th>
                <th>Kode Skema</th>
                <th>Nama Skema</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

            @forelse ($skemas as $skema)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $skema->kode_skema }}</td>

                    <td>{{ $skema->nama_skema }}</td>

                    <td>{{ $skema->deskripsi ?? '-' }}</td>

                    <td>
                        <a href="{{ route('skemas.show', $skema->id) }}" class="btn btn-warning">
                            Detail
                        </a>
                        <a href="{{ route('skemas.edit', $skema->id) }}">
                            Edit
                        </a>

                        <form
                            action="{{ route('skemas.destroy', $skema->id) }}"
                            method="POST"
                            style="display: inline;"
                            onsubmit="return confirm('Yakin ingin menghapus skema ini?')"
                        >

                            @csrf
                            @method('DELETE')

                            <button type="submit">
                                Hapus
                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" style="text-align: center;">
                        Belum ada data skema.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection
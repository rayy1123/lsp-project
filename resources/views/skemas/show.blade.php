@extends('layouts.admin')

@section('title', 'Detail Skema')

@section('page-title', 'Detail Data Skema')

@section('content')

<div class="card">

    <div style="
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    ">

        <div>
            <h2>Detail Skema</h2>
            <p>Informasi lengkap data Skema.</p>
        </div>

        <a
            href="{{ route('skemas.index') }}"
            style="
                background: #ccc;
                color: #222;
                padding: 10px 15px;
                text-decoration: none;
                border-radius: 6px;
            "
        >
            Kembali
        </a>

    </div>


    <table width="100%" cellpadding="12" cellspacing="0" border="1">

        <tr>
            <th width="30%">Kode Skema</th>
            <td>{{ $skema->kode_skema }}</td>
        </tr>

        <tr>
            <th>Nama Skema</th>
            <td>{{ $skema->nama_skema }}</td>
        </tr>

        <tr>
            <th>Deskripsi</th>
            <td>{{ $skema->deskripsi }}</td>
        </tr>

    </table>

</div>

@endsection
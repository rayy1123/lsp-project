@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')

    <h1>Dashboard</h1>
    <p>Selamat datang di Aplikasi Pengelolaan Data Peserta Sertifikasi.</p>
    <div class="stats">
        <div class="stat-card">
            <h3>Total Peserta</h3>
            <h2>{{ $totalPeserta }}</h2>
            <p>Peserta sertifikasi</p>
        </div>
        <div class="stat-card">
            <h3>Total Skema</h3>
            <h2>{{ $totalSkema }}</h2>
            <p>Skema sertifikasi</p>
        </div>
    </div>

@endsection
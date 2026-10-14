<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sertifikasi JWD')</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f8f3f3;
            color: #333;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 240px;
            background: #800000;
            color: white;
            padding: 25px 15px;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.08);
        }

        .sidebar h2 {
            margin: 0 0 30px;
            text-align: center;
            font-size: 20px;
            letter-spacing: 0.5px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 6px;
            transition: 0.2s;
        }

        .sidebar a:hover {
            background: #5f0000;
        }

        .content {
            flex: 1;
        }

        /* NAVBAR */
        .navbar {
            background: white;
            padding: 18px 25px;
            border-bottom: 1px solid #e5d6d6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar strong {
            color: #800000;
            font-size: 18px;
        }

        .navbar span {
            color: #555;
        }

        .main {
            padding: 25px;
        }

        /* CARD */
        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            border: 1px solid #eadada;
            box-shadow: 0 2px 8px rgba(128, 0, 0, 0.06);
        }

        /* DASHBOARD */
        .stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            border-left: 5px solid #800000;
            box-shadow: 0 2px 8px rgba(128, 0, 0, 0.06);
        }

        .stat-card h3 {
            margin-top: 0;
            color: #800000;
        }

        /* BUTTON */
        button {
            cursor: pointer;
        }

        .logout-btn {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 6px;
            background: #5f0000;
            color: white;
            margin-top: 20px;
            transition: 0.2s;
        }

        .logout-btn:hover {
            background: #430000;
        }

        /* LINK / BUTTON MAROON */
        a.btn-primary,
        .btn-primary {
            background: #800000;
            color: white;
            border: none;
        }

        a.btn-primary:hover,
        .btn-primary:hover {
            background: #5f0000;
        }

        /* SUCCESS MESSAGE */
        .alert-success {
            background: #e8f5e9;
            color: #256029;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        /* ERROR MESSAGE */
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
    </style>
    @yield('css')
</head>
<body>
    <div class="wrapper">
        <aside class="sidebar">
            <h2>APLIKASI SERTIFIKASI</h2>
            <a href="{{ route('dashboard') }}">
                 Dashboard
            </a>

            @if (auth()->user()->role === 'admin')
                <a href="{{ route('pesertas.index') }}">
                    Data Peserta
                </a>
                <a href="{{ route('skemas.index') }}">
                    Data Skema
                </a>
                <a href="{{ route('admin.tugas.index') }}">
                    Tugas & Penilaian
                </a>
            @else
                <a href="{{ route('peserta.tugas.index') }}">
                    Tugas Sertifikasi
                </a>
                <a href="{{ route('peserta.profil.edit') }}">
                    Data Peserta Saya
                </a>
            @endif

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="logout-btn" type="submit">
                    Logout
                </button>
            </form>
        </aside>
        <div class="content">
            <div class="navbar">
                <strong>@yield('page-title', 'Dashboard')</strong>
                <span>
                    {{ auth()->user()->name }}
                </span>
            </div>
            <main class="main">
                @if(session('success'))
                    <div class="alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')

            </main>
        </div>
    </div>
</body>

</html>

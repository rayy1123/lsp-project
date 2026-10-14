
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Sertifikasi JWD</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: #f5f6fa;
            color: #333;
        }

        nav {
            padding: 24px 8%;
            background: white;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
            color: #800020;
        }

        .hero {
            min-height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 30px 20px;
        }

        .hero h1 {
            font-size: 42px;
            margin-bottom: 20px;
            color: #800020;
        }

        .hero p {
            max-width: 600px;
            line-height: 1.8;
            margin-bottom: 30px;
            color: #666;
        }

        .buttons {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 12px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-login {
            background: #800020;
            color: white;
        }

        .btn-daftar {
            border: 2px solid #800020;
            color: #800020;
        }

        .btn:hover {
            opacity: 0.8;
        }

        @media (max-width: 600px) {
            .hero h1 {
                font-size: 30px;
            }

            .buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

    <nav>
        <div class="logo">APLIKASI SERTIFIKASI</div>
    </nav>

    <main class="hero">
        @if (session('success'))
            <div style="
                background: #e8f5e9;
                color: #256029;
                border: 1px solid #c8e6c9;
                padding: 14px 24px;
                border-radius: 8px;
                margin-bottom: 25px;
                font-weight: 600;
                max-width: 600px;
                width: 100%;
                text-align: center;
                box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            ">
                {{ session('success') }}
            </div>
        @endif

        <h1>Selamat Datang!</h1>

        <p>
            Selamat datang di Aplikasi Informasi Sertifikasi JWD.
            Temukan informasi skema sertifikasi dan kelola
            pendaftaran peserta dengan mudah melalui website ini.
        </p>

        <div class="buttons">
            <a href="{{ route('login') }}" class="btn btn-login">
                Login
            </a>

            <a href="{{ route('register') }}" class="btn btn-daftar">
                Daftar
            </a>
        </div>
    </main>

</body>
</html>

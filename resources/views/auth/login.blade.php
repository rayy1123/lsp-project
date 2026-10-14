<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sertifikasi JWD</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-box {
            width: 380px;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #800020;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 11px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input:focus {
            outline: none;
            border-color: #800020;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            background: #800020;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #600018;
        }

        .error {
            background: #ffe5e5;
            color: #c00;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .footer a {
            color: #800020;
            font-weight: bold;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="login-box">

    <h2>Login Aplikasi</h2>

    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login.process') }}" method="POST">
        @csrf

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>

        <label>Password</label>
        <div style="position: relative; margin-bottom: 18px;">
            <input type="password" id="login-password" name="password" required style="margin-bottom: 0; padding-right: 42px;">
            <button type="button" onclick="togglePassword('login-password', this)" title="Lihat password" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); width: auto; background: none; border: none; color: #666; cursor: pointer; font-size: 17px; padding: 4px;">
                👁️
            </button>
        </div>

        <button type="submit">Login</button>
    </form>

    <div class="footer">
        Belum punya akun peserta?
        <a href="{{ route('register') }}">Daftar di sini</a>
    </div>

</div>

<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        if (input.type === 'password') {
            input.type = 'text';
            btn.textContent = '🙈';
            btn.title = 'Sembunyikan password';
        } else {
            input.type = 'password';
            btn.textContent = '👁️';
            btn.title = 'Lihat password';
        }
    }
</script>

</body>
</html>
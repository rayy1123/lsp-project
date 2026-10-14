<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Peserta Sertifikasi</title>

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
            padding: 30px 15px;
        }

        .register-box {
            width: 100%;
            max-width: 580px;
            background: white;
            padding: 35px 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 8px;
            color: #800020;
        }

        .subtitle {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            font-size: 13px;
            color: #333;
        }

        .required {
            color: #c00;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #800020;
            box-shadow: 0 0 0 2px rgba(128, 0, 32, 0.12);
        }

        textarea {
            resize: vertical;
            min-height: 70px;
        }

        .row {
            display: flex;
            gap: 15px;
        }

        .col {
            flex: 1;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            background: #800020;
            color: white;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.2s;
        }

        button:hover {
            background: #600018;
        }

        .error-box {
            background: #ffe5e5;
            color: #c00;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 13px;
            border: 1px solid #ffcccc;
        }

        .error-box ul {
            margin: 0;
            padding-left: 20px;
        }

        .field-error {
            color: #c00;
            font-size: 12px;
            margin-top: -10px;
            margin-bottom: 12px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }

        .footer a {
            color: #800020;
            font-weight: bold;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .hint {
            font-size: 11px;
            color: #777;
            margin-top: -12px;
            margin-bottom: 12px;
            display: block;
        }

        @media (max-width: 600px) {
            .row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>

<body>

<div class="register-box">

    <h2>Daftar Peserta Sertifikasi</h2>
    <p class="subtitle">Lengkapi formulir pendaftaran untuk membuat akun</p>

    @if ($errors->any())
        <div class="error-box">
            <strong>Terjadi kesalahan:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register.process') }}" method="POST">
        @csrf

        {{-- Nama Lengkap (Maks 50 karakter, tidak boleh angka, boleh kutip) --}}
        <div>
            <label for="name">Nama Lengkap <span class="required">*</span></label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                maxlength="50"
                placeholder="Contoh: Sayna D'Angelo / Ahmad Asy'ari"
                required
            >
            <span class="hint">Maksimal 50 karakter, huruf/spasi/tanda kutip, tanpa angka.</span>
            @error('name')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- NIK (Tepat 16 Digit) & No HP (Maks 13 Digit) --}}
        <div class="row">
            <div class="col">
                <label for="nik">NIK (16 Digit) <span class="required">*</span></label>
                <input
                    type="text"
                    id="nik"
                    name="nik"
                    value="{{ old('nik') }}"
                    maxlength="16"
                    pattern="[0-9]{16}"
                    placeholder="16 digit angka KTP"
                    required
                >
                <span class="hint">Batas tepat 16 karakter angka.</span>
                @error('nik')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="col">
                <label for="no_hp">No. Handphone <span class="required">*</span></label>
                <input
                    type="tel"
                    id="no_hp"
                    name="no_hp"
                    value="{{ old('no_hp') }}"
                    maxlength="13"
                    placeholder="Contoh: 081234567890"
                    required
                >
                <span class="hint">Maksimal 13 karakter.</span>
                @error('no_hp')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Tempat & Tanggal Lahir --}}
        <div class="row">
            <div class="col">
                <label for="tempat_lahir">Tempat Lahir <span class="required">*</span></label>
                <input
                    type="text"
                    id="tempat_lahir"
                    name="tempat_lahir"
                    value="{{ old('tempat_lahir') }}"
                    placeholder="Kota kelahiran"
                    required
                >
                @error('tempat_lahir')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="col">
                <label for="tanggal_lahir">Tanggal Lahir <span class="required">*</span></label>
                <input
                    type="date"
                    id="tanggal_lahir"
                    name="tanggal_lahir"
                    value="{{ old('tanggal_lahir') }}"
                    required
                >
                @error('tanggal_lahir')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Jenis Kelamin & Skema Sertifikasi --}}
        <div class="row">
            <div class="col">
                <label for="jenis_kelamin">Jenis Kelamin <span class="required">*</span></label>
                <select id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="col">
                <label for="skema_id">Skema Sertifikasi <span class="required">*</span></label>
                <select id="skema_id" name="skema_id" required>
                    <option value="">-- Pilih Skema --</option>
                    @foreach ($skemas as $skema)
                        <option value="{{ $skema->id }}" {{ old('skema_id') == $skema->id ? 'selected' : '' }}>
                            {{ $skema->kode_skema }} - {{ $skema->nama_skema }}
                        </option>
                    @endforeach
                </select>
                @error('skema_id')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Alamat --}}
        <div>
            <label for="alamat">Alamat Lengkap <span class="required">*</span></label>
            <textarea
                id="alamat"
                name="alamat"
                rows="2"
                placeholder="Masukkan alamat domisili lengkap"
                required
            >{{ old('alamat') }}</textarea>
            @error('alamat')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email">Alamat Email <span class="required">*</span></label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="nama@email.com"
                required
            >
            @error('email')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password & Konfirmasi --}}
        <div class="row">
            <div class="col">
                <label for="password">Password <span class="required">*</span></label>
                <div style="position: relative;">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Minimal 6 karakter"
                        minlength="6"
                        required
                        style="padding-right: 42px;"
                    >
                    <button type="button" onclick="togglePassword('password', this)" title="Lihat password" style="position: absolute; right: 8px; top: 12px; width: auto; background: none; border: none; color: #666; cursor: pointer; font-size: 16px; padding: 2px;">
                        👁️
                    </button>
                </div>
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="col">
                <label for="password_confirmation">Ulangi Password <span class="required">*</span></label>
                <div style="position: relative;">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Ketik ulang password"
                        minlength="6"
                        required
                        style="padding-right: 42px;"
                    >
                    <button type="button" onclick="togglePassword('password_confirmation', this)" title="Lihat password" style="position: absolute; right: 8px; top: 12px; width: auto; background: none; border: none; color: #666; cursor: pointer; font-size: 16px; padding: 2px;">
                        👁️
                    </button>
                </div>
            </div>
        </div>

        <button type="submit">Daftar Sekarang</button>
    </form>

    <div class="footer">
        Sudah memiliki akun?
        <a href="{{ route('login') }}">Login di sini</a>
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

</div>

</body>
</html>

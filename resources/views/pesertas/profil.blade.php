<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Profil Peserta</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f4f6f8;
            font-family: Arial, sans-serif;
        }

        .card {
            width: 100%;
            max-width: 480px;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            color: #800020;
        }

        .info {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 22px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
            font-size: 14px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 11px;
            margin-bottom: 17px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font: inherit;
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
            margin-bottom: 16px;
            padding: 12px;
            color: #b00020;
            background: #ffe5e5;
            border-radius: 6px;
        }
    </style>
</head>

<body>

    <div class="card">
        <h2>Lengkapi Profil</h2>

        <p class="info">
            Halo, {{ $user->name }}! Lengkapi data berikut agar
            profil peserta sertifikasi kamu dapat disimpan.
        </p>

        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('peserta.profil.store') }}" method="POST">
            @csrf

            <label for="nik">NIK</label>
            <input type="text" id="nik" name="nik" value="{{ old('nik') }}" maxlength="255" required>

            <label for="alamat">Alamat</label>
            <textarea id="alamat" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>

            <div class="mb-3">
                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="Laki-laki"
                        {{ old('jenis_kelamin', $peserta->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>
                        Laki-laki</option>
                    <option value="Perempuan"
                        {{ old('jenis_kelamin', $peserta->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>
                        Perempuan</option>
                </select>
            </div>

            <label for="skema_id">Pilih Skema Sertifikasi</label>
            <select id="skema_id" name="skema_id" required>
                <option value="">-- Pilih skema --</option>

                @foreach ($skemas as $skema)
                    <option value="{{ $skema->id }}" @selected(old('skema_id') == $skema->id)>
                        {{ $skema->nama_skema }}
                    </option>
                @endforeach
            </select>

            @if ($skemas->isEmpty())
                <p class="info">
                    Belum ada skema sertifikasi yang tersedia.
                    Silakan hubungi admin.
                </p>
            @endif

            <button type="submit" @disabled($skemas->isEmpty())>
                Simpan Profil
            </button>
        </form>
    </div>

</body>

</html>

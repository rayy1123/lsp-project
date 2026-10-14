# Dokumentasi Workflow Kerja & Penjelasan Sistem
## Aplikasi Sertifikasi Kompetensi Junior Web Developer (JWD)

Dokumen ini menjelaskan alur kerja (*business workflow*), hak akses pengguna, arsitektur basis data, serta mekanisme uji kompetensi dan penilaian pada aplikasi sertifikasi ini.

---

## 1. Ikhtisar Sistem

Aplikasi ini adalah platform digital untuk pengelolaan sertifikasi kompetensi (Lembaga Sertifikasi Profesi / LSP). Sistem ini memfasilitasi pendaftaran peserta, pemilihan skema sertifikasi, penugasan uji unjuk kerja berbasis skema, pengumpulan bukti tugas (tautan/gambar), serta penilaian bertahap oleh asesor/admin hingga peserta dinyatakan **Kompeten (Lulus)**.

---

## 2. Aktor & Peran Pengguna (*User Roles*)

| Peran (Role) | Kredensial Default | Hak Akses Utama |
|---|---|---|
| **Administrator / Asesor** | `admin@gmail.com` / `admin123` | - Mengelola data master Skema Sertifikasi (CRUD).<br>- Mengelola data seluruh Peserta Sertifikasi.<br>- Menerbitkan tugas unjuk kerja untuk suatu Skema.<br>- Memeriksa bukti jawaban tugas peserta (link & gambar).<br>- Memberikan keputusan kelulusan per tugas (*Kompeten* / *Belum Kompeten*) dan catatan evaluasi. |
| **Peserta Sertifikasi** | Pendaftaran mandiri / `peserta@gmail.com` (`admin123`) | - Mendaftar akun dan melengkapi biodata awal.<br>- Memilih skema sertifikasi yang ingin ditempuh.<br>- Memantau status dan indikator metrik tugas (misal: *2 dari 4 tugas selesai*).<br>- Mengunggah bukti tugas (link project / screenshot gambar).<br>- Memperbarui data diri, mengganti password akun, dan beralih ke skema baru. |

---

## 3. Diagram Alur Kerja (*Workflow Step-by-Step*)

```
[ PENGUNJUNG ]
      │
      ▼
1. Registrasi Akun Peserta ───► Simpan User & Peserta ───► Redirect ke Halaman Utama (Notif Sukses)
      │
      ▼
2. Login Akun ───────────────► Deteksi Role
                                   │
                ┌──────────────────┴──────────────────┐
                ▼                                     ▼
        [ Role: Admin ]                       [ Role: Peserta ]
                │                                     │
                ▼                                     ▼
3. Dashboard Admin                    4. Dashboard Peserta
   - Total Peserta & Skema               - Info Skema Aktif
   - Kelola Skema (CRUD)                 - Metrik Progress Tugas (X/Y)
   - Kelola Peserta (CRUD)               - Opsi Beralih Skema Baru
   - Terbitkan Tugas per Skema                        │
                │                                     ▼
                │                             5. Halaman Tugas Saya
                │                                - Baca petunjuk tugas
                │                                - Kirim link / upload gambar
                │                                     │
                │                                     ▼
                └───────────────► 6. Verifikasi & Penilaian Asesor
                                     - Review bukti & link tugas
                                     - Putusan: Kompeten / Belum Kompeten
                                     - Beri catatan masukan
                                                      │
                                                      ▼
                                  7. Perhitungan Kelulusan Bertahap
                                     (TIDAK LANGSUNG KOMPETEN)
                                     - Jika tugas belum tuntas -> "Dalam Proses"
                                     - Jika semua tugas Kompeten -> "KOMPETEN (LULUS)"
```

---

## 4. Penjelasan Rinci Setiap Tahap Alur Kerja

### Tahap 1: Pendaftaran Akun Peserta (*Registration*)
1. Calon peserta membuka halaman `/register`.
2. Peserta mengisi formulir dengan aturan validasi ketat:
   - **Nama Lengkap:** Maksimal 50 karakter, hanya huruf, spasi, dan tanda kutip (dilarang angka).
   - **NIK:** Tepat 16 digit angka KTP (`digits:16`, unik).
   - **Nomor Handphone:** Maksimal 13 digit angka (`max:13`).
   - **Tempat & Tanggal Lahir:** Wajib diisi (tanggal lahir harus di masa lalu).
   - **Jenis Kelamin:** Pilihan *Laki-laki* atau *Perempuan*.
   - **Alamat:** Teks domisili lengkap.
   - **Skema Sertifikasi:** Memilih skema kompetensi yang diminati dari database.
   - **Email & Password:** Email valid unik dan password minimal 6 karakter.
   - **Fitur Toggle Password:** Tersedia tombol mata (👁️ / 🙈) untuk melihat/menyembunyikan password.
3. Setelah klik **Daftar Sekarang**, sistem mengeksekusi *database transaction*:
   - Membuat akun login di tabel `users` (`role = 'peserta'`).
   - Membuat profil peserta di tabel `pesertas`.
4. Sistem mengarahkan kembali ke **Halaman Utama (`/`)** dengan pesan hijau: *"Akun berhasil dibuat! Silakan login untuk masuk ke dashboard."*

---

### Tahap 2: Autentikasi & Pengalihan Role (*Login & Direct Routing*)
1. Pengguna membuka halaman `/login` dan memasukkan email serta password (dilengkapi tombol intip password 👁️).
2. Sistem memverifikasi kredensial:
   - Jika akun adalah **Admin** (`admin@gmail.com` / `role: admin`), otomatis diarahkan ke **Dashboard Admin** (`/dashboard`).
   - Jika akun adalah **Peserta**, otomatis diarahkan ke **Dashboard Peserta** (`/dashboard`).
3. Sistem memiliki pengaman sesi (*session cleaner* & *middleware guard*): peserta biasa dilarang mengakses rute admin, dan admin tidak dialihkan ke formulir peserta.

---

### Tahap 3: Pembuatan Tugas Berbasis Skema (*Task Creation per Scheme*)
1. Admin membuka menu **Tugas & Penilaian** (`/admin/tugas`).
2. Admin membuat tugas dengan memilih **Skema Sertifikasi** (misal: *Junior Web Developer*).
3. **Efisiensi Sistem:** Admin cukup membuat tugas **1 kali per skema**, tidak perlu mengirim manual satu per satu ke setiap peserta.
4. Semua peserta yang memilih skema tersebut otomatis menerima tugas pada menu tugas mereka masing-masing.

---

### Tahap 4: Pengerjaan & Pengumpulan Tugas oleh Peserta (*Task Submission*)
1. Peserta membuka menu **Tugas Sertifikasi** (`/tugas-saya`).
2. Peserta melihat daftar tugas yang wajib dikerjakan sesuai skema sertifikasinya.
3. Peserta mengisi form pengumpulan tugas:
   - **Input Tautan (Link):** Link repository GitHub, demo website, Google Drive, dsb.
   - **Input Gambar Bukti:** Upload screenshot hasil pengerjaan (format JPG/PNG/WEBP, maks 3 MB) yang tersimpan di `public/uploads/tugas/`.
4. Saat tugas dikumpulkan:
   - Data tersimpan di tabel `pengumpulan_tugas`.
   - Status tugas berubah menjadi **Menunggu Penilaian**.

---

### Tahap 5: Pemeriksaan, Hak Penilaian & Persetujuan Asesor (*Assessment & Approval*)
1. Asesor/Admin membuka menu **Tugas & Penilaian** -> klik **Nilai Peserta** pada tugas yang bersangkutan.
2. Admin melihat tabel seluruh peserta pada skema tersebut:
   - Nama & NIK peserta.
   - Bukti tautan yang dapat diklik langsung.
   - Bukti gambar yang dapat diperbesar untuk diverifikasi.
   - Waktu pengumpulan tugas.
3. Admin mengklik **Review & Nilai** untuk memberikan keputusan:
   - Radio **✓ KOMPETEN (K)** atau **✗ BELUM KOMPETEN (BK)**.
   - Kolom catatan masukan/rekomendasi untuk peserta.
4. Hak persetujuan mutlak berada pada Admin/Asesor.

---

### Tahap 6: Perhitungan Kelulusan Bertahap & Metrik Indikator (*Gradual Competency Metric*)
1. **Aturan "Tidak Langsung Kompeten":**
   - Jika suatu skema memiliki misal 4 tugas, peserta yang baru menyelesaikan 1 atau 2 tugas **TIDAK langsung dinyatakan Kompeten**.
   - Status peserta akan berstatus **Dalam Proses (2/4 Selesai)**.
2. **Indikator Metrik Visual:**
   - Menampilkan teks: `2 dari 4 Tugas Selesai (50%)`.
   - Menampilkan *Visual Progress Bar* dengan gradasi warna maroon ke hijau.
   - Menampilkan 4 kotak ringkasan:
     - *Total Tugas Skema*
     - *Tugas Terkumpul*
     - *Tugas Dinilai Kompeten*
     - *Tugas Tersisa*
3. **Syarat Kelulusan Penuh:**
   - Peserta **hanya** dinyatakan berstatus **✓ KOMPETEN (LULUS)** jika **seluruh tugas** pada skemanya telah dinilai dan disetujui sebagai **Kompeten** oleh Asesor.
   - Jika ada tugas yang dinilai Belum Kompeten, status peserta menjadi **Belum Kompeten (Perlu Revisi)**.

---

### Tahap 7: Manajemen Mandiri Akun & Perpindahan Skema
1. **Pengaturan Biodata Diri (`/data-peserta-saya`):**
   - Peserta dapat memperbarui data kontak, NIK, alamat, dan tempat/tanggal lahir secara mandiri.
2. **Ubah Password Akun Terpisah:**
   - Terdapat kartu terpisah untuk mengganti password login akun peserta (minimal 6 karakter) dilengkapi tombol intip password (👁️).
   - Form terpisah menjamin tidak ada tabrakan *browser autofill*.
3. **Pilih / Beralih Skema Baru dari Dashboard:**
   - Di Dashboard Peserta (`/dashboard`), peserta dapat melihat informasi skema yang sedang aktif (`★ SKEMA AKTIF ANDA`).
   - Peserta dapat mengeksplorasi skema lain dan mengklik tombol **"Pilih Skema Ini"** untuk beralih kompetensi baru kapan saja.

---

## 5. Arsitektur Basis Data (*Database Schema*)

```
┌─────────────────┐       1:N       ┌────────────────────────┐
│     skemas      │ ─────────────── │         tugas          │
│ - id            │                 │ - id                   │
│ - kode_skema    │                 │ - skema_id (FK)        │
│ - nama_skema    │                 │ - judul                │
│ - deskripsi     │                 │ - deskripsi            │
└────────┬────────┘                 └───────────┬────────────┘
         │                                      │
         │ 1:N                                  │ 1:N
         ▼                                      ▼
┌─────────────────┐       1:N       ┌────────────────────────┐
│    pesertas     │ ─────────────── │   pengumpulan_tugas    │
│ - id            │                 │ - id                   │
│ - user_id (FK)  │                 │ - tugas_id (FK)        │
│ - skema_id (FK) │                 │ - peserta_id (FK)      │
│ - nama          │                 │ - link_jawaban         │
│ - nik           │                 │ - file_jawaban         │
│ - tempat_lahir  │                 │ - status               │
│ - tanggal_lahir │                 │ - catatan_admin        │
│ - no_hp         │                 └────────────────────────┘
│ - status_komp.  │
└────────┬────────┘
         │ 1:1
         ▼
┌─────────────────┐
│      users      │
│ - id            │
│ - name          │
│ - email         │
│ - password      │
│ - role          │
└─────────────────┘
```

---

## 6. Daftar Rute Aplikasi (*Route Endpoints*)

| HTTP Method | URI | Controller & Method | Deskripsi |
|---|---|---|---|
| `GET` | `/` | Closure (`welcome.blade.php`) | Halaman beranda penyambutan |
| `GET` | `/login` | `AuthController@showLogin` | Halaman formulir login |
| `POST` | `/login` | `AuthController@login` | Memproses login |
| `POST` | `/logout` | `AuthController@logout` | Memproses logout |
| `GET` | `/register` | `AuthController@showRegister` | Halaman formulir pendaftaran peserta |
| `POST` | `/register` | `AuthController@register` | Memproses registrasi peserta baru |
| `GET` | `/dashboard` | Closure | Pengarah dashboard sesuai role |
| `GET` | `/dashboard-peserta` | `PesertaProfileController@dashboard` | Dashboard khusus peserta |
| `POST` | `/pilih-skema-baru` | `PesertaProfileController@pilihSkemaBaru` | Memilih / beralih skema baru |
| `GET` | `/data-peserta-saya` | `PesertaProfileController@edit` | Halaman edit biodata & ganti password |
| `PUT` | `/data-peserta-saya` | `PesertaProfileController@update` | Simpan perubahan biodata |
| `PUT` | `/ubah-password-peserta`| `PesertaProfileController@updatePassword`| Simpan password baru akun |
| `GET` | `/tugas-saya` | `TugasController@indexPeserta` | Daftar tugas uji kompetensi peserta |
| `POST` | `/tugas-saya/{tugas}/kumpulkan` | `TugasController@submitPeserta` | Mengumpulkan link/gambar tugas |
| `RESOURCE` | `/skemas` | `SkemaController` | CRUD Master Skema (Khusus Admin) |
| `RESOURCE` | `/pesertas` | `PesertaController` | CRUD Data Peserta (Khusus Admin) |
| `GET` | `/admin/tugas` | `TugasController@indexAdmin` | Daftar tugas per skema (Khusus Admin) |
| `GET` | `/admin/tugas/tambah` | `TugasController@createAdmin` | Form buat tugas skema (Khusus Admin) |
| `POST` | `/admin/tugas` | `TugasController@storeAdmin` | Simpan tugas skema (Khusus Admin) |
| `GET` | `/admin/tugas/{tugas}/penilaian` | `TugasController@penilaianAdmin` | Daftar pengumpulan peserta (Admin) |
| `GET` | `/admin/tugas/{tugas}/peserta/{peserta}` | `TugasController@reviewPesertaAdmin` | Halaman review unjuk kerja (Admin) |
| `POST` | `/admin/tugas/{tugas}/peserta/{peserta}/nilai` | `TugasController@simpanNilaiAdmin` | Putusan Kompeten/Belum Kompeten |

---

## 7. Panduan Menjalankan Project di Laragon

1. Buka aplikasi **Laragon**, klik **Start All** (Apache & MySQL aktif).
2. Konfigurasi file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=apk_sertifikasi_sayna_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Sinkronisasi Database (Pilih salah satu):
   - **Cara 1 (Artisan):**
     ```bash
     php artisan migrate
     ```
   - **Cara 2 (phpMyAdmin):**
     Buka phpMyAdmin (`http://localhost/phpmyadmin`), klik menu **Import**, pilih file:
     `database/apk_sertifikasi_sayna_db.sql` lalu klik **Go**.
4. Bersihkan cache:
   ```bash
   php artisan optimize:clear
   ```
5. Akses website melalui:
   - URL Virtual Host: `http://apk_sertifikasi_jwd_sayna-main.test`
   - Atau jalankan `php artisan serve` di: `http://127.0.0.1:8000`

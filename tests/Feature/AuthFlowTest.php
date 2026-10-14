<?php

namespace Tests\Feature;

use App\Models\Skema;
use App\Models\User;
use App\Models\Peserta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Pastikan ada skema untuk tes
        Skema::firstOrCreate(
            ['kode_skema' => 'JWD-TEST'],
            [
                'nama_skema' => 'Junior Web Developer Test',
                'deskripsi' => 'Skema untuk testing',
            ]
        );
    }

    /**
     * Test registrasi berhasil dan redirect ke halaman welcome dengan flash message.
     */
    public function test_peserta_can_register_successfully(): void
    {
        $skema = Skema::first();

        $response = $this->post(route('register.process'), [
            'name' => "Sayna D'Angelo",
            'nik' => '3201012345678901',
            'no_hp' => '081234567890',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2001-05-12',
            'jenis_kelamin' => 'Perempuan',
            'alamat' => 'Jl. Cibaduyut No. 45',
            'skema_id' => $skema->id,
            'email' => 'sayna.test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('welcome'));
        $response->assertSessionHas('success', 'Akun berhasil dibuat! Silakan login untuk masuk ke dashboard.');

        // Cek database user dan peserta
        $this->assertDatabaseHas('users', [
            'email' => 'sayna.test@example.com',
            'name' => "Sayna D'Angelo",
            'role' => 'peserta',
        ]);

        $this->assertDatabaseHas('pesertas', [
            'email' => 'sayna.test@example.com',
            'nik' => '3201012345678901',
            'tempat_lahir' => 'Bandung',
            'jenis_kelamin' => 'Perempuan',
            'skema_id' => $skema->id,
        ]);
    }

    /**
     * Test validasi nama tidak boleh mengandung angka.
     */
    public function test_registration_fails_if_name_contains_numbers(): void
    {
        $skema = Skema::first();

        $response = $this->post(route('register.process'), [
            'name' => 'Sayna 123',
            'nik' => '3201012345678902',
            'no_hp' => '081234567890',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2001-05-12',
            'jenis_kelamin' => 'Perempuan',
            'alamat' => 'Jl. Test',
            'skema_id' => $skema->id,
            'email' => 'fail.name@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    /**
     * Test validasi NIK harus tepat 16 digit.
     */
    public function test_registration_fails_if_nik_is_not_16_digits(): void
    {
        $skema = Skema::first();

        // 15 digit
        $response1 = $this->post(route('register.process'), [
            'name' => 'Ahmad Asy\'ari',
            'nik' => '123456789012345',
            'no_hp' => '081234567890',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Test',
            'skema_id' => $skema->id,
            'email' => 'fail.nik1@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response1->assertSessionHasErrors(['nik']);

        // 17 digit
        $response2 = $this->post(route('register.process'), [
            'name' => 'Ahmad Asy\'ari',
            'nik' => '12345678901234567',
            'no_hp' => '081234567890',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Test',
            'skema_id' => $skema->id,
            'email' => 'fail.nik2@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response2->assertSessionHasErrors(['nik']);
    }

    /**
     * Test validasi No HP maksimal 13 karakter.
     */
    public function test_registration_fails_if_no_hp_exceeds_13_digits(): void
    {
        $skema = Skema::first();

        $response = $this->post(route('register.process'), [
            'name' => 'Budi Santoso',
            'nik' => '3201012345678903',
            'no_hp' => '0812345678901234', // 16 digit
            'tempat_lahir' => 'Semarang',
            'tanggal_lahir' => '1999-09-09',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Test',
            'skema_id' => $skema->id,
            'email' => 'fail.hp@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['no_hp']);
    }

    /**
     * Test login peserta langsung masuk ke dashboard peserta.
     */
    public function test_peserta_can_login_and_redirect_to_peserta_dashboard(): void
    {
        $skema = Skema::first();

        $user = User::create([
            'name' => 'Peserta Uji',
            'email' => 'peserta.uji@example.com',
            'password' => bcrypt('password123'),
            'role' => 'peserta',
        ]);

        Peserta::create([
            'user_id' => $user->id,
            'nama' => 'Peserta Uji',
            'nik' => '3201012345678999',
            'tempat_lahir' => 'Yogyakarta',
            'tanggal_lahir' => '2002-02-02',
            'jenis_kelamin' => 'Laki-laki',
            'email' => 'peserta.uji@example.com',
            'no_hp' => '081299998888',
            'alamat' => 'Jl. Malioboro',
            'skema_id' => $skema->id,
            'tanggal_daftar' => now()->toDateString(),
        ]);

        $response = $this->post(route('login.process'), [
            'email' => 'peserta.uji@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));

        $dashboardResponse = $this->actingAs($user)->get(route('dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Dashboard Peserta');
        $dashboardResponse->assertSee('Peserta Uji');
        $dashboardResponse->assertSee('3201012345678999');
        $dashboardResponse->assertSee('Yogyakarta');
    }

    /**
     * Test login admin langsung masuk ke dashboard admin.
     */
    public function test_admin_can_login_and_redirect_to_admin_dashboard(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
            ]
        );

        $response = $this->post(route('login.process'), [
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect(route('dashboard'));

        $dashboardResponse = $this->actingAs($admin)->get(route('dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Total Peserta');
        $dashboardResponse->assertSee('Total Skema');
    }

    /**
     * Test registrasi berhasil dengan password minimal 6 karakter.
     */
    public function test_registration_success_with_6_char_password(): void
    {
        $skema = Skema::first();

        $response = $this->post(route('register.process'), [
            'name' => 'Rina Wijaya',
            'nik' => '3201012345679999',
            'no_hp' => '081234567890',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'Perempuan',
            'alamat' => 'Jl. Mawar No. 1',
            'skema_id' => $skema->id,
            'email' => 'rina6char@example.com',
            'password' => '123456', // 6 karakter
            'password_confirmation' => '123456',
        ]);

        $response->assertRedirect(route('welcome'));
        $this->assertDatabaseHas('users', ['email' => 'rina6char@example.com']);
    }

    /**
     * Test alur tugas: Admin kirim tugas -> Peserta kirim link -> Admin nilai Kompeten.
     */
    public function test_task_flow_and_kompeten_approval(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Administrator', 'password' => bcrypt('admin123'), 'role' => 'admin']
        );

        $skema = Skema::first();

        $userPeserta = User::create([
            'name' => 'Budi Peserta',
            'email' => 'budi.peserta@example.com',
            'password' => bcrypt('123456'),
            'role' => 'peserta',
        ]);

        $peserta = Peserta::create([
            'user_id' => $userPeserta->id,
            'nama' => 'Budi Peserta',
            'nik' => '3201019999999999',
            'tempat_lahir' => 'Solo',
            'tanggal_lahir' => '1998-08-17',
            'jenis_kelamin' => 'Laki-laki',
            'email' => 'budi.peserta@example.com',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Slamet Riyadi',
            'skema_id' => $skema->id,
            'tanggal_daftar' => now()->toDateString(),
        ]);

        // 1. Admin buat tugas per skema
        $createTugasResponse = $this->actingAs($admin)->post(route('admin.tugas.store'), [
            'skema_id' => $skema->id,
            'judul' => 'Tugas Uji Kompetensi JWD',
            'deskripsi' => 'Kerjakan modul login dan kirim tautan repository github.',
        ]);

        $createTugasResponse->assertRedirect(route('admin.tugas.index'));
        $this->assertDatabaseHas('tugas', [
            'skema_id' => $skema->id,
            'judul' => 'Tugas Uji Kompetensi JWD',
        ]);

        $tugas = \App\Models\Tugas::where('skema_id', $skema->id)->first();

        // 2. Peserta mengumpulkan jawaban (link)
        $submitResponse = $this->actingAs($userPeserta)->post(route('peserta.tugas.submit', $tugas->id), [
            'link_jawaban' => 'https://github.com/budipeserta/jwd-final',
        ]);

        $submitResponse->assertRedirect(route('peserta.tugas.index'));
        $this->assertDatabaseHas('pengumpulan_tugas', [
            'tugas_id' => $tugas->id,
            'peserta_id' => $peserta->id,
            'link_jawaban' => 'https://github.com/budipeserta/jwd-final',
            'status' => 'Menunggu Penilaian',
        ]);

        // 3. Hak Admin: Memberikan persetujuan / penilaian Kompeten
        $nilaiResponse = $this->actingAs($admin)->post(route('admin.tugas.simpanNilai', ['tugas' => $tugas->id, 'peserta' => $peserta->id]), [
            'status' => 'Kompeten',
            'catatan_admin' => 'Hasil kode rapi dan memenuhi semua kriteria unjuk kerja.',
        ]);

        $nilaiResponse->assertRedirect(route('admin.tugas.penilaian', $tugas->id));
        $this->assertDatabaseHas('pengumpulan_tugas', [
            'tugas_id' => $tugas->id,
            'peserta_id' => $peserta->id,
            'status' => 'Kompeten',
            'catatan_admin' => 'Hasil kode rapi dan memenuhi semua kriteria unjuk kerja.',
        ]);

        // Status kompetensi peserta sinkron jadi Kompeten karena 1 dari 1 tugas selesai
        $this->assertDatabaseHas('pesertas', [
            'id' => $peserta->id,
            'status_kompetensi' => 'Kompeten',
        ]);
    }

    /**
     * Test peserta tidak langsung kompeten jika baru selesai sebagian tugas (misal 1 dari 2).
     */
    public function test_participant_is_not_immediately_kompeten_until_all_tasks_passed(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Administrator', 'password' => bcrypt('admin123'), 'role' => 'admin']
        );

        $skema = Skema::create([
            'kode_skema' => 'SKEMA-MULTI',
            'nama_skema' => 'Skema Multi Tugas',
            'deskripsi' => 'Skema dengan banyak tugas',
        ]);

        $tugas1 = \App\Models\Tugas::create([
            'skema_id' => $skema->id,
            'judul' => 'Tugas 1',
            'deskripsi' => 'Deskripsi 1',
        ]);

        $tugas2 = \App\Models\Tugas::create([
            'skema_id' => $skema->id,
            'judul' => 'Tugas 2',
            'deskripsi' => 'Deskripsi 2',
        ]);

        $user = User::create([
            'name' => 'Peserta Dua Tugas',
            'email' => 'duatugas@example.com',
            'password' => bcrypt('password123'),
            'role' => 'peserta',
        ]);

        $peserta = Peserta::create([
            'user_id' => $user->id,
            'nama' => 'Peserta Dua Tugas',
            'nik' => '3201016666666666',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'email' => 'duatugas@example.com',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Merdeka',
            'skema_id' => $skema->id,
            'tanggal_daftar' => now()->toDateString(),
        ]);

        // Peserta baru kumpulkan tugas 1
        $this->actingAs($user)->post(route('peserta.tugas.submit', $tugas1->id), [
            'link_jawaban' => 'https://github.com/test/tugas1',
        ]);

        // Admin nilai tugas 1 sebagai Kompeten
        $this->actingAs($admin)->post(route('admin.tugas.simpanNilai', ['tugas' => $tugas1->id, 'peserta' => $peserta->id]), [
            'status' => 'Kompeten',
        ]);

        $peserta->refresh();

        // Peserta TIDAK langsung kompeten secara keseluruhan (karena tugas 2 belum selesai)
        $this->assertEquals(2, $peserta->total_tugas);
        $this->assertEquals(1, $peserta->tugas_selesai_count);
        $this->assertEquals(1, $peserta->tugas_kompeten_count);
        $this->assertEquals(50, $peserta->persentase_selesai);
        $this->assertNotEquals('Kompeten', $peserta->status_kompetensi);
        $this->assertEquals('Dalam Proses', $peserta->status_kompetensi);

        // Peserta kumpulkan tugas 2
        $this->actingAs($user)->post(route('peserta.tugas.submit', $tugas2->id), [
            'link_jawaban' => 'https://github.com/test/tugas2',
        ]);

        // Admin nilai tugas 2 sebagai Kompeten
        $this->actingAs($admin)->post(route('admin.tugas.simpanNilai', ['tugas' => $tugas2->id, 'peserta' => $peserta->id]), [
            'status' => 'Kompeten',
        ]);

        $peserta->refresh();

        // Sekarang baru kompeten penuh (2 dari 2 selesai)
        $this->assertEquals(2, $peserta->tugas_kompeten_count);
        $this->assertEquals(100, $peserta->persentase_kompeten);
        $this->assertEquals('Kompeten', $peserta->status_kompetensi);
    }

    /**
     * Test peserta dapat mengubah biodata dan password akun.
     */
    public function test_peserta_can_update_biodata_and_password(): void
    {
        $skema = Skema::first();

        $user = User::create([
            'name' => 'Peserta Awal',
            'email' => 'peserta.edit@example.com',
            'password' => bcrypt('password123'),
            'role' => 'peserta',
        ]);

        $peserta = Peserta::create([
            'user_id' => $user->id,
            'nama' => 'Peserta Awal',
            'nik' => '3201018888888888',
            'tempat_lahir' => 'Semarang',
            'tanggal_lahir' => '1999-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'email' => 'peserta.edit@example.com',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Awal',
            'skema_id' => $skema->id,
            'tanggal_daftar' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->put(route('peserta.profil.update'), [
            'nama' => "Peserta Baru O'Connor",
            'nik' => '3201018888888888',
            'tempat_lahir' => 'Surakarta',
            'tanggal_lahir' => '1999-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'email' => 'peserta.edit@example.com',
            'no_hp' => '081987654321',
            'alamat' => 'Jl. Baru No. 12',
            'skema_id' => $skema->id,
        ]);

        $response->assertRedirect(route('peserta.profil.edit'));
        $response->assertSessionHas('success');

        // Update password akun
        $pwdResponse = $this->actingAs($user)->put(route('peserta.password.update'), [
            'password' => 'newpass6',
            'password_confirmation' => 'newpass6',
        ]);

        $pwdResponse->assertRedirect(route('peserta.profil.edit'));
        $pwdResponse->assertSessionHas('success');

        // Cek data terupdate
        $this->assertDatabaseHas('pesertas', [
            'id' => $peserta->id,
            'nama' => "Peserta Baru O'Connor",
            'no_hp' => '081987654321',
            'tempat_lahir' => 'Surakarta',
            'alamat' => 'Jl. Baru No. 12',
        ]);

        // Cek login dengan password baru
        auth()->logout();

        $loginResponse = $this->post(route('login.process'), [
            'email' => 'peserta.edit@example.com',
            'password' => 'newpass6',
        ]);

        $loginResponse->assertRedirect(route('dashboard'));
    }

    /**
     * Test peserta dapat memilih / mengganti skema baru dari dashboard.
     */
    public function test_peserta_can_change_skema_from_dashboard(): void
    {
        $skemaAwal = Skema::first();
        $skemaBaru = Skema::create([
            'kode_skema' => 'JNA-NEW',
            'nama_skema' => 'Junior Network Administrator New',
            'deskripsi' => 'Skema jaringan baru',
        ]);

        $user = User::create([
            'name' => 'Peserta Skema',
            'email' => 'skema.test@example.com',
            'password' => bcrypt('password123'),
            'role' => 'peserta',
        ]);

        $peserta = Peserta::create([
            'user_id' => $user->id,
            'nama' => 'Peserta Skema',
            'nik' => '3201017777777777',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'email' => 'skema.test@example.com',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Skema',
            'skema_id' => $skemaAwal->id,
            'tanggal_daftar' => now()->toDateString(),
        ]);

        // Cek dashboard menampilkan skema aktif
        $dashResponse = $this->actingAs($user)->get(route('dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee($skemaAwal->nama_skema);
        $dashResponse->assertSee('SKEMA AKTIF ANDA');

        // Ganti skema ke skema baru
        $changeResponse = $this->actingAs($user)->post(route('peserta.pilih.skema'), [
            'skema_id' => $skemaBaru->id,
        ]);

        $changeResponse->assertRedirect(route('dashboard'));
        $changeResponse->assertSessionHas('success');

        $this->assertDatabaseHas('pesertas', [
            'id' => $peserta->id,
            'skema_id' => $skemaBaru->id,
        ]);
    }
}

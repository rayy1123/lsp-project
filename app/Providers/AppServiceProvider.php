<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            // Pastikan kolom status_kompetensi ada pada tabel pesertas
            if (\Illuminate\Support\Facades\Schema::hasTable('pesertas')) {
                if (!\Illuminate\Support\Facades\Schema::hasColumn('pesertas', 'status_kompetensi')) {
                    \Illuminate\Support\Facades\Schema::table('pesertas', function (\Illuminate\Database\Schema\Blueprint $table) {
                        $table->string('status_kompetensi', 50)->default('Belum Mengumpulkan')->after('tanggal_daftar');
                    });
                } else {
                    try {
                        \Illuminate\Support\Facades\DB::statement("ALTER TABLE pesertas MODIFY status_kompetensi VARCHAR(50) NOT NULL DEFAULT 'Belum Mengumpulkan'");
                    } catch (\Throwable $e) {}
                }
            }

            // Pastikan kolom skema_id ada pada tabel tugas
            if (\Illuminate\Support\Facades\Schema::hasTable('tugas')) {
                if (!\Illuminate\Support\Facades\Schema::hasColumn('tugas', 'skema_id')) {
                    \Illuminate\Support\Facades\Schema::table('tugas', function (\Illuminate\Database\Schema\Blueprint $table) {
                        $table->foreignId('skema_id')->nullable()->after('id')->constrained('skemas')->cascadeOnDelete();
                    });
                }

                // Ubah peserta_id menjadi nullable agar tidak error saat admin membuat tugas skema
                if (\Illuminate\Support\Facades\Schema::hasColumn('tugas', 'peserta_id')) {
                    try {
                        \Illuminate\Support\Facades\DB::statement("ALTER TABLE tugas DROP FOREIGN KEY tugas_peserta_id_foreign");
                    } catch (\Throwable $e) {}

                    try {
                        \Illuminate\Support\Facades\DB::statement("ALTER TABLE tugas MODIFY peserta_id BIGINT UNSIGNED NULL DEFAULT NULL");
                    } catch (\Throwable $e) {}

                    try {
                        \Illuminate\Support\Facades\DB::statement("
                            UPDATE tugas t
                            JOIN pesertas p ON t.peserta_id = p.id
                            SET t.skema_id = p.skema_id
                            WHERE t.skema_id IS NULL
                        ");
                    } catch (\Throwable $e) {}
                }
            }

            // Pastikan tabel pengumpulan_tugas dibuat jika belum ada
            if (!\Illuminate\Support\Facades\Schema::hasTable('pengumpulan_tugas') && \Illuminate\Support\Facades\Schema::hasTable('tugas') && \Illuminate\Support\Facades\Schema::hasTable('pesertas')) {
                \Illuminate\Support\Facades\Schema::create('pengumpulan_tugas', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->foreignId('tugas_id')->constrained('tugas')->cascadeOnDelete();
                    $table->foreignId('peserta_id')->constrained('pesertas')->cascadeOnDelete();
                    $table->string('link_jawaban', 500)->nullable();
                    $table->string('file_jawaban')->nullable();
                    $table->enum('status', ['Menunggu Penilaian', 'Kompeten', 'Belum Kompeten'])->default('Menunggu Penilaian');
                    $table->text('catatan_admin')->nullable();
                    $table->timestamps();
                    $table->unique(['tugas_id', 'peserta_id']);
                });
            }
        } catch (\Throwable $e) {
            // Abaikan error skema database sementara saat migrasi
        }
    }
}

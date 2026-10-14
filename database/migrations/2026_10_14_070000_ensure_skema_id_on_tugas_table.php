<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1. Pastikan kolom skema_id ada di tabel tugas
        if (Schema::hasTable('tugas')) {
            if (!Schema::hasColumn('tugas', 'skema_id')) {
                Schema::table('tugas', function (Blueprint $table) {
                    $table->foreignId('skema_id')
                        ->nullable()
                        ->after('id')
                        ->constrained('skemas')
                        ->cascadeOnDelete();
                });
            }

            if (Schema::hasColumn('tugas', 'peserta_id')) {
                try {
                    DB::statement("ALTER TABLE tugas DROP FOREIGN KEY tugas_peserta_id_foreign");
                } catch (\Throwable $e) {}

                try {
                    DB::statement("ALTER TABLE tugas MODIFY peserta_id BIGINT UNSIGNED NULL DEFAULT NULL");
                } catch (\Throwable $e) {}

                try {
                    DB::statement("
                        UPDATE tugas t
                        JOIN pesertas p ON t.peserta_id = p.id
                        SET t.skema_id = p.skema_id
                        WHERE t.skema_id IS NULL
                    ");
                } catch (\Throwable $e) {}
            }
        }

        // 2. Pastikan tabel pengumpulan_tugas ada
        if (!Schema::hasTable('pengumpulan_tugas') && Schema::hasTable('tugas') && Schema::hasTable('pesertas')) {
            Schema::create('pengumpulan_tugas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tugas_id')
                    ->constrained('tugas')
                    ->cascadeOnDelete();
                $table->foreignId('peserta_id')
                    ->constrained('pesertas')
                    ->cascadeOnDelete();
                $table->string('link_jawaban', 500)->nullable();
                $table->string('file_jawaban')->nullable();
                $table->enum('status', [
                    'Menunggu Penilaian',
                    'Kompeten',
                    'Belum Kompeten'
                ])->default('Menunggu Penilaian');
                $table->text('catatan_admin')->nullable();
                $table->timestamps();

                $table->unique(['tugas_id', 'peserta_id']);
            });
        }
    }

    public function down(): void
    {
        //
    }
};

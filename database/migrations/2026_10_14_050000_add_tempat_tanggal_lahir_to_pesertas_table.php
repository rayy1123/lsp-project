<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pesertas', function (Blueprint $table) {
            if (!Schema::hasColumn('pesertas', 'jenis_kelamin')) {
                $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->default('Laki-laki')->after('nama');
            }
            if (!Schema::hasColumn('pesertas', 'tempat_lahir')) {
                $table->string('tempat_lahir')->nullable()->after('nik');
            }
            if (!Schema::hasColumn('pesertas', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pesertas', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('pesertas', 'tempat_lahir')) {
                $columns[] = 'tempat_lahir';
            }
            if (Schema::hasColumn('pesertas', 'tanggal_lahir')) {
                $columns[] = 'tanggal_lahir';
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};

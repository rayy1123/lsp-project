<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skema extends Model
{
    protected $fillable = [
        'kode_skema',
        'nama_skema',
        'deskripsi',
    ];

    // Relasi One-to-Many: Satu skema memiliki banyak peserta yang terdaftar
    public function pesertas()
    {
        return $this->hasMany(Peserta::class);
    }

    // Relasi One-to-Many: Satu skema memiliki banyak penugasan uji kompetensi
    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas';

    protected $fillable = [
        'skema_id',
        'peserta_id',
        'judul',
        'deskripsi',
    ];

    // Relasi Many-to-One: Tugas ini dimiliki oleh satu Skema Sertifikasi
    public function skema()
    {
        return $this->belongsTo(Skema::class);
    }

    // Relasi One-to-Many: Satu tugas memiliki banyak jawaban/pengumpulan dari peserta
    public function pengumpulan()
    {
        return $this->hasMany(PengumpulanTugas::class, 'tugas_id');
    }
}

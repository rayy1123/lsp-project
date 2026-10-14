<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'jenis_kelamin',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'email',
        'no_hp',
        'alamat',
        'skema_id',
        'tanggal_daftar',
        'status_kompetensi',
    ];

    // Relasi Many-to-One: Data peserta terhubung ke satu akun User login
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi Many-to-One: Data peserta memilih satu Skema Sertifikasi
    public function skema()
    {
        return $this->belongsTo(Skema::class);
    }

    // Relasi One-to-Many: Peserta memiliki banyak riwayat pengumpulan tugas
    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class);
    }

    // Accessor: Menghitung total seluruh tugas yang ada pada skema sertifikasi peserta
    public function getTotalTugasAttribute(): int
    {
        return Tugas::where('skema_id', $this->skema_id)->count();
    }

    // Accessor: Menghitung jumlah tugas yang telah dikumpulkan (link/gambar) oleh peserta
    public function getTugasSelesaiCountAttribute(): int
    {
        return $this->pengumpulanTugas()
            ->whereHas('tugas', function ($q) {
                $q->where('skema_id', $this->skema_id);
            })
            ->where(function ($q) {
                $q->whereNotNull('link_jawaban')
                  ->orWhereNotNull('file_jawaban');
            })
            ->count();
    }

    // Accessor: Menghitung jumlah tugas yang telah dinilai dan disetujui KOMPETEN
    public function getTugasKompetenCountAttribute(): int
    {
        return $this->pengumpulanTugas()
            ->whereHas('tugas', function ($q) {
                $q->where('skema_id', $this->skema_id);
            })
            ->where('status', 'Kompeten')
            ->count();
    }

    // Accessor: Menghitung jumlah tugas yang dinilai BELUM KOMPETEN (perlu perbaikan)
    public function getTugasBelumKompetenCountAttribute(): int
    {
        return $this->pengumpulanTugas()
            ->whereHas('tugas', function ($q) {
                $q->where('skema_id', $this->skema_id);
            })
            ->where('status', 'Belum Kompeten')
            ->count();
    }

    // Accessor: Menghitung persentase pengerjaan/pengumpulan tugas (0 - 100%)
    public function getPersentaseSelesaiAttribute(): int
    {
        $total = $this->total_tugas;
        return $total > 0 ? (int) round(($this->tugas_selesai_count / $total) * 100) : 0;
    }

    // Accessor: Menghitung persentase kelulusan kompetensi (0 - 100%)
    public function getPersentaseKompetenAttribute(): int
    {
        $total = $this->total_tugas;
        return $total > 0 ? (int) round(($this->tugas_kompeten_count / $total) * 100) : 0;
    }

    // Method: Menghitung dan menyinkronkan status kelulusan peserta secara bertahap (tidak langsung kompeten)
    public function sinkronkanStatusKompetensi(): string
    {
        $total = $this->total_tugas;

        if ($total === 0) {
            $status = 'Belum Ada Tugas';
        } else {
            $kompeten = $this->tugas_kompeten_count;
            $belumKompeten = $this->tugas_belum_kompeten_count;
            $selesai = $this->tugas_selesai_count;

            if ($kompeten === $total) {
                // Semua tugas dinilai Kompeten
                $status = 'Kompeten';
            } elseif ($belumKompeten > 0) {
                // Ada tugas yang dinilai Belum Kompeten
                $status = 'Belum Kompeten';
            } elseif ($selesai === $total) {
                // Semua tugas sudah dikumpulkan, menunggu keputusan asesor
                $status = 'Menunggu Penilaian';
            } elseif ($selesai > 0) {
                // Baru selesai sebagian (misal 2 dari 4)
                $status = 'Dalam Proses';
            } else {
                $status = 'Belum Mengumpulkan';
            }
        }

        $this->status_kompetensi = $status;
        $this->save();

        return $status;
    }
}
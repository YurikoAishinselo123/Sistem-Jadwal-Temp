<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Makul extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['jumlah_sks_teori', 'jumlah_sks_praktek'];

    /**
     * Auto-compute SKS Teori: 1 SKS Teori = 1 Sesi Teori
     */
    public function getJumlahSksTeoriAttribute(): int
    {
        return (int) $this->jumlah_sesi_teori;
    }

    /**
     * Auto-compute SKS Praktik: 1 SKS Praktik = 3 Sesi Praktik
     * Always returns an integer (rounded up).
     */
    public function getJumlahSksPraktekAttribute(): int
    {
        if ($this->jumlah_sesi_praktek === 0) {
            return 0;
        }
        return (int) ceil($this->jumlah_sesi_praktek / 3);
    }
}

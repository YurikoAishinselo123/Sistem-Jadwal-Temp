<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Schedule extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    public function makul(): BelongsTo
    {
        return $this->belongsTo(Makul::class);
    }

    public function theoryRoom(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'theory_room_id');
    }

    public function practiceRoom(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'practice_room_id');
    }

    public function dosens(): BelongsToMany
    {
        return $this->belongsToMany(Dosen::class, 'schedule_dosen');
    }

    public function laborans(): BelongsToMany
    {
        return $this->belongsToMany(Laboran::class, 'schedule_laboran');
    }
}

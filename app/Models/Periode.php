<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periode extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'is_locked'       => 'boolean',
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Lock this periode: sets status to nonaktif and is_locked to true.
     * Once locked, data in this periode cannot be edited or deleted.
     */
    public function tutupPeriode(): void
    {
        $this->update([
            'status'    => 'nonaktif',
            'is_locked' => true,
        ]);
    }

    /**
     * Check whether this periode is locked (read-only).
     */
    public function isLocked(): bool
    {
        return (bool) $this->is_locked;
    }
}

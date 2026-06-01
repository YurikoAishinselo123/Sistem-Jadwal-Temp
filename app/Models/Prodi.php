<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 */
class Prodi extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}

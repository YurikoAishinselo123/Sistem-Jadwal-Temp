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

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function theoryRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'theory_room_id');
    }

    public function practiceRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'practice_room_id');
    }

    public function lecturers(): BelongsToMany
    {
        return $this->belongsToMany(Lecturer::class, 'schedule_lecturer');
    }

    public function assistants(): BelongsToMany
    {
        return $this->belongsToMany(Assistant::class, 'schedule_assistant');
    }
}

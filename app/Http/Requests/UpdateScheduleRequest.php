<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'academic_year' => 'required|string',
            'schedule_type' => 'required|in:semester,pengganti,ujian',
            'study_program_id' => 'required|exists:study_programs,id',
            'course_id' => 'required|exists:courses,id',
            'class' => ['required', 'string', 'size:1', 'regex:/^[A-Z]$/'],
            'day' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:offline,online',
            'theory_room_id' => 'required_if:status,offline|nullable|exists:rooms,id',
            'practice_room_id' => 'nullable|exists:rooms,id',
            'lecturers' => 'required|array|min:2|max:3',
            'lecturers.*' => 'exists:lecturers,id',
            'assistants' => 'required|array|min:1|max:2',
            'assistants.*' => 'exists:assistants,id',
        ];
    }
}

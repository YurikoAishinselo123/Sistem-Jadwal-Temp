<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'periode_id'       => 'required|exists:periodes,id',
            'schedule_type'    => 'required|in:semester,pengganti,ujian',
            'prodi_id'         => 'required|exists:prodis,id',
            'makul_id'         => 'required|exists:makuls,id',
            'class'            => ['required', 'string', 'size:1', 'regex:/^[A-Z]$/'],
            'day'              => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'status'           => 'required|in:offline,online',
            'theory_room_id'   => 'required_if:status,offline|nullable|exists:ruangans,id',
            'practice_room_id' => 'nullable|exists:ruangans,id',
            'dosens'           => 'required|array|min:1|max:3',
            'dosens.*'         => 'exists:dosens,id',
            'laborans'         => 'required|array|min:1|max:2',
            'laborans.*'       => 'exists:laborans,id',
        ];
    }
}

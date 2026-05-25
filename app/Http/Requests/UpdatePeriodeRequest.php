<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePeriodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'periode'          => 'required|string|max:100',
            'status'           => 'required|in:aktif,nonaktif',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
        ];
    }
}

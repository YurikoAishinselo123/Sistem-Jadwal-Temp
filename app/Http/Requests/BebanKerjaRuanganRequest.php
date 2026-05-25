<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BebanKerjaRuanganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ruangan_id' => 'required|exists:ruangans,id',
            'periode_id' => 'required|exists:periodes,id',
        ];
    }
}

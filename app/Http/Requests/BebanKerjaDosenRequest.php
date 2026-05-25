<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BebanKerjaDosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dosen_id'   => 'required|exists:dosens,id',
            'periode_id' => 'required|exists:periodes,id',
        ];
    }
}

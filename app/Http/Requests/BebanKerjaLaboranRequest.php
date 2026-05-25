<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BebanKerjaLaboranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'laboran_id' => 'required|exists:laborans,id',
            'periode_id' => 'required|exists:periodes,id',
        ];
    }
}

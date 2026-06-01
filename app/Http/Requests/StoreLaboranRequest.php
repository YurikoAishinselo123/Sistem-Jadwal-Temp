<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLaboranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_laboran' => 'required|string|max:20|unique:laborans,kode_laboran',
            'nama_laboran' => 'required|string|max:255|unique:laborans,nama_laboran',
        ];
    }

    public function messages(): array
    {
        return [
            'kode_laboran.unique' => 'Kode laboran sudah digunakan.',
            'nama_laboran.unique' => 'Nama laboran sudah digunakan.',
        ];
    }
}

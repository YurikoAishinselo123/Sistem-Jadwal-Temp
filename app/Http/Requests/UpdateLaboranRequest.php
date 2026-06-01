<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLaboranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('laboran');

        return [
            'kode_laboran' => ['required', 'string', 'max:20', Rule::unique('laborans', 'kode_laboran')->ignore($id)],
            'nama_laboran' => ['required', 'string', 'max:255', Rule::unique('laborans', 'nama_laboran')->ignore($id)],
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

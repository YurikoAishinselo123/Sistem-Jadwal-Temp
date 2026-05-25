<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('dosen');

        return [
            'kode_dosen' => ['required', 'string', 'max:20', Rule::unique('dosens', 'kode_dosen')->ignore($id)],
            'nama_dosen' => ['required', 'string', 'max:255', Rule::unique('dosens', 'nama_dosen')->ignore($id)],
        ];
    }
}

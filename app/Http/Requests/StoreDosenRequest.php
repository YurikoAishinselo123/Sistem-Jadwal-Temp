<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_dosen' => 'required|string|max:20|unique:dosens,kode_dosen',
            'nama_dosen' => 'required|string|max:255|unique:dosens,nama_dosen',
        ];
    }

    public function messages(): array
    {
        return [
            'kode_dosen.unique' => 'Kode dosen sudah digunakan.',
            'nama_dosen.unique' => 'Nama dosen sudah digunakan.',
        ];
    }
}

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
}

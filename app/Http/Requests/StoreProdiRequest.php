<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProdiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_prodi' => 'required|string|max:20|unique:prodis,kode_prodi',
            'nama_prodi' => 'required|string|max:255|unique:prodis,nama_prodi',
        ];
    }
}

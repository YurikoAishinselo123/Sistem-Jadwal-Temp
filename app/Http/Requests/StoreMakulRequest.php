<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMakulRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_makul'         => 'required|string|max:20|unique:makuls,kode_makul',
            'nama_makul'         => 'required|string|max:255|unique:makuls,nama_makul',
            'jumlah_sesi_teori'  => 'required|integer|min:0',
            'jumlah_sesi_praktek'=> 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'kode_makul.unique' => 'Kode mata kuliah sudah digunakan.',
            'nama_makul.unique' => 'Nama mata kuliah sudah digunakan.',
        ];
    }
}

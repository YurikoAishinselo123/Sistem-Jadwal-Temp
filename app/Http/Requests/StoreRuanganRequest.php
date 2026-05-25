<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRuanganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_ruangan'  => 'required|string|max:20|unique:ruangans,kode_ruangan',
            'nama_ruangan'  => 'required|string|max:255|unique:ruangans,nama_ruangan',
            'jenis_ruangan' => 'required|in:teori,praktik',
        ];
    }
}

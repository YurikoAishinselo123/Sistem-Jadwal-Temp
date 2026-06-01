<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRuanganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('ruangan');

        return [
            'kode_ruangan'  => ['required', 'string', 'max:20', Rule::unique('ruangans', 'kode_ruangan')->ignore($id)],
            'nama_ruangan'  => ['required', 'string', 'max:255', Rule::unique('ruangans', 'nama_ruangan')->ignore($id)],
            'jenis_ruangan' => 'required|in:teori,praktik',
        ];
    }

    public function messages(): array
    {
        return [
            'kode_ruangan.unique' => 'Kode ruangan sudah digunakan.',
            'nama_ruangan.unique' => 'Nama ruangan sudah digunakan.',
        ];
    }
}

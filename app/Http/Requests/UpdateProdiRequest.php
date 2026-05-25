<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProdiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('prodi');

        return [
            'kode_prodi' => ['required', 'string', 'max:20', Rule::unique('prodis', 'kode_prodi')->ignore($id)],
            'nama_prodi' => ['required', 'string', 'max:255', Rule::unique('prodis', 'nama_prodi')->ignore($id)],
        ];
    }
}

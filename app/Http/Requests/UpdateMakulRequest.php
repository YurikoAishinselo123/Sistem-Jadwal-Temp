<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMakulRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('makul');

        return [
            'kode_makul'         => ['required', 'string', 'max:20', Rule::unique('makuls', 'kode_makul')->ignore($id)],
            'nama_makul'         => ['required', 'string', 'max:255', Rule::unique('makuls', 'nama_makul')->ignore($id)],
            'jumlah_sesi_teori'  => 'required|integer|min:0',
            'jumlah_sesi_praktek'=> 'required|integer|min:0',
        ];
    }
}

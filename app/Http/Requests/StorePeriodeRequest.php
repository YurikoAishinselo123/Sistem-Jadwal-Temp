<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePeriodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'periode'          => 'required|string|max:100|unique:periodes,periode',
            'status'           => 'sometimes|in:aktif,nonaktif',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
        ];
    }

    public function messages(): array
    {
        return [
            'periode.unique' => 'Nama periode sudah digunakan.',
        ];
    }

    /**
     * Merge default status = aktif if not provided.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => 'aktif',
            'tanggal_selesai' => null,
        ]);
    }
}

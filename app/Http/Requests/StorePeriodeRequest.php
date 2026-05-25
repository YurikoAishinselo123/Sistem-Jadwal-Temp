<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePeriodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'periode'          => 'required|string|max:100',
            'status'           => 'sometimes|in:aktif,nonaktif',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',
        ];
    }

    /**
     * Merge default status = aktif if not provided.
     */
    protected function prepareForValidation(): void
    {
        $this->mergeIfMissing(['status' => 'aktif']);
    }
}

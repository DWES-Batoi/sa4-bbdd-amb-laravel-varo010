<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePartitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'local_id' => 'required|exists:equips,id|different:visitant_id',
            'visitant_id' => 'required|exists:equips,id|different:local_id',
            'data_partit' => 'required|date',
            'gols_local' => 'nullable|integer|min:0',
            'gols_visitant' => 'nullable|integer|min:0',
        ];
    }
}

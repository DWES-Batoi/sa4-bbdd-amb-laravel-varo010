<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJugadorRequest extends FormRequest
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
            'nom' => 'required|string|min:2',
            'cognoms' => 'required|string|min:2',
            'dorsal' => 'required|integer|min:1|max:99',
            'equip_id' => 'required|exists:equips,id',
        ];
    }
}

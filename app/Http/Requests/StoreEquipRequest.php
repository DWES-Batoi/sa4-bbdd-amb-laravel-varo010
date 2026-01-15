<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEquipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * De moment deixem sempre true.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regles de validació per a crear un nou equip.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom'       => 'required|min:3',
            'estadi_id' => 'required|integer|exists:estadis,id',
            'titols'    => 'required|integer|min:0',
            'escut'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}

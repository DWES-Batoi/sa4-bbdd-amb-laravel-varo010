<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EquipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255', 'unique:equips,nom,' . $this->route('equip')?->id], 
            'estadi_id' => ['nullable', 'exists:estadis,id'],
            'titols' => ['nullable', 'integer', 'min:0'],
            'escut' => ['nullable', 'string'],
        ];
    }
}

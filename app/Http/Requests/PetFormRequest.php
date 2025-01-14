<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PetFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'species' => ['required'],
            'breed' => ['required'],
            'birth_date' => ['required'],
            'is_active' => ['required', 'bool'],
            'owner_id' => ['required', 'exists:users,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

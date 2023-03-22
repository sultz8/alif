<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required|email|max:255|unique:users',
            'first_name' => 'required|string|max:255',
            'second_name' => 'required|string|max:255',
            'phone_number' => 'required|numeric|digits:12',
            'password' => [...Password::required(), 'confirmed'],
            'is_terms_accepted' => 'required|accepted',
        ];
    }
}

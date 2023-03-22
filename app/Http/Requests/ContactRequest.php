<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if ($this->isMethod('POST')) {
            return [
                'full_name' => 'required|string|max:255|unique:contacts',
                'birth_day' => 'required|date'
            ];
        } else {
            return [
                'full_name' => 'string|max:255|unique:contacts',
                'birth_day' => 'date'
            ];
        }
    }
}

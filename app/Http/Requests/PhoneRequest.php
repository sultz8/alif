<?php

namespace App\Http\Requests;

use App\Models\Phone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PhoneRequest extends FormRequest
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
                'phones' => 'array',
                'phones.*' => 'array',
                'phones.*.type' => 'required|string|in:' . implode(',', Phone::TYPES),
                'phones.*.phone_number' => 'required|numeric|digits:12'
            ];
        } else {
            return [
                'phones' => 'array',
                'phones.*' => 'array',
                'phones.*.id' => 'required|int|exists:phones',
                'phones.*.type' => 'string|in:' . implode(',', Phone::TYPES),
                'phones.*.phone_number' => 'numeric|digits:12'
            ];
        }
    }
}

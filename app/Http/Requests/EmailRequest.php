<?php

namespace App\Http\Requests;

use App\Models\Email;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EmailRequest extends FormRequest
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
                'emails' => 'array',
                'emails.*' => 'array',
                'emails.*.type' => 'required|string|in:' . implode(',' ,Email::TYPES),
                'emails.*.email' => 'required|string|max:255'
            ];
        } else {
            return [
                'emails' => 'array',
                'emails.*' => 'array',
                'emails.*.id' => 'required|int|exists:emails',
                'emails.*.type' => 'string|in:' . implode(',' ,Email::TYPES),
                'emails.*.email' => 'string|max:255'
            ];
        }
    }
}

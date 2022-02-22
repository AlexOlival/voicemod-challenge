<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|string',
            'surnames' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email',
            'country' => 'sometimes|string',
            'phone' => 'sometimes|string',
            'postal_code' => 'sometimes|string',
        ];
    }
}

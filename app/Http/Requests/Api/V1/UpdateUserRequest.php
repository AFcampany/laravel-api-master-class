<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends BaseUserRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules =  [
            'data.attributes.name' => 'sometimes|string',
            'data.attributes.email' => 'sometimes|email|string',
            'data.attributes.is_manager' => 'sometimes|boolean',
            'data.attributes.password' => 'sometimes|string',
        ];

        return $rules;
    }
}

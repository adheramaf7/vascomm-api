<?php

namespace App\Http\Requests\API;

use App\Enums\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class SaveUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'     => ['required'],
            'email'    => ['required'],
            'role'     => ['required', new Enum(UserRoleEnum::class),],
            'password' => ['required', 'min:6', 'confirmed',],
        ];
    }
}

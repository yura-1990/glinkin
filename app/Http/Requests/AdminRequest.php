<?php

namespace App\Http\Requests;

use App\Enums\UserRoleTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->role_id === UserRoleTypeEnum::ADMIN->name;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:users,name',
            'phone' => 'required|string',
            'status' => 'nullable|integer',
            'role_id' => 'nullable|integer',
            'password' => 'required|string',
        ];
    }
}

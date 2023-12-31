<?php

namespace App\Http\Requests;

use App\Enums\UserRoleTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class NewsStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->role_id === UserRoleTypeEnum::ADMIN->value || auth()->user()->role_id === UserRoleTypeEnum::EDITOR->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'cover' => ['nullable', File::types(['png', 'jpg', 'jpeg', 'webp'])],
            'description' => 'required|string',
            'text' => 'required|string'
        ];
    }
}

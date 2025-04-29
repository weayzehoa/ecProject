<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:develop,admin,staff',
            'mobile' => 'required|string|max:20',
            'tel' => 'nullable|string|max:20',
            'is_on' => 'required|boolean',
        ];
    }

    public function attributes(): array
    {
        return trans('validation.attributes.admin');
    }
}
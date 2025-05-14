<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ImageSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:30',
            'type' => 'required|string|max:30',
            'width' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'small_pic' => 'nullable|numeric|in:0,1',
        ];
    }

    public function attributes(): array
    {
        return trans('validation.attributes.imageSettings');
    }
}
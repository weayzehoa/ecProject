<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CKEditorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'upload' => 'required|max:5120|mimes:jpg,jpeg,png,gif,webp,pdf,mp4,avi,mov',
        ];
    }

    public function attributes(): array
    {
        return trans('validation.attributes.ckeditor.messages.upload');
    }
}
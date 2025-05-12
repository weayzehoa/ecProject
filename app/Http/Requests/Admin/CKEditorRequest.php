<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CKEditorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'upload' => 'required|file|max:5120|mimes:jpg,jpeg,png,gif,webp,pdf,mp4,avi,mov',
        ];
    }

    public function attributes(): array
    {
        return [
            'upload' => trans('validation.attributes.ckeditor.upload'),
        ];
    }

    public function messages(): array
    {
        return trans('validation.attributes.ckeditor.messages.upload');
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'uploaded' => 0,
            'error' => [
                'message' => $validator->errors()->first('upload'),
            ],
        ], 200));
    }
}
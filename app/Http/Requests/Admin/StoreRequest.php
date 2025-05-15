<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'tel' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'service_time_start' => 'required|string|max:20',
            'service_time_end' => 'required|string|max:20',
            'fb_url' => ['nullable', 'url', 'max:255'],
            'ig_url' => ['nullable', 'url', 'max:255'],
            'line_id' => 'nullable|string|max:100',
            'line_qrcode' => ['nullable', 'url', 'max:255'],
            'lon' => 'nullable|string|max:50',
            'lat' => 'nullable|string|max:50',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ];
    }

    public function attributes(): array
    {
        return trans('validation.attributes.store');
    }

    public function messages(): array
    {
        return flattenMessages(trans('validation.attributes.store.messages'));
    }
}
<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CompanySettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'brand' => 'required|string|max:255',
            'tax_num' => 'required|string|max:20',
            'tel' => 'required|string|max:20',
            'fax' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'address_en' => 'nullable|string|max:255',
            'service_tel' => 'nullable|string|max:20',
            'service_email' => 'nullable|email|max:255',
            'service_time_start' => 'required|string|max:20',
            'service_time_end' => 'required|string|max:20',
            'website' => ['required', 'string', 'max:255', 'regex:/^[\w\.-]+(?:\.[\w\.-]+)+(\/[\w\-._~:\/?#\[\]@!$&\'()*+,;=.]*)?$/i'],
            'url' => ['required', 'url', 'max:255'],
            'fb_url' => ['nullable', 'url', 'max:255'],
            'Instagram_url' => ['nullable', 'url', 'max:255'],
            'line' => 'nullable|string|max:100',
            'line_qrcode' => ['nullable', 'url', 'max:255'],
            'lon' => 'nullable|string|max:50',
            'lat' => 'nullable|string|max:50',
            'wechat_id' => 'nullable|string|max:100',
        ];
    }

    public function attributes(): array
    {
        return trans('validation.attributes.company');
    }
}

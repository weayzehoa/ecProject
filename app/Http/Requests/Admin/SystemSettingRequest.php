<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SystemSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shippingFees' => 'nullable|numeric|in:0,1',
            'totalDiscounts' => 'nullable|numeric|in:0,1',
            'productPromos' => 'nullable|numeric|in:0,1',
        ];
    }
}
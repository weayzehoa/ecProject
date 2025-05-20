<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ShippingFeeRequest extends FormRequest
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
            'fee' => 'nullable|numeric',
            'free' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'start_time' => 'nullable|date_format:Y-m-d H:i:s',
            'end_time' => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:start_time',
            'is_on' => 'nullable|numeric|in:0,1',
        ];
    }

    public function attributes(): array
    {
        return trans('validation.attributes.shippingFees');
    }
}
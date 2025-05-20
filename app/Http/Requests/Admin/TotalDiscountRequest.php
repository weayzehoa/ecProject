<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TotalDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'start_time' => 'nullable|date_format:Y-m-d H:i:s',
            'end_time' => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:start_time',
            'is_on' => 'nullable|numeric|in:0,1',
        ];
    }

    public function attributes(): array
    {
        return trans('validation.attributes.totalDiscounts');
    }
}
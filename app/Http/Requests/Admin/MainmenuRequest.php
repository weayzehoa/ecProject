<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MainmenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:30',
            'icon' => 'nullable|string|max:100',
            'func_code' => 'required|string|max:50',
            'url_type' => 'required|numeric|in:0,1,2',
            'url' => 'nullable|string|max:255',
            'power_action' => 'nullable|array',
            'power_action.*' => 'in:N,D,M,O,S,IM,EX,DL,MK,PR,SMS,SEM',
            'open_window' => 'nullable|numeric|in:0,1',
            'is_on' => 'nullable|numeric|in:0,1',
        ];
    }

    public function attributes(): array
    {
        return trans('validation.attributes.mainmenu');
    }

    public function messages(): array
    {
        return flattenMessages(trans('validation.attributes.mainmenu.messages'));
    }
}
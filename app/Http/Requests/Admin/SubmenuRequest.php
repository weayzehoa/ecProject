<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SubmenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'mainmenu_id' => 'required|numeric',
            'name' => 'required|string|max:30',
            'icon' => 'nullable|string|max:100',
            'func_code' => 'nullable|string|max:50',
            'url_type' => 'required|numeric|in:1,2',
            'power_action' => 'nullable|array',
            'power_action.*' => 'in:N,D,M,O,S,IM,EX,DL,MK,PR,SMS,SEM',
            'open_window' => 'nullable|numeric|in:0,1',
            'is_on' => 'nullable|numeric|in:0,1',
        ];

        // 動態判斷 url 驗證方式
        if ($this->input('url_type') == 1) {
            // 內部連結（例如: /admin/dashboard）
            $rules['url'] = ['required', 'string', 'regex:/^[a-zA-Z0-9\/\-_]+$/', 'max:255'];
        } elseif ($this->input('url_type') == 2) {
            // 外部網址
            $rules['url'] = ['required', 'string', 'url', 'max:255'];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return trans('validation.attributes.submenu');
    }

    public function messages(): array
    {
        return flattenMessages(trans('validation.attributes.submenu.messages'));
    }
}
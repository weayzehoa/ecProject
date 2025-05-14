@extends('admin.layouts.app')

@section('title', '圖片上傳設定')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            {{-- alert訊息 --}}
            {{-- @include('admin.layouts.alert_message') --}}
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><b>圖片上傳設定</b></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                        <li class="breadcrumb-item active"><a href="{{ url('settings') }}">圖片上傳設定</a></li>
                        <li class="breadcrumb-item active">清單</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-8">
                                    <div class="float-left d-flex align-items-center">
                                        <ul>
                                            <li>寬度與高度皆設定空白時，上傳時保留原圖。</li>
                                            <li>寬度設定數值與高度設定空白時，上傳時按照寬度限制等比例縮小原圖。</li>
                                            <li>寬度設定空白與高度設定數值時，上傳時按照高度限制等比例縮小原圖。</li>
                                            <li>寬度與高度皆有設定數值時，依照寬度等比例縮小原圖，再依照高度等比例縮小原圖，最後再按寬度與高度設定比例補邊。</li>
                                            <li>縮圖啟用則會產生中圖(原圖1/2)、小圖(原圖1/4)。ex: 檔案名稱.jpg 檔案名稱_m.jpg 檔案名稱_s.jpg</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="float-right">
                                        <div class="input-group input-group-sm align-middle align-items-middle">
                                            <span class="badge badge-purple text-lg mr-2">總筆數：{{ !empty($settings) ? number_format($settings->total()) : 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-10 offset-1">
                                    <form id="newForm" method="POST" action="{{ route('admin.imageSettings.store') }}" novalidate>
                                        @csrf

                                        <div class="input-group mb-3">

                                            {{-- 名稱 --}}
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">名稱</span>
                                            </div>
                                            <input type="text"
                                                   name="name"
                                                   value="{{ !old('id') ? old('name') : '' }}"
                                                   class="form-control {{ $errors->has('name') && !old('id') ? 'is-invalid' : '' }}"
                                                   placeholder="請輸入名稱">

                                            {{-- 代碼 --}}
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">代碼</span>
                                            </div>
                                            <input type="text"
                                                   name="type"
                                                   value="{{ !old('id') ? old('type') : '' }}"
                                                   class="form-control {{ $errors->has('type') && !old('id') ? 'is-invalid' : '' }}"
                                                   placeholder="請輸入代碼">

                                            {{-- 寬度 --}}
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">寬度</span>
                                            </div>
                                            <input type="text"
                                                   name="width"
                                                   value="{{ !old('id') ? old('width') : '' }}"
                                                   class="form-control {{ $errors->has('width') && !old('id') ? 'is-invalid' : '' }}"
                                                   placeholder="請輸入寬度（可留空）">

                                            {{-- 高度 --}}
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">高度</span>
                                            </div>
                                            <input type="text"
                                                   name="height"
                                                   value="{{ !old('id') ? old('height') : '' }}"
                                                   class="form-control {{ $errors->has('height') && !old('id') ? 'is-invalid' : '' }}"
                                                   placeholder="請輸入高度（可留空）">

                                            {{-- 縮圖 --}}
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">縮圖</span>
                                            </div>
                                            <div class="input-group-prepend ml-2 mr-2">
                                                <div class="icheck-success d-inline">
                                                    <input type="radio" name="small_pic" value="1" id="small_pic1"
                                                           {{ (!old('id') && old('small_pic', '0') == '1') ? 'checked' : '' }}>
                                                    <label for="small_pic1">是</label>
                                                </div>
                                                <div class="icheck-success d-inline ml-2">
                                                    <input type="radio" name="small_pic" value="0" id="small_pic0"
                                                           {{ (!old('id') && old('small_pic', '0') == '0') ? 'checked' : '' }}>
                                                    <label for="small_pic0">否</label>
                                                </div>
                                            </div>

                                            {{-- 提交 --}}
                                            <div class="input-group-prepend">
                                                <button type="submit" class="btn btn-primary">新增</button>
                                            </div>
                                        </div>

                                        {{-- 🚨 集中錯誤訊息（只在新增錯誤時顯示） --}}
                                        @if ($errors->any() && !old('id'))
                                            <ul class="mt-3 pl-3 small text-danger" style="list-style: disc;">
                                                @foreach ($errors->all() as $error)
                                                    <li class="mb-1">{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </form>
                                </div>

                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-left" width="15%">名稱</th>
                                        <th class="text-left" width="15%">代碼</th>
                                        <th class="text-left" width="15%">寬度</th>
                                        <th class="text-left" width="15%">高度</th>
                                        <th class="text-center" width="15%">縮圖</th>
                                        <th class="text-center" width="15%">修改</th>
                                        <th class="text-center" width="10%">刪除</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($settings as $setting)
                                    <tr>
                                        {{-- 名稱 --}}
                                        <td class="text-left align-middle">
                                            <input class="form-control form-control-sm {{ $errors->has('name') && old('id') == $setting->id ? 'is-invalid' : '' }}"
                                                   type="text"
                                                   name="name"
                                                   form="form-{{ $setting->id }}"
                                                   value="{{ old('id') == $setting->id ? old('name') : $setting->name }}">
                                            @if ($errors->has('name') && old('id') == $setting->id)
                                                <div class="text-danger small">{{ $errors->first('name') }}</div>
                                            @endif
                                        </td>

                                        {{-- 代碼 --}}
                                        <td class="text-left align-middle">
                                            <input class="form-control form-control-sm {{ $errors->has('type') && old('id') == $setting->id ? 'is-invalid' : '' }}"
                                                   type="text"
                                                   name="type"
                                                   form="form-{{ $setting->id }}"
                                                   value="{{ old('id') == $setting->id ? old('type') : $setting->type }}">
                                            @if ($errors->has('type') && old('id') == $setting->id)
                                                <div class="text-danger small">{{ $errors->first('type') }}</div>
                                            @endif
                                        </td>

                                        {{-- 寬度 --}}
                                        <td class="text-left align-middle">
                                            <input class="form-control form-control-sm {{ $errors->has('width') && old('id') == $setting->id ? 'is-invalid' : '' }}"
                                                   type="text"
                                                   name="width"
                                                   form="form-{{ $setting->id }}"
                                                   value="{{ old('id') == $setting->id ? old('width') : $setting->width }}">
                                            @if ($errors->has('width') && old('id') == $setting->id)
                                                <div class="text-danger small">{{ $errors->first('width') }}</div>
                                            @endif
                                        </td>

                                        {{-- 高度 --}}
                                        <td class="text-left align-middle">
                                            <input class="form-control form-control-sm {{ $errors->has('height') && old('id') == $setting->id ? 'is-invalid' : '' }}"
                                                   type="text"
                                                   name="height"
                                                   form="form-{{ $setting->id }}"
                                                   value="{{ old('id') == $setting->id ? old('height') : $setting->height }}">
                                            @if ($errors->has('height') && old('id') == $setting->id)
                                                <div class="text-danger small">{{ $errors->first('height') }}</div>
                                            @endif
                                        </td>

                                        {{-- 縮圖 --}}
                                        <td class="text-center align-middle">
                                            <input type="checkbox"
                                                   name="small_pic"
                                                   value="1"
                                                   form="form-{{ $setting->id }}"
                                                   data-bootstrap-switch
                                                   data-on-text="啟用"
                                                   data-off-text="停用"
                                                   data-off-color="secondary"
                                                   data-on-color="success"
                                                   {{ $setting->small_pic ? 'checked' : '' }}>
                                        </td>

                                        {{-- 修改 --}}
                                        <td class="text-center align-middle">
                                            <form id="form-{{ $setting->id }}"
                                                  action="{{ route('admin.imageSettings.update', $setting->id) }}"
                                                  method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="id" value="{{ $setting->id }}">
                                                <button type="button"
                                                        class="btn btn-sm btn-primary modify-btn"
                                                        data-id="{{ $setting->id }}">
                                                    <i class="far fa-edit"></i>
                                                </button>
                                            </form>
                                        </td>

                                        {{-- 刪除 --}}
                                        <td class="text-center align-middle">
                                            <form action="{{ route('admin.imageSettings.destroy', $setting->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger delete-btn">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="float-left">
                                <span class="badge badge-primary text-lg ml-1">總筆數：{{ !empty($settings) ? number_format($settings->total()) : 0 }}</span>
                            </div>
                            <div class="float-right">
                                @if(isset($appends))
                                {{ $settings->appends($appends)->render() }}
                                @else
                                {{ $settings->render() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/ekko-lightbox/ekko-lightbox.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/parsley/parsley.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('vendor/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
<script src="{{ asset('vendor/parsley/parsley.min.js') }}"></script>
<script src="{{ asset('vendor/parsley/zh_tw.js') }}"></script>
<script>
    (function($) {
        "use strict";
        // 新增按鈕行為控制：失敗時解除禁用
        $('.new-btn').click(function() {
            const $btn = $(this);
            const $form = $btn.closest('form');
            $btn.prop('disabled', true);

            const isValid = $form.parsley().validate();
            if (!isValid) {
                $btn.prop('disabled', false); // 失敗時重新啟用按鈕
            } else {
                $form.submit(); // 成功才送出
            }
        });

        $("input[data-bootstrap-switch]").each(function() {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });

        $('.modify-btn').click(function() {
            const id = $(this).data('id');
            $(`#form-${id}`).submit();
        });

        $('.delete-btn').click(function() {
            if (confirm('請確認是否要刪除這筆資料?')) {
                $(this).closest('form').submit();
            }
        });

    })(jQuery);
</script>
@endpush
@extends('admin.layouts.app')

@section('title', '運費折扣設定')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            {{-- alert訊息 --}}
            {{-- @include('admin.layouts.alert_message') --}}
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><b>運費折扣設定</b></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                        <li class="breadcrumb-item active"><a href="{{ url('shippingFees') }}">運費折扣設定</a></li>
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
                                <div class="col-12 mb-2">
                                    @if(Auth::user()->role == 'develop' || in_array($menuCode.'M', explode(',',Auth::user()->permissions)))
                                        @if(in_array($systemSettingMenuCode.'M', explode(',',Auth::user()->permissions)))
                                        <form action="{{ route('admin.systemSettings.update', 1) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="shippingFees" value="0">
                                            <input type="checkbox" name="shippingFees" value="{{ isset($systemSetting) && $systemSetting->shippingFees == 1 ? 0 : 1 }}" data-bootstrap-switch data-on-text="啟用" data-off-text="停用" data-off-color="secondary" data-on-color="success" {{ isset($systemSetting) ? $systemSetting->shippingFees == 1 ? 'checked' : '' : '' }}>
                                        </form>
                                        @else
                                        <input type="checkbox" name="shippingFees" value="{{ isset($systemSetting) && $systemSetting->shippingFees == 1 ? 0 : 1 }}" data-bootstrap-switch data-on-text="啟用" data-off-text="停用" data-off-color="secondary" data-on-color="success" {{ isset($systemSetting) ? $systemSetting->shippingFees == 1 ? 'checked' : '' : '' }} disabled>
                                        @endif
                                    @else
                                        <input type="checkbox" name="shippingFees" value="{{ isset($systemSetting) && $systemSetting->shippingFees == 1 ? 0 : 1 }}" data-bootstrap-switch data-on-text="啟用" data-off-text="停用" data-off-color="secondary" data-on-color="success" {{ isset($systemSetting) ? $systemSetting->shippingFees == 1 ? 'checked' : '' : '' }} disabled>
                                    @endif
                                </div>
                                <div class="col-8">
                                    <div class="float-left d-flex align-items-center">
                                        <ul>
                                            <li>此設定為商品運送方式運費模組。</li>
                                            <li>若未啟用則代表該運送方式的商品不計算運費，啟用則計算運費。</li>
                                            <li>免運門檻設定為該運送方式的商品累計超過免運門檻時，則免除該運送方式的商品運費。</li>
                                            <li>折扣設定則為該運送方式運費折扣，可設定開始與結束時間。<span class="text-danger">(可運用於特殊時段減免運費)</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="float-right">
                                    </div>
                                </div>
                                @if(Auth::user()->role == 'develop')
                                <div class="col-10 offset-1">
                                    <form id="newForm" method="POST" action="{{ route('admin.shippingFees.store') }}" novalidate>
                                        @csrf
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">名稱</span>
                                            </div>
                                            <input type="text" name="name" value="{{ !old('id') ? old('name') : '' }}" class="form-control {{ $errors->has('name') && !old('id') ? 'is-invalid' : '' }}" placeholder="請輸入名稱">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">代碼</span>
                                            </div>
                                            <input type="text" name="type" value="{{ !old('id') ? old('type') : '' }}" class="form-control {{ $errors->has('type') && !old('id') ? 'is-invalid' : '' }}" placeholder="請輸入代碼">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">運費</span>
                                            </div>
                                            <input type="text" name="fee" value="{{ !old('id') ? old('fee') : '' }}" class="form-control {{ $errors->has('fee') && !old('id') ? 'is-invalid' : '' }}" placeholder="請輸入運費">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">免運門檻</span>
                                            </div>
                                            <input type="text" name="free" value="{{ !old('id') ? old('free') : '' }}" class="form-control {{ $errors->has('free') && !old('id') ? 'is-invalid' : '' }}" placeholder="請輸入免運門檻">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">啟用</span>
                                            </div>
                                            <div class="input-group-prepend ml-2 mr-2">
                                                <div class="icheck-success d-inline">
                                                    <input type="radio" name="is_on" value="1" id="is_on1" {{ (!old('id') && old('is_on', '0') == '1') ? 'checked' : '' }}>
                                                    <label for="is_on1">是</label>
                                                </div>
                                                <div class="icheck-secondary d-inline ml-2">
                                                    <input type="radio" name="is_on" value="0" id="is_on2" {{ (!old('id') && old('is_on', '0') == '0') ? 'checked' : '' }}>
                                                    <label for="is_on2">否</label>
                                                </div>
                                            </div>
                                            <div class="input-group-prepend">
                                                <button type="submit" class="btn btn-primary">新增</button>
                                            </div>
                                        </div>
                                        @if ($errors->any() && !old('id'))
                                        <ul class="mt-3 pl-3 small text-danger" style="list-style: disc;">
                                            @foreach ($errors->all() as $error)
                                            <li class="mb-1">{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-left" width="10%">名稱</th>
                                        <th class="text-left" width="10%">代碼</th>
                                        <th class="text-left" width="8%">運費</th>
                                        <th class="text-left" width="10%">免運門檻</th>
                                        <th class="text-left" width="8%">折扣</th>
                                        <th class="text-left" width="15%">折扣開始時間</th>
                                        <th class="text-left" width="15%">折扣結束時間</th>
                                        <th class="text-center" width="8%">啟用</th>
                                        <th class="text-center" width="8%">修改</th>
                                        @if(Auth::user()->role == 'develop')
                                        <th class="text-center" width="8%">刪除</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($settings as $setting)
                                <form id="form-{{ $setting->id }}" action="{{ route('admin.shippingFees.update', $setting->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="id" value="{{ $setting->id }}">
                                    <tr>
                                        <td class="text-left align-middle">
                                            <input class="form-control form-control-sm {{ $errors->has('name') && old('id') == $setting->id ? 'is-invalid' : '' }}" type="text" name="name" value="{{ old('id') == $setting->id ? old('name') : $setting->name }}" readonly>
                                            @if ($errors->has('name') && old('id') == $setting->id)
                                                <div class="text-danger small">{{ $errors->first('name') }}</div>
                                            @endif
                                        </td>
                                        <td class="text-left align-middle">
                                            <input class="form-control form-control-sm {{ $errors->has('type') && old('id') == $setting->id ? 'is-invalid' : '' }}" type="text" name="type" value="{{ old('id') == $setting->id ? old('type') : $setting->type }}" readonly>
                                            @if ($errors->has('type') && old('id') == $setting->id)
                                                <div class="text-danger small">{{ $errors->first('type') }}</div>
                                            @endif
                                        </td>
                                        <td class="text-left align-middle">
                                            <input class="form-control form-control-sm {{ $errors->has('fee') && old('id') == $setting->id ? 'is-invalid' : '' }}" type="text" name="fee" value="{{ old('id') == $setting->id ? old('fee') : $setting->fee }}">
                                            @if ($errors->has('fee') && old('id') == $setting->id)
                                                <div class="text-danger small">{{ $errors->first('fee') }}</div>
                                            @endif
                                        </td>
                                        <td class="text-left align-middle">
                                            <input class="form-control form-control-sm {{ $errors->has('free') && old('id') == $setting->id ? 'is-invalid' : '' }}" type="text" name="free" value="{{ old('id') == $setting->id ? old('free') : $setting->free }}">
                                            @if ($errors->has('free') && old('id') == $setting->id)
                                                <div class="text-danger small">{{ $errors->first('free') }}</div>
                                            @endif
                                        </td>
                                        <td class="text-left align-middle">
                                            <input class="form-control form-control-sm {{ $errors->has('discount') && old('id') == $setting->id ? 'is-invalid' : '' }}" type="text" name="discount" value="{{ old('id') == $setting->id ? old('discount') : $setting->discount }}">
                                            @if ($errors->has('discount') && old('id') == $setting->id)
                                                <div class="text-danger small">{{ $errors->first('discount') }}</div>
                                            @endif
                                        </td>
                                        <td class="text-left align-middle">
                                            <input class="datetimepicker form-control form-control-sm {{ $errors->has('start_time') && old('id') == $setting->id ? 'is-invalid' : '' }}" type="text" name="start_time" value="{{ old('id') == $setting->id ? old('start_time') : $setting->start_time }}">
                                            @if ($errors->has('start_time') && old('id') == $setting->id)
                                                <div class="text-danger small">{{ $errors->first('start_time') }}</div>
                                            @endif
                                        </td>
                                        <td class="text-left align-middle">
                                            <input class="datetimepicker form-control form-control-sm {{ $errors->has('end_time') && old('id') == $setting->id ? 'is-invalid' : '' }}" type="text" name="end_time" value="{{ old('id') == $setting->id ? old('end_time') : $setting->end_time }}">
                                            @if ($errors->has('end_time') && old('id') == $setting->id)
                                                <div class="text-danger small">{{ $errors->first('end_time') }}</div>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="icheck-success d-inline">
                                                <input type="radio" name="is_on" value="1" id="is_on_yes_{{ $setting->id }}" {{ $setting->is_on == 1 ? 'checked' : '' }}>
                                                <label for="is_on_yes_{{ $setting->id }}">是</label>
                                            </div>
                                            <div class="icheck-secondary d-inline ml-2">
                                                <input type="radio" name="is_on" value="0" id="is_on_no_{{ $setting->id }}" {{ $setting->is_on == 0 ? 'checked' : '' }}>
                                                <label for="is_on_no_{{ $setting->id }}">否</label>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            @if(Auth::user()->role == 'develop' || in_array($menuCode.'M', explode(',',Auth::user()->permissions)))
                                                <button type="button" class="btn btn-sm btn-primary modify-btn" data-id="{{ $setting->id }}">
                                                    <i class="far fa-edit"></i>
                                                </button>
                                            @endif
                                        </td>
                                        @if(Auth::user()->role == 'develop')
                                        <td class="text-center align-middle">
                                            <form action="{{ route('admin.shippingFees.destroy', $setting->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger delete-btn">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                        @endif
                                    </tr>
                                </form>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="float-right"><span class="text-danger">*注意：修改資料請記得按右邊修改按鈕。</span></div>
                        </div>
                        <div class="card-footer bg-white">
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
<link rel="stylesheet" href="{{ asset('vendor/jquery-ui/jquery-ui.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/jqueryui-timepicker/dist/jquery-ui-timepicker-addon.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/parsley/parsley.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('vendor/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('vendor/jqueryui-timepicker/dist/jquery-ui-timepicker-addon.min.js') }}"></script>
<script src="{{ asset('vendor/jqueryui-timepicker/dist/i18n/jquery-ui-timepicker-zh-TW.js') }}"></script>
<script src="{{ asset('vendor/parsley/parsley.min.js') }}"></script>
<script src="{{ asset('vendor/parsley/zh_tw.js') }}"></script>
<script>
    (function($) {
        "use strict";

        $('.datetimepicker').datetimepicker({
            timeFormat: "HH:mm:ss",
            dateFormat: "yy-mm-dd",
        });

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

        $('input[data-bootstrap-switch]').on('switchChange.bootstrapSwitch', function (event, state) {
            $(this).parents('form').submit();
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
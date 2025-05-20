@extends('admin.layouts.app')

@section('title', '滿額折扣設定')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            {{-- alert訊息 --}}
            {{-- @include('admin.layouts.alert_message') --}}
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><b>滿額折扣設定</b></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                        <li class="breadcrumb-item active"><a href="{{ url('totalDiscounts') }}">滿額折扣設定</a></li>
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
                                    <form action="{{ route('admin.systemSettings.update', 1) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="totalDiscounts" value="0">
                                        <input type="checkbox" name="totalDiscounts" value="1" data-bootstrap-switch data-on-text="啟用" data-off-text="停用" data-off-color="secondary" data-on-color="success" {{ isset($systemSetting) ? $systemSetting->totalDiscounts == 1 ? 'checked' : '' : '' }}>
                                    </form>
                                </div>
                                <div class="col-8">
                                    <div class="float-left d-flex align-items-center">
                                        <ul>
                                            <li>此設定為訂單滿額折扣模組，當訂單付款金額(包含運費)達到門檻時可折扣金額。</li>
                                            <li>可設定多個門檻，先以滿額數值最大優先判斷。</li>
                                            <li>若滿額金額、折扣金額為 0 或 未啟用則代表該組設定不使用。</li>
                                            <li>可設定折扣開始與折扣結束時間。<span class="text-danger">(可運用於特殊時段活動安排)</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="float-right">
                                    </div>
                                </div>
                                <div class="col-10 offset-1">
                                    <form id="newForm" method="POST" action="{{ route('admin.totalDiscounts.store') }}" novalidate>
                                        @csrf
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">滿額金額</span>
                                            </div>
                                            <input type="text" name="amount" value="{{ !old('id') ? old('amount') ? 0 : 0 : 0 }}" class="form-control {{ $errors->has('amount') && !old('id') ? 'is-invalid' : '' }}" placeholder="請輸入滿額金額">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">折扣金額</span>
                                            </div>
                                            <input type="text" name="discount" value="{{ !old('id') ? old('discount') ? 0 : 0 : 0 }}" class="form-control {{ $errors->has('discount') && !old('id') ? 'is-invalid' : '' }}" placeholder="請輸入折扣金額">
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
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-left" width="10%">滿額金額</th>
                                        <th class="text-left" width="10%">折扣金額</th>
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
                                    <tr>
                                        <td class="text-left align-middle">
                                            <input class="form-control form-control-sm {{ $errors->has('amount') && old('id') == $setting->id ? 'is-invalid' : '' }}" type="text" name="amount" form="form-{{ $setting->id }}" value="{{ old('id') == $setting->id ? old('amount') : $setting->amount }}">
                                            @if ($errors->has('amount') && old('id') == $setting->id)
                                            <div class="text-danger small">{{ $errors->first('amount') }}</div>
                                            @endif
                                        </td>
                                        <td class="text-left align-middle">
                                            <input class="form-control form-control-sm {{ $errors->has('discount') && old('id') == $setting->id ? 'is-invalid' : '' }}" type="text" name="discount" form="form-{{ $setting->id }}" value="{{ old('id') == $setting->id ? old('discount') : $setting->discount }}">
                                            @if ($errors->has('discount') && old('id') == $setting->id)
                                            <div class="text-danger small">{{ $errors->first('discount') }}</div>
                                            @endif
                                        </td>
                                        <td class="text-left align-middle">
                                            <input class="datetimepicker form-control form-control-sm {{ $errors->has('start_time') && old('id') == $setting->id ? 'is-invalid' : '' }}" type="text" name="start_time" form="form-{{ $setting->id }}" value="{{ old('id') == $setting->id ? old('start_time') : $setting->start_time }}">
                                            @if ($errors->has('start_time') && old('id') == $setting->id)
                                            <div class="text-danger small">{{ $errors->first('start_time') }}</div>
                                            @endif
                                        </td>
                                        <td class="text-left align-middle">
                                            <input class="datetimepicker form-control form-control-sm {{ $errors->has('end_time') && old('id') == $setting->id ? 'is-invalid' : '' }}" type="text" name="end_time" form="form-{{ $setting->id }}" value="{{ old('id') == $setting->id ? old('end_time') : $setting->end_time }}">
                                            @if ($errors->has('end_time') && old('id') == $setting->id)
                                            <div class="text-danger small">{{ $errors->first('end_time') }}</div>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="icheck-success d-inline">
                                                <input type="radio" name="is_on" value="1" id="is_on_yes_{{ $setting->id }}" form="form-{{ $setting->id }}" {{ $setting->is_on ? 'checked' : '' }}>
                                                <label for="is_on_yes_{{ $setting->id }}">是</label>
                                            </div>
                                            <div class="icheck-secondary d-inline ml-2">
                                                <input type="radio" name="is_on" value="0" id="is_on_no_{{ $setting->id }}" form="form-{{ $setting->id }}" {{ !$setting->is_on ? 'checked' : '' }}>
                                                <label for="is_on_no_{{ $setting->id }}">否</label>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <form id="form-{{ $setting->id }}" action="{{ route('admin.totalDiscounts.update', $setting->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="id" value="{{ $setting->id }}">
                                                <button type="button" class="btn btn-sm btn-primary modify-btn" data-id="{{ $setting->id }}">
                                                    <i class="far fa-edit"></i>
                                                </button>
                                            </form>
                                        </td>
                                        @if(Auth::user()->role == 'develop')
                                        <td class="text-center align-middle">
                                            <form action="{{ route('admin.totalDiscounts.destroy', $setting->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger delete-btn">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
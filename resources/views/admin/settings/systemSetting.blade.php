@extends('admin.layouts.app')

@section('title', '系統參數設定')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        @include('admin.layouts.alert_message')
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><b>系統參數設定</b></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                        <li class="breadcrumb-item active"><a href="{{ url('systemSettings') }}">系統參數設定</a></li>
                        <li class="breadcrumb-item active">修改系統參數設定</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            @if(isset($systemSetting))
            <form id="myform" action="{{ route('admin.systemSettings.update', $systemSetting->id) }}" method="POST" data-parsley-validate>
                <input type="hidden" name="_method" value="PATCH">
                @csrf
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">系統參數設定</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-2">
                                <label for="name">運費模組</label><br>
                                <input type="hidden" name="shippingFees" value="0">
                                <input type="checkbox" name="shippingFees" value="{{ isset($systemSetting) && $systemSetting->shippingFees == 1 ? 0 : 1 }}" data-bootstrap-switch data-on-text="啟用" data-off-text="停用" data-off-color="secondary" data-on-color="success" {{ isset($systemSetting) ? $systemSetting->shippingFees == 1 ? 'checked' : '' : '' }}>
                                @error('shippingFees')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-2">
                                <label for="name">滿額折扣模組</label><br>
                                <input type="hidden" name="totalDiscounts" value="0">
                                <input type="checkbox" name="totalDiscounts" value="{{ isset($systemSetting) && $systemSetting->totalDiscounts == 1 ? 0 : 1 }}" data-bootstrap-switch data-on-text="啟用" data-off-text="停用" data-off-color="secondary" data-on-color="success" {{ isset($systemSetting) ? $systemSetting->totalDiscounts == 1 ? 'checked' : '' : '' }}>
                                @error('totalDiscounts')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-2">
                                <label for="name">商品限時優惠模組</label><br>
                                <input type="hidden" name="productPromos" value="0">
                                <input type="checkbox" name="productPromos" value="{{ isset($systemSetting) && $systemSetting->productPromos == 1 ? 0 : 1 }}" data-bootstrap-switch data-on-text="啟用" data-off-text="停用" data-off-color="secondary" data-on-color="success" {{ isset($systemSetting) ? $systemSetting->productPromos == 1 ? 'checked' : '' : '' }}>
                                @error('productPromos')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-2">
                                <label>最後修改管理員</label>
                                <br><span><b>{{ !empty($systemSetting->admin) ? $systemSetting->admin->name : '沒有姓名' }}</b> {{ $systemSetting->updated_at }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center bg-white">
                        @if(Auth::user()->role == 'develop' || in_array(isset($systemSetting) ? $menuCode.'M' : $menuCode.'N', explode(',',Auth::user()->permissions)))
                        <button type="button" class="btn btn-primary confirm-btn">{{ isset($systemSetting) ? '修改' : '新增' }}</button>
                        @endif
                        <a href="{{ url('dashboard') }}" class="btn btn-info">
                            <span class="text-white"><i class="fas fa-history"></i> 取消</span>
                        </a>
                    </div>
                </div>
            </form>
            @endif
        </div>
    </section>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/parsley/parsley.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('vendor/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
<script src="{{ asset('vendor/parsley/parsley.min.js') }}"></script>
<script src="{{ asset('vendor/parsley/zh_tw.js') }}"></script>
<script>
(function($) {
    "use strict";

    $("input[data-bootstrap-switch]").each(function() {
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

    $('#myform').parsley({
        errorClass: 'is-invalid',
        successClass: 'is-valid',
        errorsWrapper: '<span class="invalid-feedback d-block" role="alert"></span>',
        errorTemplate: '<span></span>',
        trigger: 'change'
    });

    $(".confirm-btn").click(function() {
        let form = $('#myform');
        if (form.parsley().validate()) {
            if(confirm('本頁面資訊顯示於網站首頁及相關信件，請勿任意修改，\n請確認無誤後再按確定送出。')){
                form.submit();
            }
        }
    });
})(jQuery);
</script>
@endpush
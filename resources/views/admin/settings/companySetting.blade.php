@extends('admin.layouts.app')

@section('title', '公司資料設定')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        @include('admin.layouts.alert_message')
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><b>公司資料設定</b></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">後台管理系統</a></li>
                        <li class="breadcrumb-item active"><a href="{{ url('companySettings') }}">公司資料設定</a></li>
                        <li class="breadcrumb-item active">修改公司資料</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            @if(isset($company))
            <form id="myform" action="{{ route('admin.companySettings.update', $company->id) }}" method="POST" data-parsley-validate>
                <input type="hidden" name="_method" value="PATCH">
                @csrf
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ $company->name ?? '' }} 資料設定</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="name">公司名稱</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $company->name ?? '') }}" required data-parsley-maxlength="255" data-parsley-trigger="change" placeholder="請輸入公司中文名稱">
                                @error('name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="name_en">公司英文名稱</label>
                                <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en', $company->name_en ?? '') }}" data-parsley-maxlength="255" data-parsley-trigger="change" placeholder="請輸入公司英文名稱">
                                @error('name_en')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="brand">品牌名稱</label>
                                <input type="text" class="form-control @error('brand') is-invalid @enderror" id="brand" name="brand" value="{{ old('brand', $company->brand ?? '') }}" required data-parsley-maxlength="255" data-parsley-trigger="change" placeholder="請輸入品牌名稱">
                                @error('brand')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="tax_num">統一編號</label>
                                <input type="text" class="form-control @error('tax_num') is-invalid @enderror" id="tax_num" name="tax_num" value="{{ old('tax_num', $company->tax_num ?? '') }}" required data-parsley-maxlength="20" data-parsley-pattern="^\d{8}$" data-parsley-pattern-message="統一編號應為 8 碼數字" data-parsley-trigger="change" placeholder="請輸入公司統一編號">
                                @error('tax_num')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="tel">電話</label>
                                <input type="text" class="form-control @error('tel') is-invalid @enderror" id="tel" name="tel" value="{{ old('tel', $company->tel ?? '') }}" required data-parsley-maxlength="20" data-parsley-trigger="change" placeholder="請輸入公司電話(含國際碼)">
                                @error('tel')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="fax">傳真</label>
                                <input type="text" class="form-control @error('fax') is-invalid @enderror" id="fax" name="fax" value="{{ old('fax', $company->fax ?? '') }}" required data-parsley-maxlength="20" data-parsley-trigger="change" placeholder="請輸入公司傳真(含國際碼)">
                                @error('fax')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="address">中文地址</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $company->address ?? '') }}" required data-parsley-maxlength="255" data-parsley-trigger="change" placeholder="請輸入公司中文地址">
                                @error('address')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="address_en">英文地址</label>
                                <input type="text" class="form-control @error('address_en') is-invalid @enderror" id="address_en" name="address_en" value="{{ old('address_en', $company->address_en ?? '') }}" data-parsley-maxlength="255" data-parsley-trigger="change" placeholder="請輸入公司英文地址">
                                @error('address_en')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="service_tel">客服電話</label>
                                <input type="text" class="form-control @error('service_tel') is-invalid @enderror" id="service_tel" name="service_tel" value="{{ old('service_tel', $company->service_tel ?? '') }}" data-parsley-maxlength="20" data-parsley-trigger="change" placeholder="請輸入客服電話">
                                @error('service_tel')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="service_email">客服信箱</label>
                                <input type="email" class="form-control @error('service_email') is-invalid @enderror" id="service_email" name="service_email" value="{{ old('service_email', $company->service_email ?? '') }}" data-parsley-type="email" data-parsley-maxlength="255" data-parsley-trigger="change" placeholder="請輸入客服信箱">
                                @error('service_email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="service_time_start">營業開始時間</label>
                                <input type="text" class="form-control @error('service_time_start') is-invalid @enderror" id="service_time_start" name="service_time_start" value="{{ old('service_time_start', $company->service_time_start ?? '') }}" required data-parsley-maxlength="20" data-parsley-trigger="change" placeholder="請輸入營業開始時間, ex: 早上 09:00">
                                @error('service_time_start')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="service_time_end">營業結束時間</label>
                                <input type="text" class="form-control @error('service_time_end') is-invalid @enderror" id="service_time_end" name="service_time_end" value="{{ old('service_time_end', $company->service_time_end ?? '') }}" required data-parsley-maxlength="20" data-parsley-trigger="change" placeholder="請輸入營業結束時間, ex: 下午 18:00">
                                @error('service_time_end')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="website">官網網址(不含https://)</label>
                                <input type="text" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website', $company->website ?? '') }}" required data-parsley-maxlength="255" data-parsley-pattern="^[\w\.-]+(?:\.[\w\.-]+)+(\/[\w\-._~:\/?#\[\]@!$&'()*+,;=.]*)?$" data-parsley-pattern-message="請輸入正確的網址格式" data-parsley-trigger="change" placeholder="請輸入官網網址">
                                @error('website')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="url">官網網址(含https://)</label>
                                <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url', $company->url ?? '') }}" required data-parsley-maxlength="255" data-parsley-type="url" data-parsley-trigger="change" placeholder="請輸入官網網址(含https://)">
                                @error('url')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="fb_url">FB粉絲頁連結</label>
                                <input type="text" class="form-control @error('fb_url') is-invalid @enderror" id="fb_url" name="fb_url" value="{{ old('fb_url', $company->fb_url ?? '') }}" data-parsley-type="url" data-parsley-maxlength="255" data-parsley-trigger="change" placeholder="請輸入FB粉絲頁連結">
                                @error('fb_url')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Instagram_url">Instagram粉絲頁連結</label>
                                <input type="text" class="form-control @error('Instagram_url') is-invalid @enderror" id="Instagram_url" name="Instagram_url" value="{{ old('Instagram_url', $company->Instagram_url ?? '') }}" data-parsley-type="url" data-parsley-maxlength="255" data-parsley-trigger="change" placeholder="請輸入Instagram粉絲頁連結">
                                @error('Instagram_url')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="line">Line ID</label>
                                <input type="text" class="form-control @error('line') is-invalid @enderror" id="line" name="line" value="{{ old('line', $company->line ?? '') }}" data-parsley-maxlength="100" data-parsley-trigger="change" placeholder="請輸入 Line ID">
                                @error('line')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="wechat_id">微信 WeChat ID</label>
                                <input type="text" class="form-control @error('wechat_id') is-invalid @enderror" id="wechat_id" name="wechat_id" value="{{ old('wechat_id', $company->wechat_id ?? '') }}" data-parsley-maxlength="100" data-parsley-trigger="change" placeholder="請輸入微信 Wechat ID">
                                @error('wechat_id')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="lon">經度座標</label>
                                <input type="text" class="form-control @error('lon') is-invalid @enderror" id="lon" name="lon" value="{{ old('lon', $company->lon ?? '') }}" data-parsley-maxlength="50" data-parsley-trigger="change" placeholder="請輸入經度座標">
                                @error('lon')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="lat">緯度座標</label>
                                <input type="text" class="form-control @error('lat') is-invalid @enderror" id="lat" name="lat" value="{{ old('lat', $company->lat ?? '') }}" data-parsley-maxlength="50" data-parsley-trigger="change" placeholder="請輸入緯度座標">
                                @error('lat')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-{{ !empty($company->line_qrcode) ? '2' : '3' }}">
                                <label for="line_qrcode">Line Qr-Code(網址)</label>
                                <input type="text" class="form-control @error('line_qrcode') is-invalid @enderror" id="line_qrcode" name="line_qrcode" value="{{ old('line_qrcode', $company->line_qrcode ?? '') }}" data-parsley-type="url" data-parsley-maxlength="255" data-parsley-trigger="change" placeholder="請輸入 Line QrCode 網址">
                                @error('line_qrcode')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            @if (!empty($company->line_qrcode))
                            <div class="form-group col-md-1">
                                {!! DNS2D::getBarcodeHTML($company->line_qrcode, 'QRCODE',3,3) !!}
                            </div>
                            @endif
                            <div class="form-group col-md-2">
                                <label>最後修改管理員</label>
                                <br><span><b>{{ !empty($company->admin) ? $company->admin->name : '沒有姓名' }}</b> {{ $company->updated_at }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center bg-white">
                        @if(Auth::user()->role == 'develop' || in_array(isset($company) ? $menuCode.'M' : $menuCode.'N', explode(',',Auth::user()->permissions)))
                        <button type="button" class="btn btn-primary confirm-btn">{{ isset($company) ? '修改' : '新增' }}</button>
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
<link rel="stylesheet" href="{{ asset('vendor/parsley/parsley.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('vendor/parsley/parsley.min.js') }}"></script>
<script src="{{ asset('vendor/parsley/zh_tw.js') }}"></script>
<script>
(function($) {
    "use strict";
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
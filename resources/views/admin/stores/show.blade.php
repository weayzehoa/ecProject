@extends('admin.layouts.app')

@section('title', '分店資訊')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        {{-- alert訊息 --}}
        @include('admin.layouts.alert_message')
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><b>分店資訊</b><small> ({{ isset($store) ? '修改' : '新增' }})</small></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('stores') }}">分店資訊</a></li>
                        <li class="breadcrumb-item active">{{ isset($store) ? '修改' : '新增' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if(isset($store))
            <form id="delImgForm" action="{{ route('admin.stores.delimg', $store->id) }}" method="POST">
                @csrf
            </form>
            @endif
            <form id="myform"
                  action="{{ isset($store) ? route('admin.stores.update', $store->id) : route('admin.stores.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  data-parsley-validate>
                @csrf
                @if(isset($store)) @method('PATCH') @endif
                <input type="hidden" name="type" value="store">

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ $store->name ?? '新增' }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-3">
                                {{-- <label for="title"><span class="text-red">*</span> {{ __('validation.attributes.store.title') }}</label> --}}
                                <label for="name"><span class="text-red">*</span> 分店名稱</label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name"
                                       value="{{ old('name', $store->name ?? '') }}"
                                       placeholder="請輸入分店名稱"
                                       required data-parsley-maxlength="30"
                                       data-parsley-trigger="change">
                                @error('name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="tel"><span class="text-red">*</span> 電話</label>
                                <input type="text" class="form-control @error('tel') is-invalid @enderror" id="tel" name="tel" value="{{ old('tel', $store->tel ?? '') }}" required data-parsley-maxlength="20" data-parsley-trigger="change" placeholder="請輸入電話號碼">
                                @error('tel')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="address"><span class="text-red">*</span> 地址</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $store->address ?? '') }}" required data-parsley-maxlength="255" data-parsley-trigger="change" placeholder="請輸入地址">
                                @error('address')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="service_time_start"><span class="text-red">*</span> 營業開始時間</label>
                                <input type="text" class="form-control @error('service_time_start') is-invalid @enderror" id="service_time_start" name="service_time_start" value="{{ old('service_time_start', $store->service_time_start ?? '') }}" required data-parsley-maxlength="20" data-parsley-trigger="change" placeholder="請輸入營業開始時間, ex: 早上 09:00">
                                @error('service_time_start')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="service_time_end"><span class="text-red">*</span> 營業結束時間</label>
                                <input type="text" class="form-control @error('service_time_end') is-invalid @enderror" id="service_time_end" name="service_time_end" value="{{ old('service_time_end', $store->service_time_end ?? '') }}" required data-parsley-maxlength="20" data-parsley-trigger="change" placeholder="請輸入營業結束時間, ex: 下午 18:00">
                                @error('service_time_end')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="fb_url">FB粉絲頁連結</label>
                                <input type="text" class="form-control @error('fb_url') is-invalid @enderror" id="fb_url" name="fb_url" value="{{ old('fb_url', $store->fb_url ?? '') }}" data-parsley-type="url" data-parsley-maxlength="255" data-parsley-trigger="change" placeholder="請輸入FB粉絲頁連結">
                                @error('fb_url')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="ig_url">Instagram粉絲頁連結</label>
                                <input type="text" class="form-control @error('ig_url') is-invalid @enderror" id="ig_url" name="ig_url" value="{{ old('ig_url', $store->ig_url ?? '') }}" data-parsley-type="url" data-parsley-maxlength="255" data-parsley-trigger="change" placeholder="請輸入Instagram粉絲頁連結">
                                @error('ig_url')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="line_id">Line ID</label>
                                <input type="text" class="form-control @error('line_id') is-invalid @enderror" id="line_id" name="line_id" value="{{ old('line_id', $store->line_id ?? '') }}" data-parsley-maxlength="100" data-parsley-trigger="change" placeholder="請輸入 Line ID">
                                @error('line_id')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-{{ !empty($store->line_qrcode) ? '2' : '3' }}">
                                <label for="line_qrcode">Line Qr-Code(網址)</label>
                                <input type="text" class="form-control @error('line_qrcode') is-invalid @enderror" id="line_qrcode" name="line_qrcode" value="{{ old('line_qrcode', $store->line_qrcode ?? '') }}" data-parsley-type="url" data-parsley-maxlength="255" data-parsley-trigger="change" placeholder="請輸入 Line QrCode 網址">
                                @error('line_qrcode')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="lon">經度座標</label>
                                <input type="text" class="form-control @error('lon') is-invalid @enderror" id="lon" name="lon" value="{{ old('lon', $store->lon ?? '') }}" data-parsley-maxlength="50" data-parsley-trigger="change" placeholder="請輸入經度座標">
                                @error('lon')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="lat">緯度座標</label>
                                <input type="text" class="form-control @error('lat') is-invalid @enderror" id="lat" name="lat" value="{{ old('lat', $store->lat ?? '') }}" data-parsley-maxlength="50" data-parsley-trigger="change" placeholder="請輸入緯度座標">
                                @error('lat')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="img">圖片 (@if($imageSetting->width > 0)上傳圖片寬度超過 {{ $imageSetting->width }}px，將會等比例自動調整@endif)</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file"
                                                       class="custom-file-input @error('img') is-invalid @enderror"
                                                       id="img" name="img"
                                                       accept="image/*"
                                                       data-parsley-filemaxmegabytes="5"
                                                       data-parsley-filemimetypes="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml">
                                                <label class="custom-file-label" for="img" id="img-label">
                                                    {{ old('img', isset($store) && $store->img ? basename($store->img) : __('選擇圖片')) }}
                                                </label>
                                            </div>
                                            @if(isset($store) && !empty($store->img))
                                            <div class="input-group-append">
                                                <span class="input-group-text text-danger btn del-img"><i class="fas fa-trash-alt"></i></span>
                                            </div>
                                            @endif
                                        </div>

                                        {{-- Laravel 後端錯誤訊息，強制顯示在 form-group 下 --}}
                                        @if ($errors->has('img'))
                                            <div class="invalid-feedback d-block mt-1" role="alert">
                                                <strong>{{ $errors->first('img') }}</strong>
                                            </div>
                                        @endif

                                        {{-- 已存在圖片（編輯時） --}}
                                        @if(isset($store) && $store->img)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/upload/' . $store->img) }}" width="400" alt="{{ $store->title }}" class="border rounded old-image">
                                        </div>
                                        @endif

                                        {{-- 圖片預覽（上傳後） --}}
                                        <div id="img-preview-wrapper" class="mt-2" style="display: none;">
                                            <img id="img-preview" src="#" width="400" alt="預覽圖片" class="border rounded">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-center bg-white">
                        @if((isset($store) && in_array($menuCode.'M',explode(',',Auth::user()->permissions))) || in_array($menuCode.'N',explode(',',Auth::user()->permissions)))
                        <button id="modifyBtn" type="button" class="btn btn-primary">{{ isset($store) ? '修改' : '新增' }}</button>
                        @endif
                        <a href="{{ url('stores') }}" class="btn btn-info">
                            <span class="text-white"><i class="fas fa-history"></i> 取消</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/parsley/parsley.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/jquery-ui/jquery-ui.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/jqueryui-timepicker/dist/jquery-ui-timepicker-addon.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('vendor/parsley/parsley.min.js') }}"></script>
<script src="{{ asset('vendor/parsley/zh_tw.js') }}"></script>
<script src="{{ asset('vendor/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('vendor/jqueryui-timepicker/dist/jquery-ui-timepicker-addon.min.js') }}"></script>
<script src="{{ asset('vendor/jqueryui-timepicker/dist/i18n/jquery-ui-timepicker-zh-TW.js') }}"></script>

<script>
    // 保存原始圖片預覽與檔名
    const originalImgSrc = '{{ isset($store) && $store->img ? asset("storage/upload/" . $store->img) : "" }}';
    const originalImgName = '{{ isset($store) && $store->img ? basename($store->img) : __("選擇圖片") }}';

    // 日期時間選擇器
    $('.datetimepicker').datetimepicker({
        timeFormat: "HH:mm:ss",
        dateFormat: "yy-mm-dd",
    });

    $('#img').on('change', function () {
        const fileInput = this;
        const file = fileInput.files[0];
        const label = $('#img-label');

        if (file && file.type.startsWith('image/')) {
            // 設定新的檔名
            label.text(file.name).addClass('selected');

            // 預覽新圖片
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#img-preview').attr('src', e.target.result);
                $('#img-preview-wrapper').show();
                $('img.old-image').hide(); // 隱藏舊圖
            };
            reader.readAsDataURL(file);
        } else {
            // 如果使用者取消選取檔案（或不合法），還原原始圖片與檔名
            fileInput.value = '';
            label.text(originalImgName).removeClass('selected');
            $('#img-preview-wrapper').hide();
            $('#img-preview').attr('src', '#');
            $('img.old-image').show(); // 顯示舊圖
        }
    });

    // 自定 Parsley 驗證器
    window.Parsley.addValidator('filemaxmegabytes', {
        requirementType: 'number',
        validateString: function (_value, maxMb, parsleyInstance) {
            const files = parsleyInstance.$element[0].files;
            return files.length === 0 || files[0].size <= maxMb * 1048576;
        },
        messages: {
            zh_tw: "{{ __('validation.attributes.store.messages.img.filemaxmegabytes', ['max' => 5]) }}",
            zh_cn: "{{ __('validation.attributes.store.messages.img.filemaxmegabytes', ['max' => 5]) }}",
            en: "{{ __('validation.attributes.store.messages.img.filemaxmegabytes', ['max' => 5]) }}",
        }
    });

    window.Parsley.addValidator('filemimetypes', {
        requirementType: 'string',
        validateString: function (_value, types, parsleyInstance) {
            const files = parsleyInstance.$element[0].files;
            if (files.length === 0) return true;
            const allowed = types.split(',');
            return allowed.includes(files[0].type);
        },
        messages: {
            zh_tw: "{{ __('validation.attributes.store.messages.img.filemimetypes', ['types' => 'jpeg, png, jpg, gif, svg']) }}",
            zh_cn: "{{ __('validation.attributes.store.messages.img.filemimetypes', ['types' => 'jpeg, png, jpg, gif, svg']) }}",
            en: "{{ __('validation.attributes.store.messages.img.filemimetypes', ['types' => 'jpeg, png, jpg, gif, svg']) }}",
        }
    });

    // Parsley 初始化
    $('#myform').parsley({
        errorClass: 'is-invalid',
        successClass: 'is-valid',
        errorsWrapper: '<div class="invalid-feedback d-block" role="alert"></div>',
        errorTemplate: '<span></span>',
        trigger: 'change',
        errorsContainer: function (ParsleyField) {
            if (ParsleyField.$element.is(':file')) {
                return ParsleyField.$element.closest('.form-group');
            }
            return ParsleyField.$element.parent();
        }
    });

    // 提交按鈕觸發驗證
    $('#modifyBtn').click(function () {
        const form = $('#myform');
        if (form.parsley().validate()) {
            $(this).attr('disabled', true);
            form.submit();
        } else {
            $(this).attr('disabled', false);
        }
    });

    $('.del-img').click(function(){
        if(confirm('請確認是否要刪除這張圖片?')){
            $('#delImgForm').submit();
        };
    });
</script>
@endpush
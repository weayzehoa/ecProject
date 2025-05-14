@extends('admin.layouts.app')

@section('title', '輪播管理')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        {{-- alert訊息 --}}
        @include('admin.layouts.alert_message')
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><b>輪播管理</b><small> ({{ isset($banner) ? '修改' : '新增' }})</small></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('banners') }}">輪播管理</a></li>
                        <li class="breadcrumb-item active">{{ isset($banner) ? '修改' : '新增' }}輪播</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if(isset($banner))
            <form id="delImgForm" action="{{ route('admin.banners.delimg', $banner->id) }}" method="POST">
                @csrf
            </form>
            @endif
            <form id="myform"
                  action="{{ isset($banner) ? route('admin.banners.update', $banner->id) : route('admin.banners.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  data-parsley-validate>
                @csrf
                @if(isset($banner)) @method('PATCH') @endif
                <input type="hidden" name="type" value="banner">

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ $banner->title ?? '新增' }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                {{-- <label for="title"><span class="text-red">*</span> {{ __('validation.attributes.banner.title') }}</label> --}}
                                <label for="title"><span class="text-red">*</span> 標題</label>
                                <input type="text"
                                       class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title"
                                       value="{{ old('title', $banner->title ?? '') }}"
                                       placeholder="請輸入標題"
                                       required data-parsley-maxlength="30"
                                       data-parsley-trigger="change">
                                @error('title')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="description">描述</label>
                                <input type="text"
                                       class="form-control @error('description') is-invalid @enderror"
                                       id="description" name="description"
                                       value="{{ old('description', $banner->description ?? '') }}"
                                       placeholder="請輸入描述"
                                       data-parsley-maxlength="120" data-parsley-trigger="change">
                                @error('description')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="url">連結</label>
                                <input type="url"
                                       class="form-control @error('url') is-invalid @enderror"
                                       id="url" name="url"
                                       value="{{ old('url', $banner->url ?? '') }}"
                                       placeholder="請輸入連結，EX: https://www.example.com"
                                       data-parsley-type="url" data-parsley-maxlength="255" data-parsley-trigger="change">
                                @error('url')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="start_time">開始時間</label>
                                        <input type="text"
                                               class="form-control datetimepicker @error('start_time') is-invalid @enderror"
                                               id="start_time" name="start_time"
                                               value="{{ old('start_time', $banner->start_time ?? '') }}"
                                               placeholder="請輸入開始時間，ex: 2025-01-01 00:00:00"
                                               data-parsley-pattern="^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$">
                                        @error('start_time')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="end_time">結束時間</label>
                                        <input type="text"
                                               class="form-control datetimepicker @error('end_time') is-invalid @enderror"
                                               id="end_time" name="end_time"
                                               value="{{ old('end_time', $banner->end_time ?? '') }}"
                                               placeholder="請輸入結束時間，ex:2025-12-31 23:59:59"
                                               data-parsley-pattern="^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$">
                                        @error('end_time')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="img">輪播圖片 (@if($imageSetting->height > 0)上傳圖片高度超過 {{ $imageSetting->height }}px，將會等比例自動調整@endif)</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file"
                                                       class="custom-file-input @error('img') is-invalid @enderror"
                                                       id="img" name="img"
                                                       accept="image/*"
                                                       data-parsley-filemaxmegabytes="5"
                                                       data-parsley-filemimetypes="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml">
                                                <label class="custom-file-label" for="img" id="img-label">
                                                    {{ old('img', isset($banner) && $banner->img ? basename($banner->img) : __('選擇圖片')) }}
                                                </label>
                                            </div>
                                            @if(isset($banner) && !empty($banner->img))
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
                                        @if(isset($banner) && $banner->img)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/upload/' . $banner->img) }}" width="400" alt="{{ $banner->title }}" class="border rounded old-image">
                                        </div>
                                        @endif

                                        {{-- 圖片預覽（上傳後） --}}
                                        <div id="img-preview-wrapper" class="mt-2" style="display: none;">
                                            <img id="img-preview" src="#" width="400" alt="預覽圖片" class="border rounded">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="content">內容說明</label>
                                <textarea class="form-control @error('content') is-invalid @enderror"
                                          id="content" name="content" rows="5"
                                          placeholder="請輸入內容說明"
                                          data-parsley-maxlength="255"
                                          data-parsley-trigger="change">{{ old('content', $banner->content ?? '') }}</textarea>
                                @error('content')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-center bg-white">
                        @if(Auth::user()->role == 'develop' || (isset($banner) && in_array($menuCode.'M',explode(',',Auth::user()->permissions))) || in_array($menuCode.'N',explode(',',Auth::user()->permissions)))
                        <button id="modifyBtn" type="button" class="btn btn-primary">{{ isset($banner) ? '修改' : '新增' }}</button>
                        @endif
                        <a href="{{ url('banners') }}" class="btn btn-info">
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
    const originalImgSrc = '{{ isset($banner) && $banner->img ? asset("storage/upload/" . $banner->img) : "" }}';
    const originalImgName = '{{ isset($banner) && $banner->img ? basename($banner->img) : __("選擇圖片") }}';

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
            zh_tw: "{{ __('validation.attributes.banner.messages.img.filemaxmegabytes', ['max' => 5]) }}",
            zh_cn: "{{ __('validation.attributes.banner.messages.img.filemaxmegabytes', ['max' => 5]) }}",
            en: "{{ __('validation.attributes.banner.messages.img.filemaxmegabytes', ['max' => 5]) }}",
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
            zh_tw: "{{ __('validation.attributes.banner.messages.img.filemimetypes', ['types' => 'jpeg, png, jpg, gif, svg']) }}",
            zh_cn: "{{ __('validation.attributes.banner.messages.img.filemimetypes', ['types' => 'jpeg, png, jpg, gif, svg']) }}",
            en: "{{ __('validation.attributes.banner.messages.img.filemimetypes', ['types' => 'jpeg, png, jpg, gif, svg']) }}",
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
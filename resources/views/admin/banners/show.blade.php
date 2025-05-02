@extends('admin.layouts.app')

@section('title', '輪播管理')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
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
                                <label for="title"><span class="text-red">*</span> {{ __('validation.attributes.banner.title') }}</label>
                                <input type="text"
                                       class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title"
                                       value="{{ old('title', $banner->title ?? '') }}"
                                       placeholder="{{ __('請輸入') . __('validation.attributes.banner.title') }}"
                                       required data-parsley-maxlength="30"
                                       data-parsley-trigger="change">
                                @error('title')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="description">{{ __('validation.attributes.banner.description') }}</label>
                                <input type="text"
                                       class="form-control @error('description') is-invalid @enderror"
                                       id="description" name="description"
                                       value="{{ old('description', $banner->description ?? '') }}"
                                       placeholder="{{ __('請輸入') . __('validation.attributes.banner.description') }}"
                                       data-parsley-maxlength="120" data-parsley-trigger="change">
                                @error('description')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="url">{{ __('validation.attributes.banner.url') }}</label>
                                <input type="url"
                                       class="form-control @error('url') is-invalid @enderror"
                                       id="url" name="url"
                                       value="{{ old('url', $banner->url ?? '') }}"
                                       placeholder="https://www.example.com"
                                       data-parsley-type="url" data-parsley-maxlength="255" data-parsley-trigger="change">
                                @error('url')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="start_time">{{ __('validation.attributes.banner.start_time') }}</label>
                                        <input type="text"
                                               class="form-control datetimepicker @error('start_time') is-invalid @enderror"
                                               id="start_time" name="start_time"
                                               value="{{ old('start_time', $banner->start_time ?? '') }}"
                                               placeholder="2025-01-01 00:00:00"
                                               data-parsley-pattern="^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$">
                                        @error('start_time')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="end_time">{{ __('validation.attributes.banner.end_time') }}</label>
                                        <input type="text"
                                               class="form-control datetimepicker @error('end_time') is-invalid @enderror"
                                               id="end_time" name="end_time"
                                               value="{{ old('end_time', $banner->end_time ?? '') }}"
                                               placeholder="2025-12-31 23:59:59"
                                               data-parsley-pattern="^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$">
                                        @error('end_time')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="img">{{ __('validation.attributes.banner.img') }}</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file"
                                                       class="custom-file-input @error('img') is-invalid @enderror"
                                                       id="img" name="img"
                                                       accept="image/*"
                                                       data-parsley-filemaxmegabytes="2"
                                                       data-parsley-filemimetypes="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml">
                                                <label class="custom-file-label" for="img">{{ __('選擇圖片') }}</label>
                                            </div>
                                        </div>
                                        @error('img')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                        @if(isset($banner) && $banner->img)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/upload/' . $banner->img) }}" width="400" alt="{{ $banner->title }}">
                                            </div>
                                        @endif
                                        <div id="img-preview-wrapper" class="mt-2" style="display: none;">
                                            <img id="img-preview" src="#" width="400" alt="預覽圖片" class="border rounded">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="content">{{ __('validation.attributes.banner.content') }}</label>
                                <textarea class="form-control @error('content') is-invalid @enderror"
                                          id="content" name="content" rows="5"
                                          placeholder="{{ __('請輸入') . __('validation.attributes.banner.content') }}"
                                          data-parsley-maxlength="255"
                                          data-parsley-trigger="change">{{ old('content', $banner->content ?? '') }}</textarea>
                                @error('content')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-center bg-white">
                        <button id="modifyBtn" type="button" class="btn btn-primary">{{ isset($banner) ? '修改' : '新增' }}</button>
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
    // 日期時間選擇器
    $('.datetimepicker').datetimepicker({
        timeFormat: "HH:mm:ss",
        dateFormat: "yy-mm-dd",
    });

    // 顯示選擇的圖片檔名
    $('#img').on('change', function () {
        const file = this.files[0];

        // 顯示檔名
        const fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);

        // 預覽圖片
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#img-preview').attr('src', e.target.result);
                $('#img-preview-wrapper').show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#img-preview-wrapper').hide();
            $('#img-preview').attr('src', '#');
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
            zh_tw: "{{ __('validation.attributes.banner.messages.img.filemaxmegabytes', ['max' => 2]) }}",
            zh_cn: "{{ __('validation.attributes.banner.messages.img.filemaxmegabytes', ['max' => 2]) }}",
            en: "{{ __('validation.attributes.banner.messages.img.filemaxmegabytes', ['max' => 2]) }}",
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
            // 若為 file 欄位，顯示在 form-group 下
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
</script>
@endpush
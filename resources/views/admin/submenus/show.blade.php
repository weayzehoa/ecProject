@extends('admin.layouts.app')

@section('title', '選單功能設定')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        {{-- alert訊息 --}}
        @include('admin.layouts.alert_message')
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><b>次選單功能設定</b><small> ({{ isset($subMenu) ? '修改' : '新增' }})</small></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                        <li class="breadcrumb-item active"><a href="{{ url('mainmenus') }}">次選單功能設定</a></li>
                        <li class="breadcrumb-item active">{{ isset($subMenu) ? '修改' : '新增' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        @if(isset($subMenu))
        <form id="myform" action="{{ route('admin.submenus.update', $subMenu->id) }}" method="POST">
            <input type="hidden" name="_method" value="PATCH">
        @else
        <form id="myform" action="{{ route('admin.submenus.store') }}" method="POST">
        @endif
            @csrf
            <input type="hidden" name="mainmenu_id" value="{{ $mainMenu->id }}">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">{{ $subMenu->name ?? '新增'.$mainMenu->name }}次選單資料</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>所屬主選單名稱：{{ $mainMenu->name }}</label>
                                        </div>
                                        <div class="form-group">
                                            <label for="account"><span class="text-red">* </span>次選單名稱</label>
                                            <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" name="name" value="{{ $subMenu->name ?? '' }}" placeholder="請輸次選單名稱">
                                            @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="fa5icon">Font Awesome 5 圖示 (範例: <span class="text-danger">&lt;i class="nav-icon fas fa-door-open text-danger"&gt;&lt;/i&gt;</span>)</label>
                                            <input type="text" class="form-control {{ $errors->has('fa5icon') ? ' is-invalid' : '' }}" id="fa5icon" name="fa5icon" value="{{ $subMenu->fa5icon ?? ''}}" placeholder="請輸入Font Awesome 5圖示完整語法，可在class中加上其他語法">
                                            @if ($errors->has('fa5icon'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('fa5icon') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="url_type"><span class="text-red">* </span>連結類型</label>
                                            <select class="form-control select2bs4 select2-primary {{ $errors->has('url_type') ? ' is-invalid' : '' }}" data-dropdown-css-class="select2-primary" name="url_type">
                                                <option value="1" {{ isset($subMenu) ? $subMenu->url_type == 1 ? 'selected' : '' : ''}}>內部連結</option>
                                                <option value="2" {{ isset($subMenu) ? $subMenu->url_type == 2 ? 'selected' : '' : ''}}>外部連結</option>
                                            </select>
                                            @if ($errors->has('url_type'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('url_type') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="url">連結 (選擇外部連結類型請輸入完整連結 http:// or https://)</label>
                                            <input type="text" class="form-control {{ $errors->has('url') ? ' is-invalid' : '' }}" id="url" name="url" value="{{ $subMenu->url ?? '' }}" placeholder="請輸入連結">
                                            @if ($errors->has('url'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('url') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="url">提供的功能</label>
                                            <div class="row col-md-12">
                                            @foreach($poweractions as $poweraction)
                                            @if(in_array($poweraction->code, explode(',',$subMenu->power_action)))
                                            <div class="icheck-primary mr-2">
                                                <input type="checkbox" id="pachkbox{{ $poweraction->id }}" name="power_action[]" value="{{ $poweraction->code }}" {{ isset($subMenu) ? in_array($poweraction->code, explode(',',$subMenu->power_action)) ? 'checked' : '' : '' }} disabled>
                                                <label for="pachkbox{{ $poweraction->id }}">{{ $poweraction->name }}({{ $poweraction->code }})</label>
                                            </div>
                                            @endif
                                            @endforeach
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-6">
                                                <label for="open_window">另開視窗</label>
                                                <div class="input-group">
                                                    <input type="checkbox" name="open_window" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="success" {{ isset($subMenu) ? $subMenu->open_window == 1 ? 'checked' : '' : '' }}>
                                                </div>
                                            </div>
                                            <div class="form-group col-6">
                                                <label for="is_on">啟用狀態</label>
                                                <div class="input-group">
                                                    <input type="checkbox" name="is_on" value="1" data-bootstrap-switch data-off-color="secondary" data-on-color="primary" {{ isset($subMenu) ? $subMenu->is_on == 1 ? 'checked' : '' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-center bg-white">
                                @if(in_array(isset($subMenu) ? $menuCode.'M' : $menuCode.'S2N', explode(',',Auth::user()->power)))
                                <button type="submit" class="btn btn-primary">{{ isset($subMenu) ? '修改' : '新增' }}</button>
                                @endif
                                <a href="{{ url('mainmenus/submenu/'.$mainMenu->id) }}" class="btn btn-info">
                                    <span class="text-white"><i class="fas fa-history"></i> 取消</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
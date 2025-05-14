@extends('admin.layouts.app')

@section('title', '最新消息')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        @include('admin.layouts.alert_message')
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><b>最新消息</b><small> ({{ isset($new) ? '修改' : '新增' }})</small></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('news') }}">最新消息</a></li>
                        <li class="breadcrumb-item active">{{ isset($new) ? '修改' : '新增' }}消息內容</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <form id="myform"
                  action="{{ isset($new) ? route('admin.news.update', $new->id) : route('admin.news.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  data-parsley-validate>
                @csrf
                @if(isset($new)) @method('PATCH') @endif
                <input type="hidden" name="type" value="news">

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ $new->title ?? '新增' }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="title"><span class="text-red">*</span> 標題</label>
                                <input type="text"
                                       class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title"
                                       value="{{ old('title', $new->title ?? '') }}"
                                       placeholder="請輸入標題"
                                       required data-parsley-maxlength="30"
                                       data-parsley-trigger="change">
                                @error('title')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label for="start_time">開始時間</label>
                                <input type="text"
                                       class="form-control datetimepicker @error('start_time') is-invalid @enderror"
                                       id="start_time" name="start_time"
                                       value="{{ old('start_time', $new->start_time ?? '') }}"
                                       placeholder="請輸入開始時間，ex: 2025-01-01 00:00:00"
                                       data-parsley-pattern="^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$">
                                @error('start_time')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="end_time">結束時間</label>
                                <input type="text"
                                       class="form-control datetimepicker @error('end_time') is-invalid @enderror"
                                       id="end_time" name="end_time"
                                       value="{{ old('end_time', $new->end_time ?? '') }}"
                                       placeholder="請輸入結束時間，ex:2025-12-31 23:59:59"
                                       data-parsley-pattern="^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$">
                                @error('end_time')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="content">消息內容</label>
                                <textarea class="ckeditor form-control @error('content') is-invalid @enderror"
                                          id="content" name="content" rows="10"
                                          placeholder="請輸入消息內容">{{ old('content', $new->content ?? '') }}</textarea>
                                @error('content')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-center bg-white">
                        @if(Auth::user()->role == 'develop' || (isset($new) && in_array($menuCode.'M',explode(',',Auth::user()->permissions))) || in_array($menuCode.'N',explode(',',Auth::user()->permissions)))
                        <button id="modifyBtn" type="button" class="btn btn-primary">{{ isset($new) ? '修改' : '新增' }}</button>
                        @endif
                        <a href="{{ url('news') }}" class="btn btn-info">
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
<script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('js/ckeditor-setup.js') }}"></script>
<script>
    //class=ckeditor 啟動CKEditor
    initCKEditor('{{ csrf_token() }}', '{{ route("admin.ckeditorUpload") }}', '{{ route("admin.ckeditorDelete") }}');

    $('.datetimepicker').datetimepicker({
        timeFormat: "HH:mm:ss",
        dateFormat: "yy-mm-dd",
    });

    $('#myform').parsley({
        errorClass: 'is-invalid',
        successClass: 'is-valid',
        errorsWrapper: '<div class="invalid-feedback d-block" role="alert"></div>',
        errorTemplate: '<span></span>',
        trigger: 'change',
        errorsContainer: function (ParsleyField) {
            return ParsleyField.$element.closest('.form-group');
        }
    });

    $('#modifyBtn').click(function () {
        const form = $('#myform');
        $('#content').val(CKEDITOR.instances.content.getData());
        if (form.parsley().validate()) {
            $(this).attr('disabled', true);
            form.submit();
        } else {
            $(this).attr('disabled', false);
        }
    });
</script>
@endpush
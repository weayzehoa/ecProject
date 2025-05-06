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
                                <textarea class="form-control @error('content') is-invalid @enderror"
                                          id="content" name="content" rows="10"
                                          placeholder="請輸入消息內容">{{ old('content', $new->content ?? '') }}</textarea>
                                @error('content')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-center bg-white">
                        @if((isset($new) && in_array($menuCode.'M',explode(',',Auth::user()->permissions))) || in_array($menuCode.'N',explode(',',Auth::user()->permissions)))
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
<script>
    // 初始化 CKEditor
    CKEDITOR.replace('content', {
        height: 300,
        language: 'zh',

        //開啟上傳功能
        filebrowserUploadUrl: '{{ route("admin.ckeditorUpload") }}',
        filebrowserUploadMethod: 'form',

        // 啟用拖拉上傳圖片
        extraPlugins: 'uploadimage,uploadfile',
        filebrowserUploadUrl: '{{ route("admin.ckeditorUpload") }}?_token={{ csrf_token() }}',
        removeDialogTabs: 'link:upload;image:Upload',
    });

    CKEDITOR.on('instanceReady', function (evt) {
        const editor = evt.editor;

        // 加入右鍵選單項目
        editor.addMenuGroup('customGroup');
        editor.addMenuItem('deleteImageItem', {
            label: '刪除圖片',
            icon: CKEDITOR.basePath + 'plugins/delete/icons/delete.png', // 可自訂圖示
            command: 'deleteImage',
            group: 'customGroup',
        });

        editor.contextMenu.addListener(function (element) {
            if (element.getName() === 'img' && element.getAttribute('src').includes('/storage/upload/ckeditor/')) {
                return { deleteImageItem: CKEDITOR.TRISTATE_OFF };
            }
        });

        editor.addCommand('deleteImage', {
            exec: function (editor) {
                const selection = editor.getSelection();
                const element = selection.getStartElement();
                if (element && element.getName() === 'img') {
                    const imgUrl = element.getAttribute('src');
                    confirmAndDeleteImage(imgUrl, () => {
                        element.remove(); // 刪除圖片 DOM
                    });
                }
            }
        });

        // 鍵盤刪除行為（Delete 鍵）
        editor.on('contentDom', function () {
            const editable = editor.editable();
            editable.attachListener(editable, 'keydown', function (e) {
                if (e.data.$.key === 'Delete') {
                    const selection = editor.getSelection();
                    const element = selection.getStartElement();
                    if (element && element.getName() === 'img') {
                        const imgUrl = element.getAttribute('src');
                        confirmAndDeleteImage(imgUrl, () => {
                            element.remove();
                        });
                    }
                }
            });
        });
    });

    function confirmAndDeleteImage(imgUrl, onSuccess) {
        if (!imgUrl.includes('/storage/upload/ckeditor/')) return;

        if (!confirm('確定要刪除此圖片嗎？這將會永久刪除伺服器上的圖片檔案。')) {
            return;
        }

        fetch('{{ route("admin.ckeditorDelete") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ url: imgUrl })
        })
        .then(response => response.json())
        .then(data => {
            if (data.deleted) {
                if (typeof onSuccess === 'function') onSuccess();
            } else {
                alert('圖片刪除失敗，請稍後再試。');
            }
        })
        .catch(() => {
            alert('圖片刪除過程中發生錯誤。');
        });
    }

    // CKEditor 內容寫回 textarea 給 Parsley 用
    CKEDITOR.instances.content.on('change', function () {
        $('#content').val(CKEDITOR.instances.content.getData()).trigger('change');
    });

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
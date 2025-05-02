@extends('admin.layouts.app')

@section('title', '管理員帳號管理')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        {{-- alert訊息 --}}
        {{-- @include('admin.layouts.alert_message') --}}
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><b>管理員帳號</b><small> ({{ isset($admin) ? '修改' : '新增' }})</small></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                        <li class="breadcrumb-item active"><a href="{{ url('admins') }}">管理員帳號</a></li>
                        <li class="breadcrumb-item active">{{ isset($admin) ? '修改' : '新增' }}管理員帳號</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <form id="myform" action="{{ isset($admin) ? route('admin.admins.update', $admin->id) : route('admin.admins.store') }}" method="POST" data-parsley-validate>
                @csrf
                @if(isset($admin))
                <input type="hidden" name="_method" value="PATCH">
                @endif
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ $admin->name ?? '新增' }} 帳號資料</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="email"><span class="text-red">* </span>EMail帳號</label>
                                @if(isset($admin) && !in_array(Auth::user()->role,['develop','admin']))
                                <input type="hidden" name="email" value="{{ $admin->email }}">
                                <input type="email" class="form-control" value="{{ $admin->email }}" disabled>
                                @else
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       placeholder="請輸入電子郵件"
                                       value="{{ old('email', $admin->email ?? '') }}"
                                       data-parsley-type="email"
                                       data-parsley-trigger="change"
                                       required>
                                @endif
                                @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="name"><span class="text-red">* </span>姓名</label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $admin->name ?? '') }}"
                                       placeholder="請輸入姓名"
                                       required
                                       data-parsley-trigger="change">
                                @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="password"><span class="text-red">* </span>密碼</label>
                                <input type="text"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       value="{{ old('password') }}"
                                       placeholder="{{ isset($admin) ? '不修改請留空白' : '請輸入密碼' }}"
                                       {{ isset($admin) ? '' : 'required' }}
                                       data-parsley-minlength="6"
                                       data-parsley-trigger="change">
                                @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="mobile"><span class="text-red">* </span>行動電話號碼</label>
                                <input type="text"
                                        class="form-control @error('mobile') is-invalid @enderror"
                                        id="mobile"
                                        name="mobile"
                                        value="{{ old('mobile', $admin->mobile ?? '') }}"
                                        placeholder="請輸入行動電話號碼"
                                        required
                                        data-parsley-pattern="^09\d{2}-?\d{3}-?\d{3}$"
                                        data-parsley-pattern-message="請輸入正確的手機號碼，例如 0928123456 或 0928-123-456"
                                        data-parsley-trigger="change">
                                @error('mobile')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="tel">聯絡電話</label>
                                <input type="text"
                                        class="form-control @error('tel') is-invalid @enderror"
                                        id="tel"
                                        name="tel"
                                        value="{{ old('tel', $admin->tel ?? '') }}"
                                        placeholder="請輸入聯絡電話"
                                        data-parsley-trigger="change">
                                @error('tel')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="role">角色群組</label>
                                @if(isset($admin) && !in_array(Auth::user()->role,['develop','admin']))
                                <input type="hidden" name="role" value="{{ $admin->role }}">
                                <select class="form-control" disabled>
                                    @foreach($roles as $key => $value)
                                        @if(auth('admin')->user()->role == 'develop' || $key != 'develop')
                                        <option value="{{ $key }}" {{ old('role', $admin->role ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @else
                                <select class="form-control @error('role') is-invalid @enderror"
                                    id="role" name="role" required data-parsley-trigger="change">
                                    @foreach($roles as $key => $value)
                                        @if(auth('admin')->user()->role == 'develop' || $key != 'develop')
                                        <option value="{{ $key }}" {{ old('role', $admin->role ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @endif
                                @error('role')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="active"><span class="text-red">* </span>狀態</label>
                                @if(isset($admin) && !in_array($admin->role,['develop','admin']))
                                <input type="hidden" name="is_on" value="{{ $admin->is_on }}">
                                <div class="form-group clearfix">
                                    <div class="icheck-green d-inline mr-2">
                                        <input type="radio" id="active_pass" name="is_on" value="1" {{ isset($admin) ? $admin->is_on == 1 ? 'checked' : '' : 'checked' }} disabled>
                                        <label for="active_pass">啟用</label>
                                    </div>
                                    <div class="icheck-danger d-inline mr-2">
                                        <input type="radio" id="active_denie" name="is_on" value="0" {{ isset($admin) ? $admin->is_on == 0 ? 'checked' : '' : '' }} disabled>
                                        <label for="active_denie">停權</label>
                                    </div>
                                </div>
                                @else
                                <div class="form-group clearfix">
                                    <div class="icheck-green d-inline mr-2">
                                        <input type="radio" id="active_pass" name="is_on" value="1" {{ isset($admin) ? $admin->is_on == 1 ? 'checked' : '' : 'checked' }}>
                                        <label for="active_pass">啟用</label>
                                    </div>
                                    <div class="icheck-danger d-inline mr-2">
                                        <input type="radio" id="active_denie" name="is_on" value="0" {{ isset($admin) ? $admin->is_on == 0 ? 'checked' : '' : '' }}>
                                        <label for="active_denie">停權</label>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @if(isset($admin) && !in_array(Auth::user()->role,['develop','admin']))
                        <input type="hidden" name="permissions" value="{{ $admin->permissions }}">
                        @else
                        @if(isset($admin) && isset($menuCode) && in_array($menuCode.'M' , explode(',',Auth::user()->permissions)))
                        <div class="card-primary card-outline col-12 mb-2"></div>
                        <div class="col-md-12">
                            <label for="">權限設定</label>
                            <div class="icheck-primary">
                                <input type="checkbox" id="checkAll" onclick="toggleSelect('#myform',this)">
                                <label for="checkAll">選擇全部</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                @foreach($mainmenus as $mainmenu)
                                <div class="col-md-4" id="mmid_{{ $mainmenu->id }}">
                                    <div class="icheck-primary col-md-12">
                                        <input type="checkbox" onclick="toggleSelect('#mmid_{{ $mainmenu->id }}',this)" name="mypower" value="{{ $mainmenu->code }}" id="mmchkbox{{ $mainmenu->id }}" {{ isset($admin) ? in_array($mainmenu->code,explode(',',$admin->permissions)) ? 'checked' : '' : ''}}>
                                        <label for="mmchkbox{{ $mainmenu->id }}">{!! $mainmenu->fa5icon !!} {{ $mainmenu->name }}</label>
                                    </div>
                                    @if($mainmenu->url_type == 1)
                                    <div class="col-md-12">
                                        @foreach($powerActions as $powerAction)
                                        <input type="checkbox" name="mypower" value="{{ $mainmenu->code.$powerAction->code }}" {{ isset($admin) ? in_array($mainmenu->code.$powerAction->code,explode(',',$admin->permissions)) ? 'checked' : '' : ''}}><span> {{ $powerAction->name }}</span>
                                        @endforeach
                                    </div>
                                    @elseif($mainmenu->power_action)
                                    <div class="col-md-12">
                                        @foreach($powerActions as $powerAction)
                                        @if(in_array($powerAction->code,explode(',',$mainmenu->power_action)))
                                        <input type="checkbox" name="mypower" value="{{ $mainmenu->code.$powerAction->code }}" {{ isset($admin) ? in_array($mainmenu->code.$powerAction->code,explode(',',$admin->permissions)) ? 'checked' : '' : ''}}><span> {{ $powerAction->name }}</span>
                                        @endif
                                        @endforeach
                                    </div>
                                    @endif
                                    <div class="col-md-12">
                                        <ol>
                                            @foreach($mainmenu->submenu as $submenu)
                                            <div class="icheck-primary">
                                                <input type="checkbox" onclick="toggleSelect('#smid_{{ $submenu->id }}',this)" name="mypower" value="{{ $submenu->code }}" id="smchkbox{{ $submenu->id }}" {{ isset($admin) ? in_array($submenu->code,explode(',',$admin->permissions)) ? 'checked' : '' : ''}}>
                                                <label for="smchkbox{{ $submenu->id }}">{!! $submenu->icon !!} {{ $submenu->name }}</label>
                                            </div>
                                            <div class="col-md-12" id="smid_{{ $submenu->id }}">
                                                <ol>
                                                    @foreach($powerActions as $powerAction)
                                                    @if(in_array($powerAction->code,explode(',',$submenu->power_action)))
                                                    <input type="checkbox" name="mypower" value="{{ $submenu->code.$powerAction->code }}" {{ isset($admin) ? in_array($submenu->code.$powerAction->code,explode(',',$admin->permissions)) ? 'checked' : '' : ''}}><span> {{ $powerAction->name }}</span>
                                                    @endif
                                                    @endforeach
                                                </ol>
                                            </div>
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                    <div class="card-footer text-center bg-white">
                        <button id="modifyBtn" type="button" class="btn btn-primary">{{ isset($admin) ? '修改' : '新增' }}</button>
                        <a href="{{ url('admins') }}" class="btn btn-info">
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

    $('#modifyBtn').click(function () {
        let adminRole = '{{ Auth::user()->role }}';
        let form = $('#myform');
        if (form.parsley().validate()) {
            let power = [];
            $.each($("input[name='mypower']:checked"), function(){
                power.push($(this).val());
                $("input[name='mypower']").remove();
            });
            let powerString = power.join(',');
            if (adminRole == 'develop' || adminRole == 'admin') {
                form.append($('<input type="hidden" name="permissions">').val(powerString));
            }
            form.submit();
        }
    });
})(jQuery);

function chkall(input1, input2) {
        var objForm = document.forms[input1];
        var objLen = objForm.length;
        for (var iCount = 0; iCount < objLen; iCount++) {
            var objChk = $(objForm.elements[iCount]);
            if (!objChk.hasClass("toggle-switch") && !objChk.hasClass("disabled")) { //去除 上線,disabled
                if (input2.checked == true) {
                    if (objForm.elements[iCount].type == "checkbox") {
                        objForm.elements[iCount].checked = true;
                    }
                } else {
                    if (objForm.elements[iCount].type == "checkbox") {
                        objForm.elements[iCount].checked = false;
                    }
                }
            }
        }
    }

    function toggleSelect(parentId, trigger) {
        var option_list = document.querySelectorAll(`${parentId} input`);
        if (trigger.checked) {
            option_list.forEach((el) => {
                if (!el.classList.contains("disabled") && el.getAttribute("type") !== "radio") {
                    el.checked = true;
                }
            });
        } else {
            option_list.forEach((el) => {
                if (!el.classList.contains("disabled") && el.getAttribute("type") !== "radio") {
                    el.checked = false;
                }
            });
        }
    }
</script>
@endpush
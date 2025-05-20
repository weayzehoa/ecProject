@extends('admin.layouts.app')

@section('title', '管理員管理')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            {{-- alert訊息 --}}
            @include('admin.layouts.alert_message')
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><b>管理員操作紀錄</b></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                        <li class="breadcrumb-item active"><a href="{{ url('adminLogs') }}">管理員操作紀錄</a></li>
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
                                <div class="col-6">
                                    <div class="float-left d-flex align-items-center">
                                        <button id="showForm" class="btn btn-sm btn-success mr-2" title="使用欄位查詢">使用欄位查詢</button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="float-right">
                                        <div class="input-group input-group-sm align-middle align-items-middle">
                                            <span class="badge badge-purple text-lg mr-2">總筆數：{{ !empty($adminLogs) ? number_format($adminLogs->total()) : 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="search" class="card card-primary" style="display: {{ isset($searchForm) ? 'block' : 'none' }}">
                                <div class="card-header">
                                    <h3 class="card-title">使用欄位查詢</h3>
                                </div>
                                <form id="searchForm" role="form" action="{{ url('adminLogs') }}" method="get">
                                    <input type="hidden" name="searchForm" value="1">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="keyword">關鍵字搜尋:</label>
                                                <input type="text" class="form-control" id="keyword" name="keyword" placeholder="請輸入關鍵字" value="{{ isset($keyword) ? $keyword : '' }}" autocomplete="off" />
                                            </div>
                                            <div class="col-md-2">
                                                <label class="control-label" for="list">每頁筆數:</label>
                                                <select class="form-control" id="list" name="list">
                                                    @foreach ($lists as $key => $value)
                                                    <option value="{{ $value }}" {{ $list == $value ? 'selected' : '' }}>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center bg-white">
                                        <button type="button" onclick="formSearch()" class="btn btn-primary">查詢</button>
                                        <input type="reset" class="btn btn-default" value="清空">
                                    </div>
                                </form>
                            </div>
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-left" width="10%">管理員</th>
                                        <th class="text-left" width="10%">群組</th>
                                        <th class="text-left" width="15%">動作</th>
                                        <th class="text-left" width="45%">內容</th>
                                        <th class="text-left" width="10%">IP</th>
                                        <th class="text-left" width="10%">時間</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($adminLogs as $adminLog)
                                    <tr>
                                        <td class="text-left align-middle">
                                            {{ !empty($adminLog->admin) ? $adminLog->admin->name : '' }}
                                        </td>
                                        <td class="text-left align-middle">
                                            {{ !empty($adminLog->admin) ? $adminLog->admin->roleLabel : '' }}
                                        </td>
                                        <td class="text-left align-middle">
                                            {{ $adminLog->action }}
                                        </td>
                                        <td class="text-left align-middle text-warp">{!! nl2br(e($adminLog->descriptionText)) !!}</td>
                                        <td class="text-left align-middle">{{ $adminLog->ip }}</td>
                                        <td class="text-left align-middle">{{ $adminLog->created_at }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="float-left">
                                <span class="badge badge-primary text-lg ml-1">總筆數：{{ !empty($adminLogs) ? number_format($adminLogs->total()) : 0 }}</span>
                            </div>
                            <div class="float-right">
                                @if(isset($appends))
                                {{ $adminLogs->appends($appends)->render() }}
                                @else
                                {{ $adminLogs->render() }}
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
@endpush
@push('scripts')
    <script src="{{ asset('vendor/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
    <script>
        (function($) {
            "use strict";

            $("input[data-bootstrap-switch]").each(function() {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            });

            $('input[data-bootstrap-switch]').on('switchChange.bootstrapSwitch', function (event, state) {
                $(this).parents('form').submit();
            });

            $('.delete-btn').click(function (e) {
                if(confirm('請確認是否要刪除這筆資料?')){
                    $(this).parents('form').submit();
                };
            });

            $('#showForm').click(function(){
                let text = $('#showForm').html();
                $('#search').toggle();
                text == '使用欄位查詢' ? $('#showForm').html('隱藏欄位查詢') : $('#showForm').html('使用欄位查詢');
            });
        })(jQuery);

        function formSearch(){
            let form = $('#searchForm');
            $("#searchForm").submit();
        }
    </script>
@endpush
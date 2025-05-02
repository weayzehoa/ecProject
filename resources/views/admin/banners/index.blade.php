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
                    <h1 class="m-0 text-dark"><b>輪播管理</b></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                        <li class="breadcrumb-item active"><a href="{{ url('banners') }}">輪播管理</a></li>
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
                                        @if(in_array($menuCode.'N',explode(',',Auth::user()->permissions)))
                                        <a href="{{ route('admin.banners.create') }}" class="btn btn-sm btn-primary mr-1"><i class="fas fa-plus mr-1"></i>新增</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="float-right">
                                        <div class="input-group input-group-sm align-middle align-items-middle">
                                            <span class="badge badge-purple text-lg mr-2">總筆數：{{ !empty($banners) ? number_format($banners->total()) : 0 }}</span>
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
                                <form id="searchForm" role="form" action="{{ url('banners') }}" method="get">
                                    <input type="hidden" name="searchForm" value="1">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="name">姓名:</label>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="請輸入姓名" value="{{ isset($name) ? $name : '' }}" autocomplete="off" />
                                            </div>
                                            <div class="col-md-3">
                                                <label for="email">電子郵件:</label>
                                                <input type="text" class="form-control" id="email" name="email" placeholder="請輸入電子郵件" value="{{ isset($email) ? $email : '' }}" autocomplete="off" />
                                            </div>
                                            <div class="col-md-3">
                                                <label for="tel">聯絡電話:</label>
                                                <input type="text" class="form-control" id="tel" name="tel" placeholder="請輸入聯絡電話" value="{{ isset($tel) ?     $tel : '' }}" autocomplete="off" />
                                            </div>
                                            <div class="col-md-3">
                                                <label for="mobile">行動電話:</label>
                                                <input type="text" class="form-control" id="mobile" name="mobile" placeholder="請輸入行動電話" value="{{ isset($mobile) ? $mobile : '' }}" autocomplete="off" />
                                            </div>
                                            <div class="col-md-2 mt-2">
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
                                        <th class="text-left" width="15%">標題</th>
                                        <th class="text-center" width="10%">圖片</th>
                                        <th class="text-left" width="20%">描述</th>
                                        <th class="text-left" width="10%">開始時間</th>
                                        <th class="text-left" width="10%">結束時間</th>
                                        <th class="text-center" width="10%">狀態</th>
                                        <th class="text-center" width="10%">預覽</th>
                                        <th class="text-center align-middle" width="10%">排序</th>
                                        <th class="text-center" width="5%">刪除</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($banners as $banner)
                                    <tr>
                                        <td class="text-left align-middle">
                                            @if(in_array($menuCode.'M',explode(',',Auth::user()->permissions)))
                                            <span class=""><a href="{{ route('admin.banners.show', $banner->id ) }}">{{ $banner->title }}</a></span>
                                            @else
                                            <span class="">{{ $banner->title }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <a href="{{ asset('storage/upload/' . $banner->img) }}" data-toggle="lightbox" data-title="{{ $banner->title .' 輪播圖片 ' }}" data-gallery="gallery" data-max-width="1440">
                                            <img height="50" class="banner_image_{{ $banner->id }}" src="{{ asset('storage/upload/' . $banner->img) }}" alt="{{ $banner->title }}">
                                            </a>
                                        </td>
                                        <td class="text-left align-middle">{{ $banner->description }}</td>
                                        <td class="text-left align-middle">{{ $banner->start_time }}</td>
                                        <td class="text-left align-middle">{{ $banner->end_time }}</td>
                                        <td class="text-center align-middle">
                                            @if(in_array($menuCode.'O',explode(',',Auth::user()->permissions)))
                                            <form action="{{ url('banners/active/' . $banner->id) }}" method="POST">
                                                @csrf
                                                <input type="checkbox" name="is_on" value="{{ $banner->is_on == 1 ? 0 : 1 }}" data-bootstrap-switch data-on-text="啟用" data-off-text="停用" data-off-color="secondary" data-on-color="success" {{ isset($banner) ? $banner->is_on == 1 ? 'checked' : '' : '' }}>
                                            </form>
                                            @else
                                            <input type="checkbox" name="is_on" value="{{ $banner->is_on == 1 ? 0 : 1 }}" data-bootstrap-switch data-on-text="啟用" data-off-text="停用" data-off-color="secondary" data-on-color="success" {{ isset($banner) ? $banner->is_on == 1 ? 'checked' : '' : '' }} disabled>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            @if(in_array($menuCode.'O',explode(',',Auth::user()->permissions)))
                                            <form action="{{ url('banners/preview/' . $banner->id) }}" method="POST">
                                                @csrf
                                                <input type="checkbox" name="is_preview" value="{{ $banner->is_preview == 1 ? 0 : 1 }}" data-bootstrap-switch data-on-text="啟用" data-off-text="停用" data-off-color="secondary" data-on-color="success" {{ isset($banner) ? $banner->is_preview == 1 ? 'checked' : '' : '' }}>
                                            </form>
                                            @else
                                            <input type="checkbox" name="is_preview" value="{{ $banner->is_preview == 1 ? 0 : 1 }}" data-bootstrap-switch data-on-text="啟用" data-off-text="停用" data-off-color="secondary" data-on-color="success" {{ isset($banner) ? $banner->is_preview == 1 ? 'checked' : '' : '' }} disabled>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            @if(in_array($menuCode.'S',explode(',',Auth::user()->permissions)))
                                            @if($loop->iteration != 1)
                                            <a href="{{ url('banners/sortup/' . $banner->id) }}" class="text-navy">
                                                <i class="fas fa-arrow-alt-circle-up text-lg"></i>
                                            </a>
                                            @endif
                                            @if($loop->iteration != count($banners))
                                            <a href="{{ url('banners/sortdown/' . $banner->id) }}" class="text-navy">
                                                <i class="fas fa-arrow-alt-circle-down text-lg"></i>
                                            </a>
                                            @endif
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            @if(in_array($menuCode.'D',explode(',',Auth::user()->permissions)))
                                            <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="button" class="btn btn-sm btn-danger delete-btn">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="float-left">
                                <span class="badge badge-primary text-lg ml-1">總筆數：{{ !empty($banners) ? number_format($banners->total()) : 0 }}</span>
                            </div>
                            <div class="float-right">
                                @if(isset($appends))
                                {{ $banners->appends($appends)->render() }}
                                @else
                                {{ $banners->render() }}
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
    <link rel="stylesheet" href="{{ asset('vendor/ekko-lightbox/ekko-lightbox.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('vendor/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('vendor/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
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

            $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox({
                    alwaysShowClose: true
                });
            });
        })(jQuery);

        function formSearch(){
            let form = $('#searchForm');
            $("#searchForm").submit();
        }
    </script>
@endpush
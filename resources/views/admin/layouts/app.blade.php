<!DOCTYPE html>
<html lang="zh-Hant">
{{-- <html lang="{{ app()->getLocale() }}"> --}}
{{-- 使用 @yied() 方式，有需要再載入，節省資料傳輸加快速度 --}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>後台管理系統 - @yield('title')</title>
    @include('admin.layouts.css')
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
    {{-- 上方選單 --}}
    @include('admin.layouts.topbar')
    {{-- 側邊選單 --}}
    @include('admin.layouts.sidebar')
    {{-- 主要內容 --}}
    @section('content')
    @show
    {{-- 下方頁尾 --}}
    @include('admin.layouts.footer')
    </div>

@section('modal')
@show

{{-- 全站共用的JS套件 --}}
@include('admin.layouts.js')
@stack('scripts')
</body>
</html>

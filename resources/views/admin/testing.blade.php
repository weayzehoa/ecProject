@extends('admin.layouts.app')

@section('title', '測試功能')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        {{-- alert訊息 --}}
        @include('admin.layouts.alert_message')
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><b>測試功能</b></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('admin.testing') }}">測試功能</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-12">
                <h1 class="text-danger">此為開發測試頁面</h1>
            </div>
        </div>
    </section>
</div>
@endsection

@push('css')

@endpush

@push('scripts')

@endpush
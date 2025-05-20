<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <title>重設密碼</title>
    @include('admin.layouts.css')
</head>
<body class="hold-transition login-page bg-navy" style="background-image: url({{ asset('img/bg.jpg') }});">
    {{-- 背景動畫使用區塊 --}}
    <div id="particles-js"></div>
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center text-navy">
                <h2><b>重設密碼</h2>
            </div>
            <div class="card-body login-card-body">
                <p class="login-box-msg">請輸入管理者電子郵件</p>
                <form id="forgetForm" action="{{ route('admin.forgetPass.submit') }}" method="post" data-parsley-validate>
                    @csrf
                    <div class="input-group mb-3">
                        <input id="email" type="email"
                            placeholder="請輸入電子郵件"
                            class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                            name="email" value="{{ old('email') }}"
                            required
                            data-parsley-type="email"
                            data-parsley-required-message="請輸入有效的電子郵件"
                            autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    </div>
                    @if (session('status'))
                        <div class="text-danger mb-2">{{ session('status') }}</div>
                    @endif
                    {!! app('GoogleRecaptcha')->v3Input('resetPass') !!}
                    {{-- {!! app('GoogleRecaptcha')->v2Widget() !!} --}}

                    <div class="row">
                        <div class="col-4">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-block">返回</a>
                        </div>
                        <div class="col-4"></div>
                        <div class="col-4">
                            <button type="button" class="btn btn-success btn-block btn-submit">送出</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin.layouts.js')
    <script src="{{ asset('vendor/particles.js/particles.min.js') }}"></script>
    <script src="{{ asset('vendor/parsley/parsley.min.js') }}"></script>
    <script src="{{ asset('vendor/parsley/zh_tw.js') }}"></script>
    {!! app('GoogleRecaptcha')->v3Script('resetPass','visibility') !!}
    {{-- {!! app('GoogleRecaptcha')->v2Script() !!} --}}

    <script>
        $(document).ready(function () {
            // 啟動 Parsley
            $('#forgetForm').parsley({
                errorClass: 'is-invalid',
                successClass: 'is-valid',
                classHandler: function (ParsleyField) {
                    return ParsleyField.$element;
                },
                errorsContainer: function (ParsleyField) {
                    return ParsleyField.$element.siblings('.invalid-feedback');
                }
            });

            $('.btn-submit').click(function () {
                if ($('#forgetForm').parsley().validate()) {
                    $(this).attr('disabled', true);

                    // 等待 Recaptcha token 被填入再送出
                    const tokenInterval = setInterval(() => {
                        const token = $('#g-recaptcha-response').val();
                        if (token) {
                            clearInterval(tokenInterval);
                            $('#forgetForm').submit();
                        }
                    }, 100);
                }
            });

            $('body').keydown(function (e) {
                if (e.keyCode === 13) {
                    if ($('#forgetForm').parsley().validate()) {
                        $('.btn-submit').attr('disabled', true);

                        const tokenInterval = setInterval(() => {
                            const token = $('#g-recaptcha-response').val();
                            if (token) {
                                clearInterval(tokenInterval);
                                $('#forgetForm').submit();
                            }
                        }, 100);
                    }
                }
            });
        });
    </script>
</body>
</html>

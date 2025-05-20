<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>密碼重設通知</title>
    <style>
        body {
            font-family: 'Microsoft JhengHei', Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 30px;
            color: #333;
        }
        .container {
            max-width: 600px;
            background: #fff;
            border-radius: 6px;
            padding: 30px;
            margin: auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .content {
            font-size: 16px;
            line-height: 1.8;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #007bff;
            color: white !important;
            border-radius: 4px;
            text-decoration: none;
        }
        a.btn:hover {
            background-color: #0056b3;
            color: white !important;
        }
        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">密碼重設通知</div>
    <div class="content">
        <p>親愛的 {{ $user->name ?? $user->email }}，您好：</p>

        <p>您收到這封信是因為我們收到您的帳號密碼重設請求。</p>

        <p>請點選下方按鈕重新設定密碼：</p>

        <p><a class="btn" href="{{ $url }}">👉 重設密碼</a></p>

        <p>此連結將於 {{ config('auth.passwords.admins.expire', 60) }} 分鐘後失效。</p>

        <p>如果您並未請求此操作，請忽略這封信件。</p>
    </div>

    <div class="footer">
        若您有任何問題，請聯繫 {{ config('mail.from.address') }}<br>
        {{ config('app.name') }} 管理團隊 敬上
    </div>
</div>
</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{$siteName ?? '南海青商会'}} - 管理员登录</title>
        <!-- Styles -->
        <link rel="stylesheet" href="/css/pure-min.css" />
        <style>
            body {
                margin: 0;
                padding: 0;
                background: url('/images/banner_01_repeat.png') repeat-x #fff;
            }

            .container {
                max-width: 1100px;
                min-height: 700px;
                margin: 0 auto;
                position: relative;
                background: url('/images/banner_01.png') no-repeat #transparent;
            }

            .form-contaner {
                padding: 30px;
                position: absolute;
                top: 50px;
                right: 30px;
                width: 300px;
                min-height: 300px;
                background: #fff;
                border-radius: 10px;
            }

            .title {
                margin: 0 0 15px;
                padding: 10px 0;
                font-size: 110%;
                text-align: center;
            }

            .form-group {
                margin: 20px 0;
            }

            input, button {
                width: 100%;
            }

            .invalid-feedback {
                display: block;
                margin: 5px 0;
                font-size: 80%;
                color: #f30;
            }

            .footer-note {
                padding: 0;
                font-size: 80%;
                list-style: none;
            }

            .footer-note li {
                margin: 5px 0;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <img src="/images/banner_01.png" />

            <div class="form-contaner">
                <h2 class="title">管理员登录</h2>

                <form method="POST" action="{{ route('login') }}" class="pure-form login-form">
                    @csrf

                    <div class="form-group row">
                        <div class="col-md-6">
                            <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" required placeholder="电话" autofocus>

                            @error('mobile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="密码">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="pure-button pure-button-primary">
                                登录
                            </button>

                            @if (Route::has('password.request'))
                                请联系管理员重置密码
                            @endif
                        </div>
                    </div>
                </form>

                <ul class="footer-note">
                    <li>版权所有：佛山市凤星科技有限公司</li>
                    <li>粤ICP备13046912号-1</li>
                    <li>全国免费服务热线：4000-168-325</li>
                </ul>
            </div>
        </div>
    </body>
</html>

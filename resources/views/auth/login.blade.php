@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="login-box card">
                <!-- 「__(‘Login’)」という構文の「__」は、ヘルパ関数（viewで使うための関数）の一種で、翻訳文字列の取得として使われる -->
                <!-- resources/langの中に、多言語対応できるようにmessages.phpファイルを作成して読み込む -->
                <div class="login-header card-header mx-auto">{{ __('messages.Login') }}</div>

                <div class="login-body card-body">
                    <!-- route関数は、URLを生成したりリダイレクトしたりするための関数 -->
                    <!-- 今回であれば、”/login”というURLを生成している -->
                    <form method="POST" action="{{ route('login') }}">
                        <!-- 認証済みのユーザーがリクエストを送信しているのかを確認するために利用 -->
                        <!-- 隠しCSRFトークンを生成する -->
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('messages.E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <!-- 三項演算子 -->
                                <!-- 条件式がfalseまたはNULLの時は、:の右側が実行され、そうでない時は、:の左側が実行される -->
                                <!-- 今回はエラーがなければ$errors->has('email') の値はNULLになるので右側が表示される
                                （何も記載がないので実際は表示されない） -->
                                <!-- oldヘルパ関数は、セッションにフラッシュデータ（一時的にしか保存されないデータ）として入力されているデータを取得できる -->
                                <!-- 今回の場合のフラッシュデータとは、直前までユーザーが入力していた値、ない場合はnull-->
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" 
                                       name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('messages.Password') }}</label>
                        
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" 
                                       name="password" required>

                                 @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>    
                            
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('messages.Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('messages.Login') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

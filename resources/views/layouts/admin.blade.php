<!DOCTYPE html>
<!-- カッコの中身はPHPの内容、カッコの中身を文字列に置換し、HTMLの中に記載する -->
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <!-- windowsの基本ブラウザであるedgeに対応するという記載 -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- 画面幅を小さくしたとき、例えばスマートフォンで見たときなどに文字や画像の大きさを調整してくれるタグ -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        {{-- 後の章で説明します --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <!-- titleというセッションの内容を表示、各ページ毎にタイトルを変更できるようにするため -->
        {{-- 各ページごとにtitleタグを入れるために@yieldで空けておきます --}}
        <title>@yield('title')</title>
        
        <!---- Scripts -->
        <!-- asset(‘ファイルパス’)は、publicディレクトリのパスを返す関数「/js/app.js」というパスを生成する -->
        {{-- Laravel標準で用意されているJavascriptを読み込みます --}}
        <script src="{{ secure_asset('js/app.js') }}" defer></script>
        
        <!---- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
        
        <!---- Styles -->
        {{-- Laravel標準で用意されているCSSを読み込みます --}}
        <link href="{{ secure_asset('css/app.css') }}" rel="stylesheet">
        {{-- この章の後半で作成するCSSを読み込みます*admin.css --}}
        <link href="{{ secure_asset('css/admin.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app">
            {{-- 画面上部に表示するナビゲーションバーです。 --}}
            <nav class="navbar navbar-expand-md navbar-dark navbar-laravel">
                <div class="container">
                    <!-- url(“パス”)は、そのままURLを返すメソッド -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <!-- assetと似たような関数で、configフォルダのapp.phpの中にあるnameにアクセス -->
                        <!-- アプリケーションの名前「Laravel」が格納されている -->
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" 
                    aria-controls="navbarSupportedContent" area-expanded="false" area-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!----  Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">
                            
                        </ul>
                        
                        <!----  Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            
                        {{-- 追記012 認証リンク --}}
                        <!---- Authentication Links -->
                        {{-- ログインしていなかったらログイン画面へのリンクを表示 --}}
                        <!-- gestは認証されていないユーザー -->
                        @guest
                        　　<!-- Route関数で /login というURL生成 -->
                        　　<!-- ヘルパ関数「__」翻訳文字列の取得 -->
                            <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                        {{-- ログインしていたらユーザー名とログアウトボタンを表示 --}}
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" 
                                   href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <!-- ログイン中のユーザーの名前 -->
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>
                                
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}" 
                                       onclick="event.preventDefault();
                                                      document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>    
                             </li>
                            @endguest         
                            {{-- 以上までを追記 --}}
                        </ul>    
                    </div>
                </div>
            </nav>
            {{-- ここまでナビゲーションバー --}}
            
            <main class="py-4">
                {{-- コンテンツをここに入れるため、@yieldで空けておきます。 --}}
                @yield('content')
            </main>
        </div>    
    </body>
</html>

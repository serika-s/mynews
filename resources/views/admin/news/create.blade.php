<!-- テンプレート（viewファイル）の継承（読み込み）を行うメソッド -->
{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')

<!-- コンテンツのセクションを定義 -->
{{-- admin.blade.phpの@yield('title')に'ニュースの新規作成'を埋め込む --}}
@section('title','ニュースの新規作成')

{{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <h2>ニュース新規作成</h2>
                <form action="{{ action('Admin\NewsController@create') }}" method="post" enctype="multipart/form-data">
                    <!-- $errors は validate で弾かれた内容を記憶する配列 -->
                    <!-- countメソッドは配列の個数を返すメソッド -->
                    <!-- バリデーションでエラーを見つけたときには、Laravel が自動的に $errors という変数にエラーを格納 -->
                    <!-- エラーがなければ$errorsはnullを返すのでcount($errors)は0、ある時はメッセージを返す -->
                    @if (count($errors) > 0)
                        <ul>
                            <!-- foreachは配列の数だけループする構文 -->
                            <!-- $errorsの中身の数だけループし、その中身を$eに代入 -->
                            @foreach($errors->all() as $e)
                                <!-- $eに代入された中身を表示 -->
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="form-group row">
                        <label class="col-md-2">タイトル</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="title" value="{{ old('title') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2">本文</label>
                        <div class="col-md-10">
                            <textarea class="form-control" name="body" rows="20">{{ old('body') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2">画像</label>
                        <div class="col-md-10">
                            <input type="file" class="form-control-file" name="image">
                        </div>
                    </div>
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-primary" value="更新">
                </form>
                
            </div>
        </div>
    </div>    
@endsection



<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// 09課題３
// 「http://XXXXXX.jp/XXX というアクセスが来たときに、 AAAControllerのbbbというAction に渡すRoutingの設定」を書いてみてください。
Route::get('XXX','AAAController@bbb');

// 追記：09
// Route::group は、いくつかのRoutingの設定をgroup化する役割
// [‘prefix’ => ‘admin’] の設定を、無名関数function(){}の中の全てのRoutingの設定に適用させる
// [‘prefix’ => ‘admin’] は、無名関数function(){} の中の設定のURLを http://XXXXXX.jp/admin/ から始まるURLにしている
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    
    // http://XXXXXX.jp/admin/news/create にアクセスが来たら、Controller Admin\NewsController のAction addに渡すという設定
    // Route::get('admin/news/create', 'Admin\NewsController@add'); と下記は同じ意味になる
    // Routingの設定の最後に 「->middleware(‘auth’)」 と入れることで、リダイレクトされる（ログインしていない場合のアクセス時）#追記012
    // 通常のページ表示にはgetを受け取る、フォーム送信時にはpostを受け取る
    Route::get('news/create','Admin\NewsController@add'); #ニュース新規作成画面へのアクセス
    Route::post('news/create','Admin\NewsController@create'); #追記013 #ニュース新規作成画面へのアクセス
    Route::get('news', 'Admin\NewsController@index'); #追記015 #一覧表示へのアクセス
    Route::get('news/edit', 'Admin\NewsController@edit'); #追記016 #編集
    Route::post('news/edit', 'Admin\NewsController@update'); #追記016 #編集
    Route::get('news/delete', 'Admin\NewsController@delete'); #追記016 #削除
    
    // http://XXXXXX.jp/admin/profile/create にアクセスが来たら、Controller Admin\ProfileController のAction addに渡すという設定
    Route::get('profile/create','Admin\ProfileController@add'); #09課題 #Myプロフィール作成画面へのアクセス
    Route::post('profile/create','Admin\ProfileController@create'); #追記013 #Myプロフィール作成画面へのアクセス
    Route::get('profile', 'Admin\ProfileController@index'); #追記015 #一覧表示へのアクセス
    Route::get('profile/edit','Admin\ProfileController@edit'); #09課題 #編集
    Route::post('profile/edit','Admin\ProfileController@update'); #追記016 #編集
    Route::get('profile/delete', 'Admin\ProfileController@delete'); #追記016 #削除
    
});

// ログイン認証の設定
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'NewsController@index');

Route::get('/profile', 'ProfileController@index');

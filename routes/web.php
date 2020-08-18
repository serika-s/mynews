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

// 以下に追記　(09課題４は21・22行目)
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    
    Route::get('news/create','Admin\NewsController@add');
    Route::post('news/create','Admin\NewsController@create'); #追記1 #投稿画面作成
    Route::get('news', 'Admin\NewsController@index'); #追記2 #一覧に表示
    Route::get('news/edit', 'Admin\NewsController@edit'); #追記3 #更新
    Route::post('news/edit', 'Admin\NewsController@update'); #追記3 #更新
    Route::get('news/delete', 'Admin\NewsController@delete'); #追記4 #削除
    
    Route::get('profile/create','Admin\ProfileController@add');
    Route::post('profile/create','Admin\ProfileController@create'); #追記1 #Myプロフィール作成
    Route::get('profile', 'Admin\ProfileController@index'); #追記2 #一覧に表示
    Route::get('profile/edit','Admin\ProfileController@edit');
    Route::post('profile/edit','Admin\ProfileController@update'); #追記1
    Route::get('profile/delete', 'Admin\ProfileController@delete'); #追記4 #削除
    
});

// ログイン認証の設定
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'NewsController@index');

Route::get('/profile', 'ProfileController@index');

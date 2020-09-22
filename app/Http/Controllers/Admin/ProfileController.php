<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// 追記 (Profile Modelが扱えるようになる)
use App\Profile;

// 追記017 (Historyの使用を宣言)
use App\PHistory;

// 追記017 (Carbonを使う)
use Carbon\Carbon;

// 追記heroku画像
use Storage;

class ProfileController extends Controller
{
    // add関数というActionを実装
    public function add()
    {
        // views/admin/profileディレクトリ配下のcreate.blade.php というファイルを呼び出す
        return view('admin.profile.create');
    }
    // 追記013
    // createメソッド（登録画面作成）
    public function create(Request $request)
    {
        // 追記014
        // Varidationを行う
        $this->validate($request, Profile::$rules);
    
        $profiles = new Profile;
        $form = $request->all();
        
        // フォームからプロフィール画像が送信されてきたら、保存して、$profiles->profileimage_path に画像のパスを保存する
        if (isset($form['profileimage'])) {
          // $path = $request->file('profileimage')->store('public/profileimage'); をherokuで下記のように編集
          $path = Storage::disk('s3')->putFile('/',$form['profileimage'],'public');
          // $profiles->profileimage_path = basename($path); をherokuで下記のように編集
          $profiles->profileimage_path = Storage::disk('s3')->url($path);
        } else {
            $profiles->profileimage_path = null;
        }

        // フォームから送信されてきた_tokenとimageを削除する
        unset($form['_token']);
        unset($form['profileimage']);
        
        // データベースに保存する
        $profiles->fill($form);
        $profiles->save();
    
        // admin/profile/createにリダイレクトする
        return redirect('admin/profile/create');
    }
    
    // 追記015
    // indexメソッド(一覧表示・検索する処理）
    public function index(Request $request) 
    {
        $cond_name = $request->cond_name;
        if ($cond_name != '') {
            // 検索されたら検索結果を取得する
            $posts = Profile::where('title',$cond_name)->get();
        } else {
            // それ以外はすべてのニュースを取得する
            $posts = Profile::all();
        }    
        return view('admin.profile.index', ['posts' => $posts, 'cond_name' => $cond_name]);
    }
    
    // 追記016
    // editメソッド（更新・編集画面を処理）
    public function edit(Request $request) 
    {
        // Profile Modelからデータを取得する
        $profiles = Profile::find($request->id);
        if (empty($profiles)) {
            abort(404);
        }
        // views/admin/profileディレクトリ配下のedit.blade.php というファイルを呼び出す
        return view('admin.profile.edit', ['profile_form' => $profiles]);
    }
    
    // updateメソッド（更新・編集画面から送信されたフォームデータを処理）
    public function update(Request $request) 
    {
        // Validationをかける
        $this->validate($request, Profile::$rules);
        
        // Profile Modelからデータを取得する
        $profiles = Profile::find($request->id);
        
        // 送信されてきたフォームを格納する
        $profile_form = $request->all();
        if ($request->remove == 'true') {
            $profile_form['profileimage_path'] = null;
        } elseif ($request->file('profileimage')) {
            // $path = $request->file('profileimage')->store('public/profileimage'); をherokuで下記のように編集
            $path = Storage::disk('s3')->putFile('/',$profile_form['profileimage'],'public');
            // $profile_form ['profileimage_path'] = basename($path); をherokuで下記のように編集
            $profiles->profileimage_path = Storage::disk('s3')->url($path);
        } else {
            $profile_form ['profileimage_path'] = $profiles->profileimage_path;
        }
        
        unset($profile_form['_token']);
        unset($profile_form['profileimage']);
        unset($profile_form['remove']);
         
        // 該当するデータを上書きして保存する
        $profiles->fill($profile_form)->save();
        
        // 追記017
        $phistory = new PHistory;
        $phistory->profile_id = $profiles->id;
        $phistory->edited_at = Carbon::now();
        $phistory->save();
        
        return redirect('admin/profile/');
    }
        
    // 追記
    // deleteメソッド=削除
    public function delete(Request $request)
    {
        // 該当するProfile Modelを取得
        $profiles = Profile::find($request->id);
        // 削除する
        $profiles->delete();
        return redirect('admin/profile/');
    }
    
}

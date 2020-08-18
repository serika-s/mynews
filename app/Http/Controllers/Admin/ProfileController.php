<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// 追記 (Profile Modelが扱えるようになる)
use App\Profile;

// 追記 (Historyの使用を宣言)
use App\PHistory;

// 追記 (Carbonを使う)
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function add()
    {
        return view('admin.profile.create');
    }

    public function create(Request $request)
    {
        // 追記
        // Varidationを行う
        $this->validate($request, Profile::$rules);
    
        $profiles = new Profile;
        $form = $request->all();
        
        // フォームからプロフィール画像が送信されてきたら、保存して、$profiles->profileimage_path に画像のパスを保存する
        if (isset($form['profileimage'])) {
          $path = $request->file('profileimage')->store('public/profileimage');
          $profiles->profileimage_path = basename($path);
        } else {
            $profiles->profileimage_path = null;
        }

        // フォームから送信されてきた_tokenを削除する
        unset($form['_token']);
        
        // データベースに保存する
        $profiles->fill($form);
        $profiles->save();
    
        // admin/profile/createにリダイレクトする
        return redirect('admin/profile/create');
    }
    
    // 追記
    // indexメソッド=一覧に表示
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
    
    // 追記
    public function edit(Request $request) 
    {
        // Profile Modelからデータを取得する
        $profiles = Profile::find($request->id);
        if (empty($profiles)) {
            abort(404);
        }
        return view('admin.profile.edit', ['profile_form' => $profiles]);
    }
    
    // updateメソッド=更新
    public function update(Request $request) 
    {
        // Validationをかける
        $this->validate($request, Profile::$rules);
        
        // Profile Modelからデータを取得する
        $profiles = Profile::find($request->id);
        
        // 送信されてきたフォームを格納する
        $profile_form = $request->all();
        if (isset($profile_form['profileimage'])) {
          $path = $request->file('profileimage')->store('public/profileimage');
          $profiles->profileimage_path = basename($path);
          unset($profile_form['profileimage']);
        } elseif (isset($request->remove)) {
          $profiles->profileimage_path = null;
            unset($profile_form['remove']);
        }
         
        // 該当するデータを上書きして保存する
        $profiles->fill($profile_form)->save();
        
        // 追記
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

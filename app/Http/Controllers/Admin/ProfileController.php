<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


// 以下を追記することでProfile Modelが扱えるようになる
use App\Profile;

class ProfileController extends Controller
{
    // 以下を追記
    public function add()
    {
        return view('admin.profile.create');
    }

    
    public function create(Request $request)
    {
        // 以下を追記
        // Varidationを行う
        $this->validate($request, Profile::$rules);
    
        $profiles = new Profile;
        $form = $request->all();
        
        // フォームから送信されてきた_tokenを削除する
        unset($form['_token']);
        
        // データベースに保存する
        $profiles->fill($form);
        $profiles->save();
    
        // admin/profile/createにリダイレクトする
        return redirect('admin/profile/create');
    }
    
    
    // 以下を追記
    public function index(Request $request) #indexメソッド=一覧に表示
    {
        $cond_title = $request->cond_title;
        if ($cond_title != ''){
            // 検索されたら検索結果を取得する
            $posts = Profile::where('title',$cond_title)->get();
        } else {
            // それ以外はすべてのニュースを取得する
            $posts = Profile::all();
        }    
        return view('admin.profile.index', ['posts' => $posts, 'cond_title' => $cond_title]);
    }
    
    
    // 以下を追記
    public function edit(Request $request) 
    {
        // Profile Modelからデータを取得する
        $profiles = Profile::find($request->id);
        if (empty($profiles)) {
            abort(404);
        }
        return view('admin.profile.edit', ['profile_form' => $profiles]);
    }
    
    
    public function update(Request $request) #updateメソッド=更新
    {
        // Validationをかける
        $this->validate($request, Profile::$rules);
        
        // Profile Modelからデータを取得する
        $profiles = Profile::find($request->id);
        
        // 送信されてきたフォームを格納する
        $profile_form = $request->all();
        unset($profile_form['_token']);
        
        // 該当するデータを上書きして保存する
        $profiles->fill($profile_form)->save();
        
        return redirect('admin/profile/');
    }
}


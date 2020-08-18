<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// 追記 (News Modelが扱えるようになる)
use App\News;

// 追記 (Historyモデルの使用を宣言)
use App\History;

// 追記 (Carbonを使う)
use Carbon\Carbon;

class NewsController extends Controller
{
    public function add()
    {
        return view('admin.news.create');
    }
    // createメソッド=投稿画面作成
    public function create(Request $request) 
    {
        // 追記 (保存の処理)
        // Varidationを行う
        $this->validate($request, News::$rules);
    
        $news = new News;
        $form = $request->all();
        
        // formから画像が送信されてきたら、保存して、$news->image_path に画像のパスを保存する
        if ($form['image']) {
          $path = $request->file('image')->store('public/image');
          $news->image_path = basename($path);
        } else {
            $news->image_path = null;
        }
        
        // フォームから送信されてきた_tokenを削除する
        unset($form['_token']);
        // フォームから送信されてきたimageを削除する
        unset($form['image']);
        
        // データベースに保存する
        $news->fill($form);
        $news->save();
    
        // admin/news/createにリダイレクトする
        return redirect('admin/news/create');
    }
        
    // 追記
    // indexメソッド=一覧に表示
    public function index(Request $request) 
    {
        $cond_title = $request->cond_title;
        if ($cond_title != '') {
            // 検索されたら検索結果を取得する
            $posts = News::where('title',$cond_title)->get();
        } else {
            // それ以外はすべてのニュースを取得する
            $posts = News::all();
        }    
        return view('admin.news.index', ['posts' => $posts, 'cond_title' => $cond_title]);
    }
    
    // 追記
    public function edit(Request $request) 
    {
        // News Modelからデータを取得する
        $news = News::find($request->id);
        if (empty($news)) {
            abort(404);
        }
        return view('admin.news.edit', ['news_form' => $news]);
    }
 
    // updateメソッド=更新 
    public function update(Request $request)
    {
        // Validationをかける
        $this->validate($request, News::$rules);
        
        // News Modelからデータを取得する
        $news = News::find($request->id);
        
        // 送信されてきたフォームを格納する
        $news_form = $request->all();
        if ($request->remove == 'true') {
            $news_form['image_path'] = null;
        } elseif ($request->file('image')) {
            $path = $request->file('image')->store('public/image');
            $news_form ['image_path'] = basename($path);
        } else {
            $news_form ['image_path'] = $news->image_path;
        }
        
        unset($news_form['_token']);
        unset($news_form['image']);
        unset($news_form['remove']);
        
        // 該当するデータを上書きして保存する
        $news->fill($news_form)->save();
        
        // 追記
        $history = new History;
        $history->news_id = $news->id;
        $history->edited_at = Carbon::now();
        $history->save();
        
        return redirect('admin/news/');
    }
    
    // 追記
    // deleteメソッド=削除
    public function delete(Request $request)
    {
        // 該当するNews Modelを取得
        $news = News::find($request->id);
        // 削除する
        $news->delete();
        return redirect('admin/news/');
    }
    
    
}

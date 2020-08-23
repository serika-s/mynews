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
    // add関数というActionを実装
    public function add()
    {
        // views/admin/newsディレクトリ配下のcreate.blade.php というファイルを呼び出す
        return view('admin.news.create');
    }
    // 追記013
    // createメソッド（投稿画面作成）
    // Requestクラスは、ブラウザを通してユーザーから送られる情報をすべて含んだオブジェクトを取得することができる
    // 取得した情報は$requestに代入して使用する
    public function create(Request $request) 
    {
        // 追記014 (データを保存する処理)
        // Varidationを行う（データの値を検証）
        // $thisは擬似変数と呼ばれ、呼び出し元のオブジェクトへの参照を意味する。クラスに定義された変数を使用したいときに$thisを使用
        // 下記は、validateメソッドを参照後、第一引数$requestにより問題があるならエラーメッセージと入力値とともに直前のページに戻る
        // 第二引数News::$rulesにより配列の形でどのカラムにどんなバリデーションをかけるかを指定（News.phpファイルの$rules変数を呼び出す）
        // これによりvalidationが実行できる
        $this->validate($request, News::$rules);
    
        // newはモデルからインスタンス（レコード）を生成するメソッド
        $news = new News;
        // $request->all();はformで入力された値を取得（ユーザーが入力したデータを取得する）
        $form = $request->all();
        
        // formから画像が送信されてきたら、保存して、$news->image_path に画像のパスを保存する
        // issetメソッドは、引数の中にデータがあるかないかを判断する
        // 投稿画面で画像を選択していれば$form[’image’]にデータが入り、選択していなければnullを返す仕様
        if (isset($form['image'])) {
          // fileメソッドは、Illuminate\Http\UploadedFileクラスのインスタンスを返す（画像をアップロードするメソッド）
          // storeメソッドは、どこのフォルダにファイルを保存するか、パスを指定する
          $path = $request->file('image')->store('public/image');
          // basenameメソッドは、パスではなくファイル名だけ取得する
          // 取得したファイル名をnewsテーブルのimage_pathに代入   
          $news->image_path = basename($path);
        } else {
            // Newsテーブルのimage_pathカラムにnullを代入するという意味
            $news->image_path = null;
        }
        
        // unsetメソッドは、$form変数を使って「title」と「body」に値を代入する際に不要となる「_token」と「image」を削除する
        // $formのデータがtitleとbodyのみになる
        // フォームから送信されてきた_tokenを削除する
        unset($form['_token']);
        // フォームから送信されてきたimageを削除する
        unset($form['image']);
        
        // データベースに保存する
        // fillメソッドは、配列をカラムに代入する
        $news->fill($form);
        // これで、「title」「body」「image_path」の値にデータを入れることができた
        // 「id」カラムはデータベースに保存したときに値を自動的に挿入してくれる
        // データベースに保存する準備ができたので、saveメソッドで実際に保存を実行
        $news->save();
        // これでユーザーが入力したデータをデータベースに保存することが完了
    
        // admin/news/createにリダイレクトする
        return redirect('admin/news/create');
    }
        
    // 追記015
    // indexメソッド(一覧表示・検索する処理)
    // ControllerではModelに対して where メソッドを指定して検索する（where への引数で検索条件を設定）
    // ただし、検索条件となる名前が入力されていない場合は、登録してあるすべてのデータを取得
    public function index(Request $request) 
    {
        // $requestの中のcond_titleの値を$cond_titleに代入
        // $requestにcond_titleがなければnullが代入される
        // $cond_titleがあればそれに一致するレコードを、なければ全てのレコードを取得する
        $cond_title = $request->cond_title;
        if ($cond_title != '') {
            // $cond_titleにデータが存在する場合
            // 検索されたら検索結果を取得する
            // newsテーブルの中のtitleカラムで$cond_title（ユーザーが入力した文字）に一致するレコードを全て取得
            // 取得したテーブルを$posts変数に代入
            $posts = News::where('title',$cond_title)->get();
        } else {
            // $cond_titleがnullの場合（elseについて）
            // それ以外はすべてのニュースを取得する
            // Newsモデルを使って、データベースに保存されているnewsテーブルのレコードを全て取得
            // 取得したテーブルを変数$postsに代���
            $posts = News::all();
        }
        // 最初に開いた段階では、cond_titleは存在しない
        // 投稿したニュースを検索するための機能として活用
        // index.blade.phpのファイルに取得したレコード（$posts）と、ユーザーが入力した文字列（$cond_title）を渡し、ページを開く
        return view('admin.news.index', ['posts' => $posts, 'cond_title' => $cond_title]);
    }
    
    // 追記016
    // editメソッド（更新・編集画面を処理）
    public function edit(Request $request) 
    {
        // News Modelからデータを取得する
        $news = News::find($request->id);
        if (empty($news)) {
            abort(404);
        }
        return view('admin.news.edit', ['news_form' => $news]);
    }
 
    // updateメソッド（更新・編集画面から送信されたフォームデータを処理）
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

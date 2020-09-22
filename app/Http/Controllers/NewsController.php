<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 追記
use Illuminate\Support\Facades\HTML;

use App\News;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        // News::all()はEloquentを使った、全てのnewsテーブルを取得するというメソッド
        // sortByDesc()というメソッドは、カッコの中の値（キー）でソートするためのメソッド
        // 投稿日時順に新しい方から並べるということ
        $posts = News::all()->sortByDesc('updated_at');
        
        if (count($posts) > 0 ) {
            // 最新の記事とそれ以外の記事とで表記を変えたいため$headline = $posts->shift();を用いる
            $headline = $posts->shift();
        } else {
            $headline = null;
        }
        
        // news/index.blade.php ファイルを渡している
        // また　View　テンプレートに 'headline' 'posts' という変数を渡している
        return view('news.index', ['headline' => $headline, 'posts' => $posts]);
    }
}

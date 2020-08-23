<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    //  title と body と image_pathを追記
    // 関数upには、マイグレーション実行時のコードを書く
    public function up()
    {
        // id(主キー)、title、body、image_path、timestampsの５つのカラムを持つ、newsというテーブルを作成
        Schema::create('news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title'); // ニュースのタイトルを保存するカラム
            $table->string('body');  // ニュースの本文を保存するカラム 
            // ->nullable()という記述は、画像のパスは空でも保存できます、という意味
            $table->string('image_path')->nullable(); // 画像のパスを保存するカラム
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    // 関数downには、マイグレーションの取り消しを行う為のコードを書く 
    public function down()
    {
        // もしnewsというテーブルが存在すれば削除する
        Schema::dropIfExists('news');
    }
}

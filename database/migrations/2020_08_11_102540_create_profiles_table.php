<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    //  name と gender と hoby と introductionと profileimage_pathを追記
    // 関数upには、マイグレーション実行時のコードを書く
    public function up()
    {
        // id(主キー)、name、gender、hobby、introduction、profileimage_pathの6つのカラムを持つ、profilesというテーブルを作成
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name'); // プロフィールの名前を保存するカラム
            $table->string('gender'); // プロフィールの性別を保存するカラム
            $table->string('hobby'); // プロフィールの趣味を保存するカラム
            $table->string('introduction'); // プロフィールの自己紹介を保存するカラム
            // ->nullable()という記述は、画像のパスは空でも保存できます、という意味
            $table->string('profileimage_path')->nullable();  // 画像のパスを保存するカラム
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
        // もしpforilesというテーブルが存在すれば削除
        Schema::dropIfExists('profiles');
    }
}

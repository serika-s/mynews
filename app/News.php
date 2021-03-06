<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $guarded = array('id');

    //追記014 validationの設定
    // arrayメソッドは配列を作る
    public static $rules = array(
        'title' => 'required',
        'body' => 'required',
        );
        
        // 追記017
        // NewsモデルにHistoryモデルとの関連付けを定義
        public function histories()
        {
            return $this->hasMany('App\History');
        }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $guarded = array('id');
    
    // 追記014
    public static $rules = array(
        'name' => 'required',
        'gender' => 'required',
        'hobby' => 'required',
        'introduction' => 'required',
        );
        
    // 追記017
    // ProfileモデルにPHistoryモデルとの関連付けを定義
    public function p_histories()
    {
      return $this->hasMany('App\PHistory');
      
    }    
          
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $guarded = array('id');
    
    // 以下を追記
    public static $rules = array(
        'name' => 'required',
        'gender' => 'required',
        'hobby' => 'required',
        'introduction' => 'required',
        );
        
    // 追記
    // Profileモデルに関連付けを行う
    public function p_histories()
    {
      return $this->hasMany('App\PHistory');
      
    }    
          
}

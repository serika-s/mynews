<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PHistory extends Model
{
    // 作り直し
    protected $guarded = array('id');
    
    public static $rules = array(
        'profile_id' => 'required',
        'edited_at' => 'required',
        );
}

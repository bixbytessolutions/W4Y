<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class chat extends Model
{
    //
    protected $fillable = ['id','fromid','toid','message','msgon','msgtype', 'file_url', 'seen', 'deleteforid', 'file_size'];

}

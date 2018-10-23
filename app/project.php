<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class project extends Model
{
    //
    protected $fillable = ['title','first_msg','description','budget','deadline_date','owner_id', 'last_msg', 'isopen_forbid', 'hired_freelancerid', 'hiredon'];

}

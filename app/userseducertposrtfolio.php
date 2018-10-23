<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class userseducertposrtfolio extends Model
{
    //
    protected $fillable = [
        'title','userid','rectype','description','sub_description','start_date','completiondate','portfolioimg','portfoliourl'
    ];

}

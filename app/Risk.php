<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Risk extends Model
{
     protected $fillable = [
       "client_name","client_email","client_number","location","longitude","latitude","rf","qrs","user_id"
    ];
}

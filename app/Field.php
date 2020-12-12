<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
   	protected $fillable = [
   		'customer_details', 
   		'bank_details', 
   		'investment_details',
   		'fatca_details',
   		'user_id',
   		'status'
   	];
          
}

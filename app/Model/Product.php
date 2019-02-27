<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Review;

class Product extends Model
{
    public function reviews(){
        return $this->hasMany("App\Model\Review");
    }
    public function user(){
        return $this->belongsTo("App\User");
    }
}

<?php

namespace App;

//use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //use Searchable;
    protected $fillable = ['quantity'];

    public function categories(){
        return $this->belongsToMany('App\Category');
    }
    public function scopeMightAlsoLike($query){
        return $query->inRandomOrder()->take(4);
    }
}

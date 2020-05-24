<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    public static function findByCode($code){
        return self::where('code', $code)->first();
    }
    public function discount($total){
        if($this->type == 'fixed'){
            return $this->value;
        }elseif($this->type == 'percent'){
            $newTotal  =  str_replace(',', '', $total);
           return round (($this->percent_off / 100) * $newTotal );
        }else{
            return 0;
        }
    }
}

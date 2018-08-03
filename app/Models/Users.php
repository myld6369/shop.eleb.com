<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Users extends Authenticatable
{
    use Notifiable;
    //
    protected $fillable=['name','create_at','shop_id','update_at','status','email','password','remember_token'];

    public function Shops()
    {
        return $this->belongsTo(Shops::class, 'shop_id','id');
    }

}

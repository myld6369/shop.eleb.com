<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuCategories extends Model
{
    //
    protected $fillable=['name','created_at','shop_id','updated_at','type_accumulation','description','is_selected'];

    public function Users()
    {
        return $this->belongsTo(Users::class, 'id','shop_id');
    }
}

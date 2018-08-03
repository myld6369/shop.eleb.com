<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    //
    protected $fillable=['goods_name','rating','shop_id','updated_at','category_id','description','mouth_sales','rating_count','tips','satisfy_count','satisfy_rate','created_at','goods_price','goods_img','month_sales'];

    public function MenuCategories()
    {
        return $this->belongsTo(MenuCategories::class, 'category_id','id');
    }

    public function Users()
    {
        return $this->belongsTo(Users::class, 'shop_id','shop_id');
    }
}

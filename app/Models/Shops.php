<?php

namespace App\Models;

use App\Modles\Shops_categories;
use Illuminate\Database\Eloquent\Model;

class Shops extends Model
{
    //
    protected $fillable=['shop_name','create_at','update_at','status','shop_category_id','shop_img','shop_rating','brand','on_time','fengniao','bao','piao','zhun','start_send','send_cost','notice','discount'];


    public function Shops_categories()
    {
        return $this->belongsTo(Shops_categories::class, 'shop_category_id','id');
    }
}

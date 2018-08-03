<?php

namespace App\Http\Controllers;

use App\Models\Menus;
use App\Models\Order;
use App\Models\OrderGoods;
use App\Models\Shops;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    //
    public function index(Order $order)
    {
//        if ($order->status==0){
//            $order->status=2;
//        }elseif ($order->status){
//
//        }

        $orders =Order::orderBy('created_at', 'desc')->paginate(5);
        foreach ($orders as &$order){
            if ($order->status==0){
                $status='待付款';
            }elseif ($order->status==-1){
                $status='已取消';
            }elseif ($order->status==1){
                $status='待发货';
            }elseif ($order->status==2){
                $status='待确认';
            }elseif ($order->status==3){
                $status='完成';
            }
        }
        return view('Order/index',compact('orders','status'));
    }

    public function show(Order $order)
    {

            if ($order->status==0){
                $order->status='待付款';
            }elseif ($order->status==-1){
                $order->status='已取消';
            }elseif ($order->status==1){
                $order->status='待发货';
            }elseif ($order->status==2){
                $order->status='待确认';
            }elseif ($order->status==3){
                $order->status='完成';
            }

            $ordergoods =OrderGoods::where('order_id',$order->id)->get();
            return view('Order/show',compact('order','ordergoods'));
        }

    public function sales(Request $request)
    {
        $user =User::where('id',Auth::id())->first();

        $orders =Order::where('shop_id',$user->shop_id)->get();
        if (!empty($request->month)){
            $month = substr($request->month,6,2);
            $orders =Order::where('shop_id',$user->shop_id)->whereMonth('created_at',$month)->get();
        }
        if (!empty($request->date)){
            $orders =Order::where('shop_id',$user->shop_id)->whereDate('created_at',$request->date)->get();
        }
        foreach ($orders as $order){
            if ($order->status==0){
                $status='待付款';
            }elseif ($order->status==-1){
                $status='已取消';
            }elseif ($order->status==1){
                $status='待发货';
            }elseif ($order->status==2){
                $status='待确认';
            }elseif ($order->status==3){
                $status='完成';
            }
        }
        return view('Order/sales',compact('orders','status'));
    }

    public function menussales(Request $request)
    {
        $user =User::where('id',Auth::id())->first();


        $menus =DB::select("SELECT SUM(amount) as num,goods_name as name from order_goods WHERE order_id in (select id FROM orders where shop_id=$user->shop_id) GROUP BY goods_id ORDER BY num DESC");
        if (!empty($request->month)){
            $month =substr($request->month,0,7);
            $menus =DB::select("SELECT SUM(amount) as num,goods_name as name from order_goods WHERE order_id in (select id FROM orders where shop_id=$user->shop_id and created_at like '$month%') GROUP BY goods_id ORDER BY num DESC");
        }
        if (!empty($request->date)){
            $menus =DB::select("SELECT SUM(amount) as num,goods_name as name from order_goods WHERE order_id in (select id FROM orders where shop_id=$user->shop_id and created_at like '$request->date%') GROUP BY goods_id ORDER BY num DESC");
        }

        return view('Order/menus',compact('menus'));

    }
}

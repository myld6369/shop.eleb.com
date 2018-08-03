<?php

namespace App\Http\Controllers;

use App\Models\MenuCategories;
use App\Models\Menus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenusController extends Controller
{
    //
    Public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['']
        ]);
    }
    public function index(Request $request)
    {
        $where =[];
        $where[]=['shop_id',Auth::user()->shop_id];
        if (!empty($request->category_id)){
            $where[]=['category_id',$request->category_id];
        }
        if (!empty($request->name)){
            $where[]=['goods_name','like','%'.$request->name.'%'];
        }
        if (!empty($request->di)){
            $where[]=['goods_price','>=',$request->di];
        }
        if (!empty($request->gao)){
            $where[]=['goods_price','<=',$request->gao];
        }
        $data=[
            'category_id'=>$request->category_id,
            'name'=>$request->name,
            'di'=>$request->di,
            'gao'=>$request->gao
        ];
        $user = Auth::user()->name;
        $menus =Menus::where($where)->paginate(1);
        $menucategories =MenuCategories::where('shop_id',Auth::user()->shop_id)->get();
        return view('Menus/index',compact('menus','user','menucategories','data'));
    }

    public function create()
    {
        $menucategories =MenuCategories::where('shop_id',Auth::user()->shop_id)->get();
        return view('Menus/create',compact('menucategories'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'goods_name'=>'required',
            'description'=>'required',
            'goods_price'=>'required',
            'category_id'=>'required',
            'tips'=>'required',
            'goods_img'=>'required'
        ],[
            'goods_name.required'=>'菜品名称不能为空',
            'description.required'=>'菜品描述不能为空',
            'goods_price.required'=>'菜品价格不能为空',
            'tips.required'=>'提示信息不能为空',
            'category_id.required'=>'菜品分类不能为空',
            'goods_img.required'=>'菜品图片不能为空'
        ]);
        $img =$request->goods_img;
//        $storage =Storage::disk('oss');
//            $image=$storage->putFile('menus',$img);
//            $image=Storage::url($image);



        Menus::create([
            'goods_name'=>$request->goods_name,
            'description'=>$request->description,
            'goods_price'=>$request->goods_price,
            'tips'=>$request->tips,
            'rating'=>5,
            'shop_id'=>Auth::user()->shop_id,
            'category_id'=>$request->category_id,
            'month_sales'=>0,
            'rating_count'=>0,
            'satisfy_count'=>0,
            'satisfy_rate'=>0,
            'goods_img'=>$img,
        ]);

        session()->flash('success', '添加菜品成功');
        return redirect()->route('menus.index');
    }

    public function edit(Menus $menu)
    {
        $menucategories =MenuCategories::where('shop_id',Auth::user()->shop_id)->get();
        return view('Menus/edit',compact('menu','menucategories'));
    }

    public function update(Menus $menu,Request $request)
    {
        $this->validate($request,[
            'goods_name'=>'required',
            'description'=>'required',
            'goods_price'=>'required',
            'category_id'=>'required',
            'tips'=>'required',

        ],[
            'goods_name.required'=>'菜品名称不能为空',
            'description.required'=>'菜品描述不能为空',
            'goods_price.required'=>'菜品价格不能为空',
            'tips.required'=>'提示信息不能为空',
            'category_id.required'=>'菜品分类不能为空',

        ]);
        $img =$request->goods_img;
        if (empty($img)){
        $img=$menu->goods_img;
        }else{
        $img =$request->goods_img;
        }
        $menu->update([
            'goods_name'=>$request->goods_name,
            'description'=>$request->description,
            'goods_price'=>$request->goods_price,
            'tips'=>$request->tips,
            'category_id'=>$request->category_id,
            'goods_img'=>$img,
        ]);

        session()->flash('success', '修改菜品成功');
        return redirect()->route('menus.index');
    }

    public function destroy(Menus $menu)
    {
        $menu->delete();
        session()->flash('success', '删除菜品成功');
        return redirect()->route('menus.index');
    }

    public function show(Menus $menu)
    {
        $user = Auth::user()->name;
        return view('Menus/show',compact('menu','user'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\MenuCategories;
use App\Models\Menus;
use App\Models\Shops;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MenuCategoriesController extends Controller
{
    //
    Public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['']
        ]);
    }
    public function index()
    {
        $shop_id =Auth::user()->shop_id;
        $shop =Shops::where('id',$shop_id)->first();
        $menucategories =MenuCategories::where('shop_id',$shop_id)->get();
        return view('Menucategories/index',compact('menucategories','shop'));
    }

    public function create()
    {
        return view('Menucategories/create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=> 'required||unique:menu_categories',
            'description'=>'required'
        ],[
            'name.required'=>"分类名称不能为空",
            'name.unique'=>"分类名称已存在",
            'description.required'=>"描述不能为空"
        ]);
        $shop_id =Auth::user()->shop_id;
        $type_accumulation =uniqid();
        $count =MenuCategories::where('shop_id',$shop_id)->count();
        if ($count<1){
            MenuCategories::create([
                'name'=>$request->name,
                'description'=>$request->description,
                'shop_id'=>$shop_id,
                'is_selected'=>1,
                'type_accumulation'=>$type_accumulation
            ]);
        }else{
        MenuCategories::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'shop_id'=>$shop_id,
            'is_selected'=>0,
            'type_accumulation'=>$type_accumulation
        ]);
        }
        session()->flash('success', '添加菜单分类成功');
        return redirect()->route('menucategories.index');
    }

    public function edit(MenuCategories $menucategory)
    {

        return view('Menucategories/edit',compact('menucategory'));
    }

    public function update(Request $request,MenuCategories $menucategory)
    {
        $this->validate($request,[
            'name'=>[
                'required',
                Rule::unique('menu_categories')->ignore($menucategory->id, 'id'),
            ],
            'description'=>'required'
        ],[
            'name.required'=>"分类名称不能为空",
            'name.unique'=>"分类名称已存在",
            'description.required'=>"描述不能为空"
        ]);
        $is_selected=$request->is_selected;
        if ($is_selected!=1){
            $is_selected=0;
        }
        $id =$menucategory->id;
        $menucategory->update([
            'name'=>$request->name,
            'description'=>$request->description,
            'is_selected'=>$is_selected
        ]);

        $count =MenuCategories::where('is_selected',1)->count();

            if ($count<1){
                $menucategory->update([
                    'is_selected'=>1
                ]);
                session()->flash('danger', '当前只有一个默认分类,若想更换请直接设置其他分类为默认分类');
                return back();
        }

        if ($count>1){
            MenuCategories::where('is_selected',1)->update([
                'is_selected'=>0
            ]);
            MenuCategories::where('id',$id)->update([
                'is_selected'=>1
            ]);
        }
        session()->flash('success', '修改菜单分类成功');
        return redirect()->route('menucategories.index');

    }

    public function destroy(MenuCategories $menucategory)
    {
        $count =Menus::all()->where('category_id',$menucategory->id)->count();
        if($menucategory->is_selected==1){
            session()->flash('success', '该分类为默认分类不能删除');
            return back();
        }elseif ($count>0){
            session()->flash('success', '该分类中有菜品不能删除');
            return back();
        }else{
            $menucategory->delete();
            session()->flash('success', '删除分类成功');
            return redirect()->route('menucategories.index');
        }

    }
}

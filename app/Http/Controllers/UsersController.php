<?php

namespace App\Http\Controllers;

use App\Models\Shops;
use App\Models\Shops_categories;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    //
    Public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['create','index','store']
        ]);
    }
    public function index()
    {
        return view('Index/index');
    }
    public function create()
    {
        if (Auth::check()){
            session()->flash('danger', '您已登录');
            return redirect()->route('users.index');
        }

        $categories =Shops_categories::all();
        return view('Users/create',compact('categories'));
    }

    public function store(Request $request)
    {


        if ($request->password!=$request->repassword){
            session()->flash('danger', '确认密码与输入密码不一致');
            return back();
        }
        $this->validate($request,[
            'shop_name'=>'required|max:10',
            'start_send'=>'required',
            'send_cost'=>'required',
            'notice'=>'required',
            'discount'=>'required',
            'name'=>'required|min:6|max:16|unique:users',
            'password'=>'required|min:6|max:16',
            'email'=>'required|email|unique:users',
            'captcha'=>'required|captcha'
            ,'shop_img'=>'required'

        ],[
            'shop_name.required'=>'店铺名称不能为空!',
            'shop_name.max'=>'店铺名称最多10个字符',
            'start_send.required'=>'起送金额不能为空',
            'send_cost.required'=>'配送费不能为空',
            'notice.required'=>'店公告不能为空',
            'discount.required'=>'优惠信息不能为空',
            'name.required'=>'用户名不能为空',
            'email.required'=>'邮箱不能为空',
            'email.email'=>'邮箱格式不正确',
            'email.unique'=>'邮箱已存在',
            'name.min'=>'用户名不能小于6位',
            'name.unique'=>'用户名已存在',
            'name.max'=>'用户名不能大于6位',
            'password.required'=>'密码不能为空',
            'password.min'=>'密码不能小于6位',
            'password.max'=>'密码不能大于6位',
            'captcha.required'=>'验证码不能为空',
            'captcha.captcha'=>'验证码错误',
            'shop_img.required'=>'商铺图片不能为空'
        ]);

        $img =$request->shop_img;


        DB::beginTransaction();
        try{
            $id=Shops::create([
                'shop_name'=>$request->shop_name,
                'shop_category_id'=>$request->shop_category_id,
                'shop_img'=>$img,
                'shop_rating'=>5.0,
                'brand'=>$request->brand,
                'on_time'=>$request->on_time,
                'fengniao'=>$request->fengniao,
                'bao'=>$request->bao,
                'piao'=>$request->piao,
                'zhun'=>$request->zhun,
                'start_send'=>$request->start_send,
                'send_cost'=>$request->send_cost,
                'notice'=>$request->notice,
                'discount'=>$request->discount,
                'status'=>0
            ]);

            Users::create([
                'name'=>$request->name,
                'password'=>bcrypt($request->password),
                'email'=>$request->email,
                'status'=>0,
                'shop_id'=>$id->id,
                'remember_token'=>0
            ]);

         DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
            dd($e);

            session()->flash('danger', '注册失败');
            return back();
        }
        session()->flash('success', '注册成功');
        return redirect()->route('users.index');
    }

    public function edit(Users $user)
    {
        $shop =Shops::where('id',$user->shop_id)->first();
        $categories =Shops_categories::all();
        return view('Users/edit',compact('categories'));
    }

    public function update(Request $request,Users $user)
    {
        $shop =Shops::where('id',$user->shop_id)->first();
        $this->validate($request,[
            'shop_name'=>'required|max:10',
            'start_send'=>'required',
            'send_cost'=>'required',
            'notice'=>'required',
            'discount'=>'required',
            'captcha'=>'required|captcha'
        ],[
            'shop_name.required'=>'店铺名称不能为空!',
            'shop_name.max'=>'店铺名称最多10个字符',
            'start_send.required'=>'起送金额不能为空',
            'send_cost.required'=>'配送费不能为空',
            'notice.required'=>'店公告不能为空',
            'discount.required'=>'优惠信息不能为空',
            'captcha.required'=>'验证码不能为空',
            'captcha.captcha'=>'验证码错误',
        ]);
        $img =$request->shop_img;
        if (empty($img)){
            $image=$shop->shop_img;
        }else{

            $image=$request->shop_img;
        }
        $shop->update([
            'shop_name'=>$request->shop_name,
            'shop_category_id'=>$request->shop_category_id,
            'shop_img'=>$image,
            'brand'=>$request->brand,
            'on_time'=>$request->on_time,
            'fengniao'=>$request->fengniao,
            'bao'=>$request->bao,
            'piao'=>$request->piao,
            'zhun'=>$request->zhun,
            'start_send'=>$request->start_send,
            'send_cost'=>$request->send_cost,
            'notice'=>$request->notice,
            'discount'=>$request->discount,
        ]);
        session()->flash('success', '修改商铺信息成功');
        return redirect()->route('users.index');
    }

    public function show(Users $user)
    {
        return view('Users/show',compact('user'));
    }

    public function user(Request $request,Users $user)
    {
        $this->validate($request,[
            'email'=>[
                Rule::unique('users')->ignore($user->id, 'id'),
                'required',
                'email'
                ]
        ],[
            'email.required'=>'邮箱不能为空',
            'email.email'=>'邮箱格式不正确',
            'email.unique'=>'该邮箱已存在',
        ]);
        $user->update([
            'email'=>$request->email
        ]);
        session()->flash('success', '修改个人信息成功');
        return redirect()->route('users.index');
    }

    public function pass(Users $user)
    {
        return view('Users/pass',compact('user'));
    }

    public function password(Request $request)
    {
        $this->validate($request,[
            'oldpassword'=>'required',
            'password'=>'required|min:6|max:16',
            'repassword'=>'required'
            ],[
                'oldpassword.required'=>'旧密码不能为空',
            'password.required'=>'密码不能为空',
            'password.min'=>'密码不能小于6位',
            'password.max'=>'密码不能大于16位',
            'repassword.required'=>'确认密码不能为空'
        ]);
        if ($request->password!=$request->repassword){
            session()->flash('success', '修改密码失败,确认密码与新密码不一致');
            return back();
        }
        if (Hash::check($request->oldpassword,Auth::user()->password)) {
            // 密码对比...
            $password =bcrypt($request->password);
            Auth::user()->update([
                'password'=>$password
            ]);
            Auth::logout();
            session()->flash('success', '修改密码成功,请重新登录');
            return redirect()->route('users.index');
        }else{
            session()->flash('success', '修改密码失败,旧密码错误');
            return back();
        }

    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Shops;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    //

    public function login()
    {
            return redirect()->route('users.index');
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'captcha' => 'required|captcha',
            'name' => 'required',
            'password' => 'required',

        ],[
            'name.required'=>'用户名不能为空',
            'password.required'=>'密码不能为空',
            'captcha.required'=>"验证码不能为空",
            'captcha.captcha'=>"验证码错误",
        ]);


        if (Auth::attempt(['name'=>$request->name,'password'=>$request->password],$request->rememberMe)){
            $user = Users::where('name',$request->name)->first();
            $shop =Shops::where('id',$user->shop_id)->first();
            if ($user->status==0){
                Auth::logout();
                return back()->with('danger','很抱歉，您的账号还未审核')->withInput();
            }elseif($shop->status!=1){
                Auth::logout();
                return back()->with('danger','很抱歉，您的商铺还未审核')->withInput();
            }
            session()->flash('success', '欢迎回来！');
            return redirect()->route('users.index');
        }else{
            return back()->with('danger','很抱歉，您的用户名和密码不匹配')->withInput();
        }
    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect()->route('users.index');
    }

}

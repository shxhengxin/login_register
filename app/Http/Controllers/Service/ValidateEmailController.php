<?php

namespace App\Http\Controllers\Service;

use App\Events\UserRegistered;
use Illuminate\Http\Request;
use App\Tool\Validate\ValidateCode;
use App\Http\Controllers\Controller;
use App\User;
use App\Tool\UUID;
use Illuminate\Support\Facades\Cache;

class ValidateEmailController extends Controller
{
    public function create()
    {   //创建邮箱验证码
        $validateCode =new ValidateCode();
        return $validateCode->make();
    }
    //检测邮箱是否注册
    public function checkEmail(Request $request)
    {
        $email = $request->except('_token');
        $re = User::where('email',$email)->first();
        if($re){
            return ['status'=>5,'message'=>"该邮箱已注册"];
        }
    }

    //激活邮箱
    public function activate(Request $request)
    {
        $user = User::where('remember_token',$request->get('token'))->first();
        if(Cache::has('uuidTime'.$user->email)){
            $user->remember_token = UUID::create();
            $user->status = 1;
            $user->save();
        }
    }

}

<?php

namespace App\Http\Controllers\Service;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Orgs\M3Result;
use Illuminate\Support\Facades\Crypt;
use App\Tool\Validate\ValidateCode;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $m3_result = new M3Result();
        $_code = new ValidateCode();
        $emailPreg = '/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/';
        $phonePreg = '/^((1[3,5,8][0-9])|(14[5,7])|(17[0,1,6,7,8]))\d{8}$/';

        if(strtolower($request->get('code')) != strtolower($_code->get())){
            $m3_result->status = 1;
            $m3_result->message = '验证码输入有误';
            return $m3_result->toJson();
        }
        if(preg_match($emailPreg,$request->get('accouts'))){
            $re = User::where('email',$request->get('accouts'))->first();
            if(!$re){
                $m3_result->status = 1;
                $m3_result->message = '该邮箱未注册';
                return $m3_result->toJson();
            }
            if(Crypt::decrypt($re->password) != $request->get('password')){
                $m3_result->status = 1;
                $m3_result->message = '密码输入有误';
                return $m3_result->toJson();
            }
            $m3_result->status = 0;
            $m3_result->message = '登录成功';
            return $m3_result->toJson();

        }elseif(preg_match($phonePreg,$request->get('accouts'))){
            $re = User::where('phone',$request->get('accouts'))->first();
            if(!$re){
                $m3_result->status = 1;
                $m3_result->message = '该手机未注册';
                return $m3_result->toJson();
            }
            if(Crypt::decrypt($re->password) != $request->get('password')){
                $m3_result->status = 1;
                $m3_result->message = '密码输入有误';
                return $m3_result->toJson();
            }
            $m3_result->status = 0;
            $m3_result->message = '登录成功';
            return $m3_result->toJson();
        }

    }
}

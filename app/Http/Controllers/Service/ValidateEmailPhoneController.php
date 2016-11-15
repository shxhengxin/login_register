<?php

namespace App\Http\Controllers\Service;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Orgs\M3Result;
use App\Tool\UUID;
use App\Tool\Validate\ValidateCode;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class ValidateEmailPhoneController extends Controller
{

    public function register(Request $request)
    {
        $m3_result = new M3Result();
        $email = $request->input('email', '');
        $phone = $request->input('phone', '');
        $password = $request->input('password', '');
        $confirm = $request->input('confirm', '');
        $phone_code = $request->input('phone_code', '');
        $validate_code = $request->input('validate_code', '');
        if($email == '' && $phone == '') {
            $m3_result->status = 1;
            $m3_result->message = '手机号或邮箱不能为空';
            return $m3_result->toJson();
        }
        if($password == '' || strlen($password) < 6) {
            $m3_result->status = 2;
            $m3_result->message = '密码不少于6位';
            return $m3_result->toJson();
        }
        if($confirm == '' || strlen($confirm) < 6) {
            $m3_result->status = 3;
            $m3_result->message = '确认密码不少于6位';
            return $m3_result->toJson();
        }
        if($password != $confirm) {
            $m3_result->status = 4;
            $m3_result->message = '两次密码不相同';
            return $m3_result->toJson();
        }
        //手机注册
        if($phone != '' ){
            if($phone_code == '' && strlen($phone_code) != 6){
                $m3_result->status = 5;
                $m3_result->message = '手机验证码为6位';
                return $m3_result->toJson();
            }
            if(time() > Redis::get('phoneCodeTime'.$phone)){
                $m3_result->status = 7;
                $m3_result->message = "验证码失败，请重新获取验证码";
                return $m3_result->toJson();
            }
            if($phone_code != Cache::get('ValidateCode'.$phone)){
                $m3_result->status = 6;
                $m3_result->message = "手机验证码错误";
                return $m3_result->toJson();
            }

            $re = User::create(['phone' => $phone,'password' =>$password,'type'=>'手机用户']);
            if($re){
                $m3_result->status = 0;
                $m3_result->message = "注册成功";
                return $m3_result->toJson();
            }
            $m3_result->status = 8;
            $m3_result->message = "注册失败";
            return $m3_result->toJson();
            //邮箱注册
        }else{
            if($validate_code == '' && strlen($validate_code) != 4){
                $m3_result->status = 5;
                $m3_result->message = "验证码为4位";
                return $m3_result->toJson();
            }
            $code = new ValidateCode();
            if( strtolower($code->get()) != strtolower($validate_code)){
                $m3_result->status = 6;
                $m3_result->message = "验证码错误";
                return $m3_result->toJson();
            }
            Cache::put('remember_token_uuid',UUID::create(),1);//记录UUID，通过UUID查找表里对应用户，缓存时间为1分钟
            Cache::put('uuidTime'.$email,time(),1440);//记录uuid生效时间为24个小时
            $user = ['email' => $email, 'password' => $password, 'remember_token' => Cache::get('remember_token_uuid'), 'type' => '邮箱用户',];
            $re = User::register($user);//用户注册
            if($re){
                $m3_result->status = 0;
                $m3_result->message = "注册成功";
                return $m3_result->toJson();
            }
            $m3_result->status = 8;
            $m3_result->message = "注册失败";
            return $m3_result->toJson();
        }
    }
}

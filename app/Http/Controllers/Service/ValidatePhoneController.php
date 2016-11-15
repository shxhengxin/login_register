<?php

namespace App\Http\Controllers\Service;


use App\Http\Controllers\Controller;
use App\Tool\SMS\SendTemplateSMS;
use App\User;
use Illuminate\Http\Request;
use App\Orgs\M3Result;

class ValidatePhoneController extends Controller
{
  //检测手机是否注册
    public function checkPhone(Request $request)
    {
        $phone = $request->except('_token');
        $re = User::where('phone',$phone)->first();
        if($re){
            return ['status'=>5,'message'=>"该手机已注册"];
        }
    }

    //发送手机验证码
    public function send(Request $request)
    {   //引入各种消息状态，返回结果为json数据
        $m3_result = new M3Result();
        $phone = $request->get('phone');
        //对手机验证
        if($phone == ''){
            $m3_result->status = 1;
            $m3_result->message = '手机号不能为空';
            return $m3_result->toJson();
        }
        if(strlen($phone) != 11 || $phone[0] != 1 ){
            $m3_result->status = 2;
            $m3_result->message = '手机号格式错误';
            return $m3_result->toJson();
        }
        //引入发送信息模板
        $sendTemplateSMS = new SendTemplateSMS();
        //把手机验证码写入缓存，过期时间为5分钟
        $phoneCode = Cache::remember('ValidateCode'.$phone,5,function() {
            return rand(100000,999999);
        });
        //记录验证码创建时间加上5分钟
        Redis::set('phoneCodeTime'.$phone,time()+300);
        //发送手机验证码
        $re = $sendTemplateSMS->sendTemplateSMS($phone,array($phoneCode, 5), 1);
        if(!$re){
            $m3_result ->status = 4;
            $m3_result ->message = "发送失败";
            return $m3_result->toJson();
        }

    }
}

<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
use Illuminate\Support\Facades\Redis;

Route::get('/',function(){
    return view("welcome");
});
Route::get('login','View\MemberController@toLogin');
Route::get('register','View\MemberController@toRegister');
Route::get('/service/validate_code/create','Service\ValidateEmailController@create');//创建邮箱验证码
Route::get('/service/validate_email/activate','Service\ValidateEmailController@activate');//激活邮箱
Route::get('/service/validate_email/resend','Service\ValidateEmailController@resend');//重新发送邮箱
Route::post('service/check/email','Service\ValidateEmailController@checkEmail');//检查该邮箱是否注册
Route::post('service/check/phone','Service\ValidatePhoneController@checkPhone');//检查该手机是否注册
Route::post('/service/validate_phone/send','Service\ValidatePhoneController@send');//发送手机验证码
Route::post('/service/register','Service\ValidateEmailPhoneController@register');//注册，写入到数据库

Route::post('login','Service\LoginController@login'); //登录处理


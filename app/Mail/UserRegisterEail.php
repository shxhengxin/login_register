<?php
/**
 * Created by PhpStorm.
 * User: amaya
 * Date: 2016/11/11
 * Time: 11:54
 */

namespace App\Mail;

use Illuminate\Support\Facades\Cache;

class UserRegisterEail extends Mail
{
    public function welcome($user)
    {
        $subject = '邮箱验证';//模板中的邮件标题
        $view = 'welcome';//模板中的调用名称
        $data = ['%name%' =>[$user->email],'%token%' => [Cache::get('remember_token_uuid')]];
        $this->sendTo($user,$subject,$view,$data);
    }

}
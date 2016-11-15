<?php
/**
 * Created by PhpStorm.
 * User: amaya
 * Date: 2016/11/11
 * Time: 11:52
 */

namespace App\Mail;


class Mail
{
    protected $url = "http://api.sendcloud.net/apiv2/mail/sendtemplate";
    protected function sendTo($user,$subject,$view,$data=[])
    {
        $vars = json_encode(['to' => [$user->email],'sub'=>$data]);
        $param = [
            'apiUser' => env('SENDCLOUD_API_USER'),
            'apiKey'  => env('SENDCLOUD_API_KEY'),
            'from'    => config('mail')['from']['address'],
            'fromName' => config('mail')['from']['name'],
            'xsmtpapi' => $vars,
            'subject' => $subject,
            'templateInvokeName' => $view,
            'respEmailId' => 'true',
        ];
        $sendData = http_build_query($param);
        $options = [
            'http' =>[
                'method' => 'POST',
                'header' => 'Content-Type:application/x-www-form-urlencoded',
                'content' => $sendData,
            ],
        ];
        $context = stream_context_create($options);//创建资源流上下文
        return file_get_contents($this->url,FILE_TEXT,$context);
    }
}
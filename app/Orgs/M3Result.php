<?php
/**
 * Created by PhpStorm.
 * User: amaya
 * Date: 2016/11/10
 * Time: 12:34
 */

namespace App\Orgs;


class M3Result
{
    public $status;
    public $message;

    public function toJson()
    {
        return json_encode($this, JSON_UNESCAPED_UNICODE);
    }

}
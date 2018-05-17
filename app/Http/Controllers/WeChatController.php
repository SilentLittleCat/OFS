<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Log;

class WeChatController extends Controller
{
    public function serve()
    {
        $wechat = app('wechat');
        $wechat->server->setMessageHandler(function($message){
            return "欢迎关注 overtrue！";
        });
        return $wechat->server->serve(); // 或者 return $server;
    }
}

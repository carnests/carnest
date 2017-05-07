<?php
/**
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/4/4
 * Time: 18:16
 */

namespace app\auth\controller;
use app\common\tool\wechat\EasyWechat;
use EasyWeChat\Message\Voice;
class Wechat
{
    protected $wechat;
    public function __construct()
    {
        $this->wechat = EasyWechat::mp();
    }

    /**
     * 验证服务器连接有效性
     */
    public function index()
    {

        $message = $this->wechat->server->getMessage();
        if($message['MsgType'] == 'text'){
            //$video = new Voice(['media_id' => $this->material()]);

            $this->wechat->server->setMessageHandler(function ($message) { return new Voice(['media_id' => $this->material()]);});
        }elseif($message['MsgType'] == 'voice'){
            //$this->wechat->server->setMessageHandler(function ($message) { return new Voice(['media_id' => $message['MediaId']]);});
            $this->wechat->server->setMessageHandler(function ($message) { return new Voice(['media_id' => $this->material()]);});
        }
        $response = $this->wechat->server->serve();
        $response->send();

    }

    public function material()
    {
        $url = './upload/voice/1.mp3';
        $result = $this->wechat->material->uploadVoice($url);

        return  $result->media_id;
    }
}
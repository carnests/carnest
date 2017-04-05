<?php
/**
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/4/4
 * Time: 18:16
 */

namespace app\auth\controller;
use app\common\tool\wechat\EasyWechat;

class Wechat
{
    protected $wechat;
    /**
     * 验证服务器连接有效性
     */
    public function index()
    {
        $this->wechat = EasyWechat::mp();
        $response = $this->wechat->server->serve();
        $response->send();
    }
}
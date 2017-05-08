<?php

/**
 * 语言电话通知
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/4/8
 * Time: 20:31
 */
namespace app\notice\controller;
use app\common\controller\Common;
class VoiceNotice extends Common
{
    public function index()
    {
        $id = input('id');  //car_card表id
        if($id){
            $db = model('card/BindPhone');
            $phone = $db->getPhone($id);
            //调用语言短信接口
        }

    }
}
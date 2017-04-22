<?php

/**
 * 发生送语言电话
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/4/8
 * Time: 20:31
 */
namespace app\notice\controller;
use app\common\controller\Common;
class VoiceSms extends Common
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
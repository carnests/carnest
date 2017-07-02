<?php
/**
 * 小程序
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/1
 * Time: 11:40
 */

namespace app\auth\controller;
use app\common\tool\wechat\XcxWechat;
use think\Controller;

class Xcx extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        $code = input('post.code');
        $xcx = new XcxWechat();
        $result = $xcx->getUserInfo($code);
        if($result){
            return $result;
        }else{
           return $xcx->getError();
        }
    }
}
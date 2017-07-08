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
    protected $user;
    public function __construct()
    {
        parent::__construct();
        $this->user = session('user');
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

    public function openid()
    {
        $code = input('code');
        $xcx = new XcxWechat();
        $result = $xcx->getSessionKey($code);
        if($result){
            //保持信息
            action('user/User/add_user',[$result]);     //添加用户信息
            return $result;
        }else{
            return $xcx->getError();
        }
    }
}
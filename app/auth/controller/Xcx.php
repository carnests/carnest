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
use app\common\tool\wechat\EasyWechat;
use think\Controller;

class Xcx extends Controller
{
    protected $user;
    protected $wechat;
    protected $xcx;
    public function __construct()
    {
        parent::__construct();
        $this->wechat = EasyWechat::mp();
        $this->xcx = $this->wechat->mini_program;
        $this->user = session('user');
    }

    public function login()
    {
        $code = input('code');
        $iv = input('iv');
        $encryptedData = input('encryptedData');
//        $xcx = new XcxWechat();
//        $result = $xcx->getUserInfo($code);

        $sessionData = $this->xcx->sns->getSessionKey($code);
        $user = $this->xcx->encryptor->decryptData($sessionData['session_key'], $iv, $encryptedData);
        $user['session_key'] = $sessionData['session_key'];
        action('user/User/add_user',[$user,'xcx']);     //添加用户信息

        $session_3rd = make_3rd_session();
        cache($session_3rd,$user['openId'].$user['session_key']);
        if($user){
            return $session_3rd;
        }else{
           return $this->xcx->getError();
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
            return ['errCode'=>1,'errMsg'=>'请求异常'];
        }
    }
}
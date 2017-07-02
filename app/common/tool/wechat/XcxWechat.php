<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/1
 * Time: 11:44
 */

namespace app\common\tool\wechat;


class XcxWechat
{
    const APPID = 'wxdf008d543a554f1f';
    const SECRET = '9e7cb05255daf8e1bab3056f74c77419';  //2017-7-1
    const CODE_TO_SESSION_URL = 'https://api.weixin.qq.com/sns/jscode2session';



    protected $error;
    public function __construct($param=[])
    {
        $this->appid = isset($param['appid'])?$param['appid']:self::APPID;
        $this->secret = isset($param['secret'])?$param['secret']:self::SECRET;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getSessionKey($code)
    {
        $result = http_get(self::CODE_TO_SESSION_URL.'?appid='.$this->appid.'&secret='.$this->secret.'&js_code='.$code.'&grant_type=authorization_code');
        if($result){
            $json = json_decode($result,true);
            if(!$json || isset($json['errcode'])){
                $this->error = ['errCode'=>$json['errcode'],'errMsg'=>$json['errmsg']];
                return false;
            }
            return $json;
        }
    }

    public function getUserInfo($code)
    {
        $session = $this->getSessionKey($code);
        dump($session);
    }
}
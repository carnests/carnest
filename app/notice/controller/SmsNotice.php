<?php

/**
 * 短信通知
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/4/8
 * Time: 20:30
 */
namespace app\notice\controller;
use app\common\controller\Common;
use app\common\tool\sms\Sms;
use think\Config;

class SmsNotice extends Common
{
    public function __construct()
    {
        parent::__construct();
    }


    public function index($phone,$type)
    {
        $type = $this->getType($type);
        if(!$type){
            return ['errCode'=>1,'errMsg'=>'来源异常'];
        }
        $m_sms_log = model('notice/SmsLog');
        if(!$m_sms_log->dailyLimit($phone,1)){          //1 代表短信验证码
            return $m_sms_log->getError();
        }
        $m_verify_code = model('notice/VerifyCode');
        $code = $m_verify_code->make_code($phone,$type); //注册短信
        if(!$code){
            return $m_verify_code->getError();
        }
        $sms = new Sms('ucpaas');
        $msg = [
            'uid'=>$this->user['id'],
            'type'=>'text_verify',
            'templateId' => '42777',
            'source'=>$type,
            'param'=>[
                'code'=>$code,
            ],
        ];
        $result = $sms->send($phone,$msg);
        if($result){
            return ['errCode'=>0,'errMsg'=>'成功'];
        }else{
            return $sms->getError();
        }
    }

    public function getType($type)
    {
        return Config::get('sms.SEND_TYPE')[$type];
    }
}
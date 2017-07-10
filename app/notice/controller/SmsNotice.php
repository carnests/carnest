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
class SmsNotice extends Common
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($phone)
    {
        $m_verify_code = model('notice/VerifyCode');
        $code = $m_verify_code->make_code($phone,1);
        if(!$code){
            return $m_verify_code->getError();
        }
        $sms = new Sms('ucpaas');
        $msg = [
            'uid'=>$this->user['id'],
            'type'=>'text_verify',
            'templateId' => '42777',
            'source'=>1,
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
}
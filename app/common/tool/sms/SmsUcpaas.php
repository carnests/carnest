<?php

/**
 * 云之讯短信平台
 * User: coolong
 * Date: 2017/4/23
 * Time: 22:22
 */
namespace app\common\tool\sms;
use api\sms\Ucpaas;
class SmsUcpaas
{
    protected $sms;
    protected $phone;
    protected $msg;
    protected $error;
    protected $appId;
    public function __construct()
    {
        $this->appId = "558ce46f8d49471e9ef4311ec38abbc4";

        $options['accountsid']='af5c83e5aafb7463aff7e65afd3e06e2';
        $options['token']='601612ffc9b82d530b6e3407152e2645';
        $this->sms = new Ucpaas($options);
    }

    public function getError()
    {
        return $this->error;
    }

    public function send($phone,$msg)
    {
        if(is_array($phone)){

        }else{
            if(isPhone($phone)){
                $this->phone = $phone;
                $this->msg = $msg;
                $result = $this->getSmsType();
                return $result;
            }else{
                $this->error = ['errCode'=>400001,'errMsg'=>'电话格式错误'];
                return false;
            }
        }
    }

    public function getSmsType()
    {
        switch ($this->msg['type']){
            case 'text':
                return $this->sendText();
                break;
            case 'voice_verify':
                return $this->sendVoiceVerify();
                break;
            case 'voice_notice':
                return $this->sendVoiceNotice();
                break;
            default:
                return $this->sendText();
        }
    }

    protected function sendText()
    {
        $param = implode(',',$this->msg['param']);
        return $this->sms->templateSMS($this->appId,$this->phone,$this->msg['templateId'],$param);
    }
}
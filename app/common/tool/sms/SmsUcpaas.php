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
    protected $type;    //短信类型 1验证短信 2通知短信  3语言验证码  4语言通知  5双向呼叫
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
                $this->log($result?1:0);
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
            case 'text_verify':
                return $this->sendTextVerify();         //验证短信
                break;
            case 'text_notice':
                return $this->sendTextNotice();         //通知短信
                break;
            case 'voice_verify':
                return $this->sendVoiceVerify();        //语言验证
                break;
            case 'voice_notice':
                return $this->sendVoiceNotice();        //语言通知
                break;
            case 'voice_call':
                return $this->sendVoiceCall();        //双向呼叫
                break;
            default:
                return $this->sendTextVerify();
        }
    }

    /**
     * 验证短信
     * @return mixed|string
     * @throws \api\sms\Exception
     */
    protected function sendTextVerify()
    {
        $this->type = 1;
        $param = implode(',',$this->msg['param']);
        return $this->sms->templateSMS($this->appId,$this->phone,$this->msg['templateId'],$param);
    }

    protected function sendTextNotice()
    {
        $this->type = 2;
        return 2;
    }

    protected function sendVoiceVerify()
    {
        $this->type = 3;
        return 3;
    }

    protected function sendVoiceNotice()
    {
        $this->type = 4;
        return 4;
    }

    protected function sendVoiceCall()
    {
        $this->type = 5;
        return 5;
    }

    private function log($status)
    {
        $log['type'] = $this->type;
        $log['phone'] = $this->phone;
        $log['caller'] = $this->msg['caller']?:0;     //主叫号码（只用于双方呼叫）
        $log['ip'] =  request()->ip();
        $log['uid'] = $this->msg['uid']?:0;
        $log['source'] = $this->msg['source'];
        $log['status'] = $status;
        model('notice/SmsLog')->save($log);
    }
}
<?php

/**
 * 云之讯短信平台
 * User: coolong
 * Date: 2017/4/23
 * Time: 22:22
 */
namespace app\common\tool\sms;
use api\sms\Ucpaas;
class ApiUcpaas
{
    protected $sms;
    protected $phone;
    protected $msg;
    protected $error;
    protected $appId;
    protected $type;    //短信类型 1验证短信 2通知短信  3语言验证码  4语言通知  5双向呼叫
    public function __construct()
    {
//        $this->appId = "0cd41510c4ac47aca75f6c826e6f6fb0";
        $this->appId = "7c4e059a7cbe4a5ebec447ba497e79c7";      //挪车
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
            $this->phone = $phone;
            $this->msg = $msg;
            if(isPhone($phone)){
                $result = $this->getSmsType();
            }else{
                $this->error = ['errCode'=>400001,'errMsg'=>'电话格式错误'];
                $result = false;
            }
            $this->log($result?1:0);
            return $result;
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
        return $this->sms->voiceCode($this->appId,$this->msg['param']['code'],$this->phone);
    }

    /**
     * 语音通知
     * @return int
     */
    protected function sendVoiceNotice()
    {
        $this->type = 4;
        return $this->sms->voiceNotice($this->appId,$this->phone,'18835241102',2,$this->msg['templateId'],$this->msg['param']);
    }

    /**
     * 双向回拨
     * @return int|mixed|string
     * @throws \api\sms\Exception
     */
    protected function sendVoiceCall()
    {
        $this->type = 5;
        if($this->msg['called'] == $this->phone){
            $this->error = ['errCode'=>400003,'errMsg'=>'无法通知相同的两个号码'];
            return false;
        }
        $friendlyName = $this->getClient();
        if(!$friendlyName){     //获取失败就调用语言通知接口
            return $this->sendVoiceNotice();
        }
        $result = $this->sms->callBack($this->appId,$friendlyName,$this->msg['called'],'057156056290','057156056290');
        if($result){
            $json = json_decode($result);
            if(!$json || !empty($result['respCode'])){
                $this->error = ['errCode'=>$json['respCode'],'errMsg'=>''];
                return false;
            }
            return true;
        }
    }

    /**
     * 记录日志
     * @param $status
     */
    private function log($status)
    {
        $log['platform'] = 'ucpaas';
        $log['type'] = $this->type?:'';
        $log['phone'] = $this->phone?:'';
        $log['called'] = isset($this->msg['called'])?$this->msg['called']:0;     //主叫号码（只用于双方呼叫）
        $log['ip'] =  request()->ip();
        $log['uid'] = $this->msg['uid']?:0;
        $log['source'] = $this->msg['source']?:0;
        $log['status'] = $status;
        $log['errmsg'] = serialize($this->error);
        $log['day'] = date('Y-m-d');
        $log['week'] = date('Y-W');
        $log['month'] =date('Y-m');
        model('notice/SmsLog')->save($log);
    }

    /**
     * 获取client
     * @return bool
     * @throws \api\sms\Exception
     */
    public function getClient()
    {
        $client = $this->sms->getClientInfoByMobile($this->appId,$this->phone);//获取Client信息
        $json = json_decode($client,true)['resp'];
        if($json['respCode'] == '000000'){
            return $json['client']['friendlyName'];
        }elseif($json['respCode'] == '100007'){         //查无数据
            $newClient = $this->sms->applyClient($this->appId,'0','0','',$this->phone);          //注册client
            $json = json_decode($newClient,true)['resp'];
            if($json['respCode'] == '000000'){
                return $json['client']['clientNumber'];
            }
        }
        return false;
    }
}
<?php
/**
 * 发短信抽象类
 * User: coolong
 * Date: 2017/4/23
 * Time: 22:25
 */

namespace app\common\tool\sms;

class Sms
{
    protected $platform;        //短信平台
    protected $error;
    public function __construct($platform='')
    {
        $this->platform = $platform?:'SmsUcpaas';
    }

    public function getError()
    {
        return $this->error;
    }

    /**
     * 发生抽象方法
     * @param $phone
     * @param $msg
     * @return bool
     */
    public function send($phone,$msg)
    {
        $sendClass = $this->getClass();
        $result = $sendClass->send($phone,$msg);
        if($result){
            return $result;
        }else{
            $this->error =$sendClass->getError();
            return false;
        }
    }

    public function getClass()
    {
        switch ($this->platform){
            case 'SmsUcpaas': return new SmsUcpaas();
            break;
            default:return new SmsUcpaas();
            break;
        }
    }
}
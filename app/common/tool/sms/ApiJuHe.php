<?php
/**
 * Created by PhpStorm.
 * User: sinre
 * Date: 2017/6/22
 * Time: 18:18
 */

namespace app\common\tool\sms;


class ApiJuHe
{
    const OPEN_ID = 'JH0139bd535907403a120ecab6c1595e3d';
    const TEXT_URL = 'http://v.juhe.cn/sms/send';
    const VOICE_URL = 'http://op.juhe.cn/yuntongxun/voice';
    const CALLBACK_URL = 'http://op.juhe.cn/huihu/query';

    protected $phone;
    protected $msg;
    protected $ip;
    protected $unid = 0;    //双向回拨接口调用是需要的日志id
    protected $error = [];
    protected $type;    //短信类型 1验证短信 2通知短信  3语言验证码  4语言通知  5双向呼叫
    public function __construct()
    {
        $this->text_key = 'e7fc459656804c5d626c29b3c7bbc399';     //短信应用key
        $this->voice_key = '';   //语言验证应用key
        $this->callback_key = '2d6f22751d7b21e9c0d481d7f7b39ac0';   //双向回拨key
        $this->ip = request()->ip(0,true);
        $this->m_log = model('notice/SmsLog');
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

            }else{
                $this->error = ['errCode'=>400001,'errMsg'=>'电话格式错误'];
                $result = false;
            }
            $this->log($result);
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
//        return $this->sms->templateSMS($this->appId,$this->phone,$this->msg['templateId'],$param);
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
//        return $this->sms->voiceCode($this->appId,$this->msg['param']['code'],$this->phone);
    }

    /**
     * 语音通知
     * @return int
     */
    protected function sendVoiceNotice()
    {
        $this->type = 4;
//        return $this->sms->voiceNotice($this->appId,$this->phone,'18835241102',2,$this->msg['templateId'],$this->msg['param']);
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

        if(empty($this->msg['called']) || !isPhone($this->msg['called'])){
            $this->error = ['errCode'=>400002,'errMsg'=>'被叫电话格式错误或不存在'];
            return false;
        }
        $this->m_log->save(['uid'=>$this->msg['uid']]);
        $this->unid = $this->m_log->id;
        $sign = md5($this->callback_key.self::OPEN_ID.$this->unid.$this->phone.$this->msg['called']);
        $result = http_get(self::CALLBACK_URL.'?key='.$this->callback_key.'&phone='.$this->phone.'&call='.$this->msg['called'].'&unid='.$this->unid.'&sign='.$sign);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || $json['error_code']) {
                $this->error = [
                    'errCode'=>$json['error_code'],
                    'errMsg' =>$json['reason']
                ];
                return false;
            }
            return $json;
        }
    }

    /**
     * 记录日志
     * @param $status
     */
    private function log($status)
    {
        $log['platform'] = 'juhe';
        $log['type'] = $this->type;
        $log['phone'] = $this->phone;
        $log['called'] = $this->msg['called']?:0;     //主叫号码（只用于双方呼叫）
        $log['ip'] =  $this->ip;
        $log['source'] = $this->msg['source'];
        $log['status'] = $status?1:0;
        $log['errmsg'] = $this->error?serialize($this->error):'';   //错误信息
        $log['day'] = date('Y-m-d');
        $log['week'] = date('Y-W');
        $log['month'] =date('Y-m');
        if($this->unid){
            $this->m_log->where(['id'=>$this->unid])->update($log);
        }else{
            $this->m_log->save($log);
        }

    }
}
<?php

/**
 * 拨打对方电话通知
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/4/8
 * Time: 20:32
 */
namespace app\notice\controller;
use app\common\controller\Common;
use app\common\tool\sms\Sms;
class Call extends Common
{
    /**
     * 双向回拨挪车
     * @param $phone    //主叫电话
     * @param $id       //车主车牌id（用于查找被叫电话）
     * @return bool
     */
    public function move_car($phone,$id)
    {
        $m_bind_phone = model('card/BindPhone');
        $call = $m_bind_phone->getPhone($id,true);       //获取被叫电话

        $m_blacklist = model('notice/SmsBlacklist');
        $confine = $m_blacklist->is_confine($phone.$call);      //验证黑名单

        if($confine){
            return ['errCode'=>1,'errMsg'=>'请求异常请稍后再试'];
        }

        $sms = new Sms();
        $msg = [
            'type'=>'voice_call',       //通知类型
            'templateId' => '42777',    //模板id(短信接口使用)
            'param'=>[                  //模板变量参数
                'code'=>'020251',
            ],
            'called'=>$call,            //被叫号码
            'uid'=>$this->user['id'],   //用户id
            'source'=>5100,             //资源消耗渠道
        ];
        $result = $sms->send($phone,$msg);
        dump($sms->getError());die;
        return $result;
    }

}
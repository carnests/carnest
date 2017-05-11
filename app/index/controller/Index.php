<?php
namespace app\index\controller;
use app\common\controller\Common;
use app\common\tool\sms\Sms;
class Index extends Common
{
    public function index()
    {
        $sms = new Sms();
        $msg = [
            'type'=>'voice_notice',
            'templateId' => '42777',
            'param'=>[
                'code'=>'020251',
            ],
            'called'=>'15931086078',
            'uid'=>1,
            'source'=>1
        ];
        $result = $sms->send(18830102006,$msg);
        dump($result);die;
        return $this->fetch();
    }

    public function home()
    {
        return $this->fetch('index');
    }
}

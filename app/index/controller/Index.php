<?php
namespace app\index\controller;
use app\common\controller\Common;
use app\common\tool\sms\Sms;
class Index extends Common
{
    public function index()
    {
        return false;
        $sms = new Sms('juhe');
        $msg = [
            'type'=>'voice_call',
            'templateId' => '42777',
            'param'=>[
                'code'=>'020251',
            ],
            'called'=>'18830102006',
            'uid'=>1,
            'source'=>1,
            'unid'=>rand(1,100)
        ];
        $result = $sms->send(18830102005,$msg);

        dump($result);die;
        return $this->fetch();
    }

    public function home()
    {
        return $this->fetch('index');
    }
}

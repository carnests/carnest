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
            'type'=>'text',
            'templateId' => '42777',
            'param'=>[
                'code'=>'020251',
            ]
        ];
        $result = $sms->send(1883010200,$msg);
        dump($sms->getError());die;
        return $this->fetch();
    }

    public function home()
    {
        return $this->fetch('index');
    }
}

<?php
namespace app\index\controller;
use app\common\controller\Common;
use app\common\tool\sms\Sms;
use think\Config;

class Index extends Common
{
    public function index()
    {
        Config::set('default_return_type','html');
        if(request()->isPost()){
            $info = input('post.info/a');

            if(empty($info['to']) || empty($info['my'])){
                return ['errCode'=>1,'errMsg'=>'请填写手机号码'];
            }

            $info['to'] = str_replace('-','',$info['to']);
            $info['my'] = str_replace('-','',$info['my']);

            $sms = new Sms('ucpaas');
            $msg = [
                'type'=>'voice_call',
                'templateId' => '42777',
                'param'=>[
                    'code'=>'020251',
                ],
                'called'=>$info['to'],
                'uid'=>0,
                'source'=>1,
                'unid'=>rand(1,100)
            ];
            $result = $sms->send($info['my'],$msg);
            if($result){
                return $result;
            }else{
                return $sms->getError();
            }
        }else{
            return $this->fetch('index');
        }
    }

    public function home()
    {
        return $this->fetch('index');
    }
}

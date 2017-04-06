<?php
/**
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/4/7
 * Time: 1:24
 */

namespace app\auth\controller;
use think\Controller;
use EasyWeChat\Foundation\Application;
use think\Request;

class Common extends Controller
{
    protected $wechat;
    protected $oauth;
    protected $user;
    public function _initialize()
    {
        //dump();
        $config = [
            'oauth' => [
                'scopes'   => ['snsapi_userinfo'],
                'callback' => url('auth/Common/callback','','',true),
            ],
            'app_id'  => 'wxdcc677f0fab756ed',                  // AppID
            'secret'  => '28e0f7453abe4872ea0a191f57795224',    // AppSecret
            'token'   => 'carnest',                             // Token
            'aes_key' => '',                                    // EncodingAESKey，安全模式下请一定要填写！！！
        ];
        $this->wechat = new Application($config);
        $this->oauth = $this->wechat->oauth;
        $this->user = session('user');
        self::auth();
    }

    private function auth()
    {
        $c_name = strtolower(Request::instance()->controller());
        $a_name = strtolower(Request::instance()->action());
        if($c_name=='common' && in_array($a_name, array('callback','add_user'))) return true;

        if(empty($this->user['id'])){
            $this->oauth->redirect()->send();
        }
    }

    public function callback()
    {
        $user = $this->oauth->user();
        $this->add_user($user->getOriginal());
        $this->redirect('index/Index/index');
    }

    private function add_user($user)
    {
        $m_user = model('user/User');
        $user_data = $m_user->is_exist($user['openid']);
        if(!$user_data){
            $user_data = $user;
            $user_data['id'] = $m_user->add($user);
        }
        session('user',$user_data);
    }
}
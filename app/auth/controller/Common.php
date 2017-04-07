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
    protected $index_url;   //跳转地址
    public function _initialize()
    {
        $this->index_url = url('index/Index/index');
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
//            $last_url = PROTOCOL.$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"];
//            if($last_url)session('last_url',$last_url);
            $this->oauth->redirect()->send();
        }
    }

    /**
     * 回调地址
     */
    public function callback()
    {
        $user = $this->oauth->user();
        $this->add_user($user->getOriginal());
//        if(session('last_url')){
//            $this->redirect(session('last_url'));
//        }
        $this->redirect($this->index_url);
    }

    /**
     * 添加用户信息
     * @param $user
     */
    private function add_user($user)
    {
        $m_user = model('user/User');
        $user_data = $m_user->is_exist($user['openid']);
        if(!$user_data){
            $user_data = $user;
            $user_data['id'] = $m_user->add($user);
        }else{
            $m_user->update_lastlogin($user_data['id']);
        }
        session('user',['id'=>$user_data['id'],'openid'=>$user_data['openid']]);
    }
}
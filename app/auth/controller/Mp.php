<?php
/**
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/4/7
 * Time: 1:24
 */

namespace app\auth\controller;
use think\Controller;
use app\common\tool\wechat\EasyWechat;
use think\Request;

class Mp extends Controller
{
    protected $wechat;
    protected $oauth;
    protected $user;
    protected $index_url;   //跳转地址
    public function __construct()
    {
        parent::__construct();
        $this->index_url = url('index/Index/index');

        $this->wechat = EasyWechat::mp();
        $this->oauth = $this->wechat->oauth;
        $this->user = session('user');
        $this->user['id'] =2;
        //self::auth();
    }

    private function auth()
    {
        $c_name = strtolower(Request::instance()->controller());
        $a_name = strtolower(Request::instance()->action());
        if($c_name=='mp' && in_array($a_name, array('callback','add_user'))) return true;

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
        action('user/User/add_user',[$user->getOriginal()]);     //添加用户信息
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
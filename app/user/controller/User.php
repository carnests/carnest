<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/8 0008
 * Time: 18:53
 */
namespace app\user\controller;
use app\common\controller\Common;
class User extends Common
{
    protected $db_oa;
    protected $db_xcx;
    public function __construct()
    {
        parent::__construct();
        $this->db_oa = model('user/UserOa');
        $this->db_xcx = model('user/UserXcx');
    }

    /**
     * 获取添加用户信息
     * @param $user
     * @param string $platform      //平台信息默认公众号  oa-公众号  xcx-小程序
     */
    public function add_user($user,$platform='oa')
    {
        if($platform=='oa'){
            $this->add_user_oa($user);
        }elseif ($platform=='xcx'){
            $this->add_user_xcx($user);
        }

    }

    /**
     * 添加微信公众号用户信息
     * @param $user
     */
    private function add_user_oa($user){
        $user_data = $this->db_oa->is_exist($user['openid']);
        if(!$user_data){
            $user_data = $user;
            $user_data['id'] = $this->db_oa->add($user);
        }else{
            $this->db_oa->update_lastlogin($user_data['id']);
        }
        session('user',['id'=>$user_data['id'],'openid'=>$user_data['openid']]);
    }

    private function add_user_xcx($user)
    {
        $user_data = $this->db_xcx->is_exist($user['openId']);
        if(!$user_data){
            $user_data = $user;
            $user_data['id'] = $this->db_xcx->add($user);
        }else{
            $this->db_xcx->update_lastlogin($user_data['id']);
        }
    }

}
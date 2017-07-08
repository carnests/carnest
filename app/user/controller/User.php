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
    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = model('user/User');
    }

    /**
     * 获取添加用户信息
     * @param $user
     */
    public function add_user($user)
    {
        $user_data = $this->db->is_exist($user['openid']);
        if(!$user_data){
            $user_data = $user;
            $user_data['id'] = $this->db->add($user);
        }else{
            $this->db->update_lastlogin($user_data['id']);
        }
        session('user',['id'=>$user_data['id'],'openid'=>$user_data['openid']]);
    }


}
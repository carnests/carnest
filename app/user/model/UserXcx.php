<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/23 0023
 * Time: 0:24
 */

namespace app\user\model;
use think\Model;

class UserXcx extends Model
{
    protected $table = 'carnest_user_xcx';
    protected $pk = 'id';
    // 关闭自动写入update_time字段
    protected $updateTime = false;
    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * 添加用户信息
     * @param array $user
     * @return false|int
     */
    public function add($user=[])
    {
        $user['base64name'] = base64_encode($user['nickName']);
        $result =$this->allowField(true)->save($user);
        return $result?$this->id:0;
    }

    /**
     * 判断用户是否存在
     * @param $openid
     * @param string $field     追加要查询的字段
     * @return array|false|\PDOStatement|string|Model
     */
    public function is_exist($openid,$field='')
    {
        if($field)$field = ','.$field;
        return $this->where(['openId'=>$openid])->field('id,openId'.$field)->find();
    }

    /**
     * 更新最后登陆时间
     * @param $id
     * @return $this
     */
    public function update_lastlogin($id)
    {
        return $this->where(['id'=>$id])->update(['lastlogin'=>date('Y-m-d H:i:s')]);
    }

    public function getIdBy3RdSession($session_3rd)
    {
        if($session_3rd && $session_3rd != 'undefined'){
            $session_3rd = cache($session_3rd);
        }else{
            $this->error = ['errCode'=>1,'errMsg'=>'数据异常'];
            return false;
        }
        $openId = getOpenId($session_3rd);
        return $this->where(['openId'=>$openId])->value('id');
    }
}
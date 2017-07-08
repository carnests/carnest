<?php

/**
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/4/7
 * Time: 0:05
 */
namespace app\user\model;
use think\Model;
class User extends Model
{
    protected $table = 'carnest_user';
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
        return $this->where(['openid'=>$openid])->field('id,openid',$field)->find();
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
}
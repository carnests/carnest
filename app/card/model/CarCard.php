<?php

/**
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/4/8
 * Time: 11:33
 */
namespace app\card\model;
use app\common\model\Common;
use think\Loader;
class CarCard extends Common
{
    protected $table = 'carnest_car_card';
    protected $pk = 'id';
    // 关闭自动写入update_time字段
    protected $updateTime = false;

    protected $db_bing_phone;   //手机绑定表
    protected $db_user;   //用户表
    protected function initialize()
    {
        parent::initialize();
        $this->db_bing_phone = model('card/BindPhone');
        $this->db_user = model('user/User');
    }

    public function bind($info)
    {
        if($this->is_bing($info['id'])){
            $this->error = '该卡已经被绑定';
            return false;
        }
        if($this->license_plate($info['license_plate'])){
            $this->error = '该车牌已经绑定';
            return false;
        }
        $Validate = Loader::validate('card/CarCard');
        if(!$Validate->check($info)){
            $this->error = $Validate->getError();
            return false;
        }
        $info['bind_time'] = date('Y-m-d H:i:s');

        if(!$this->db_bing_phone->add($info['id'],$info['phone'],true)){     //绑定手机
            $this->error = $this->db_bing_phone->getError();
            return false;
        }
        $result = $this->where(['id'=>$info['id']])->update($info);
        return $result;
    }

    /**
     * 是否存在没有绑定的卡
     * @param $uid
     * @return mixed
     */
    public function is_idle($uid)
    {
        return $this->where(['uid'=>$uid,'status'=>0])->value('id');
    }

    /**
     * 卡片初始化
     * @param $uid
     * @return bool|mixed
     */
    public function initial($uid)
    {
        $result = $this->save(['uid'=>$uid]);
        return $result?$this->id:false;
    }

    public function is_bing($id)
    {
        return $this->where(['id'=>$id,'status'=>0])->value('uid,phone');
    }

    /**
     * 查询车牌是否存在
     * @param $license_plate    车牌
     * @return mixed
     */
    public function license_plate($license_plate)
    {
        return $this->where(['license_plate'=>$license_plate])->value('id');
    }

    /**
     * 通过id查询车辆和车主信息
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function getCarInfo($id)
    {
        $data = $this->where(['id'=>$id])->field('uid,license_plate')->find();
        $data['openid'] = $this->db_user->where(['id'=>$data['uid']])->value('openid');
        return $data;
        //return $this->alias('c')->where(['c.id'=>$id])->join('__USER__ u ON c.uid=u.id')->field('openid,license_plate')->();
    }
}
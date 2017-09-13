<?php

/**
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/4/8
 * Time: 11:33
 */
namespace app\card\model;
use app\common\model\Common;
use think\Config;
use think\Loader;
class CarCard extends Common
{
    protected $table = 'carnest_car_card';
    protected $pk = 'id';
    // 关闭自动写入update_time字段
    protected $updateTime = false;

    protected $db_user;         //用户表
    protected $card_num;        //可绑定车辆数
    protected function initialize()
    {
        parent::initialize();
        $this->db_user = model('user/UserXcx');
        $this->card_num = 3;
    }

    /**
     * 绑定车牌
     * @param mixed $info
     * @return $this|bool|false|int
     */
    public function bindCard($info)
    {

        $Validate = Loader::validate('card/CarCard');       //验证字段信息
        if(!$Validate->check($info)){
            $this->error = $Validate->getError();
            return false;
        }

        if(!$this->cardLimit($info['uid'])){
            $this->error = '已达绑定车辆上线';
            return false;
        }

        if(isset($info['id'])){
            if($this->isBing($info['id'])){
                $this->error = '该卡已经被绑定';
                return false;
            }
        }

        $info['card'] = strtoupper($info['card']);
        $province = array_values(Config::get('location.PROVINCE'))[$info['province_id']]['name'];
        $info['license_plate'] = $province.'·'.$info['card'];
        if($this->licensePlate($info['license_plate'])){
            $this->error = '该车牌已经绑定';
            return false;
        }
        $info['city_id'] = substr($info['card'],0,1);
        $info['bind_time'] = date('Y-m-d H:i:s');
        $info['status'] = 1;

        if(isset($info['id'])){
            $result = $this->where(['id'=>$info['id']])->update($info);
        }else{
            $result = $this->save($info);
        }

        return $result;
    }

    /**
     * 判断是否已经被绑定
     * @param $id
     * @return mixed
     */
    public function isBing($id)
    {
        return $this->where(['id'=>$id,'status'=>1])->value('uid');
    }

    /**
     * 查询车牌是否存在
     * @param $license_plate    //车牌
     * @return mixed
     */
    public function licensePlate($license_plate)
    {
        return $this->where(['license_plate'=>$license_plate])->value('id');
    }


    public function cardLimit($uid)
    {
        $count = $this->where(['uid'=>$uid,'status'=>1])->count();
        if($count >= $this->card_num){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 通过id查询车辆和车主信息
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function getCarInfo($id)
    {
        $data = $this->where(['id'=>$id])->find();
        $data['openId'] = $this->db_user->where(['id'=>$data['uid']])->value('openId');
        return $data;
    }

    public function getCarPhone($id)
    {
        return $this->where(['id'=>$id])->value('phone');
    }
}
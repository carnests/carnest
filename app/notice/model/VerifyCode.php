<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/10 0010
 * Time: 22:34
 */

namespace app\notice\model;
use app\common\model\Common;

class VerifyCode extends Common
{
    protected $table = 'carnest_phone_verify_code';
    protected $pk = 'id';
    // 关闭自动写入update_time字段
    protected $updateTime = false;
    protected $expires;
    protected function initialize()
    {
        parent::initialize();
        $this->expires = 10*60;
    }

    /**
     * 生成手机验证码
     * @param $phone
     * @param int $type  发送短信类型  1注册
     * @param int $uid
     * @return bool|mixed
     */
    public function make_code($phone,$type=0,$uid=0)
    {
        if(!isPhone($phone)){
            $this->error = ['errCode'=>1,'errMsg'=>'电话号码错误'];
            return false;
        }

        if($code = $this->code_is_exist($phone)){
            return $code;
        }
        $add['code'] = $code = rand(100000,999999);
        $add['phone'] = $phone;
        $add['type'] = $type;
        $add['uid'] = $uid;
        $result = $this->save($add);
        if($result){
            return $code;
        }else{
            $this->error = ['errCode'=>1,'errMsg'=>'网络异常'];
            return false;
        }
    }

    /**
     * 验证手机验证码是否已经生产
     * @param $phone
     * @param int $type
     * @return bool|mixed
     */
    public function code_is_exist($phone,$type=0)
    {
        $where['phone'] = $phone;
        $where['state'] = 0;
        if($type){
            $where['type'] = $type;
        }
        $data = $this->where($where)->field('id,code,create_time')->find();
        if($data){
            if(strtotime($data['create_time'])+$this->expires < time()){        //如果过期了重新生成
                $save['code'] = rand(100000,999999);
            }
            $save['create_time'] = date('Y-m-d H:i:s');
            $this->where(['id'=>$data['id']])->update($save);
            return isset($save['code'])?$save['code']:$data['code'];
        }
        return false;
    }

    public function check_code($phone,$code)
    {
        if(!isPhone($phone)){
            $this->error = ['errCode'=>1,'errMsg'=>'电话号码错误'];
            return false;
        }
        $where['phone'] = $phone;
        $where['state'] = 0;
        $data = $this->where($where)->field('id,code,create_time')->find();
        if($data){
            if($code == $data['code']){
                if(strtotime($data['create_time'])+$this->expires > time()){
                    $this->where(['id'=>$data['id']])->update(['state'=>1,'check_time'=>date('Y-m-d H:i:s')]);
                    return true;
                }else{
                    $this->error = ['errCode'=>1,'errMsg'=>'验证码已过期，请重新获取'];
                }
            }else{
                $this->error = ['errCode'=>1,'errMsg'=>'验证码错误'];
            }
        }else{
            $this->error = ['errCode'=>1,'errMsg'=>'网络异常'];
        }
        return false;
    }
}
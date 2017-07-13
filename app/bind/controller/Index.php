<?php

/**
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/4/8
 * Time: 10:32
 */
namespace app\bind\controller;
use app\common\controller\Common;
use app\common\tool\location\BaiduMap;
use think\Config;

class Index extends Common
{
    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = model('card/CarCard');
    }

    public function bind($info,$id=0)
    {
        //验证手机验证码
        $m_verify_code = model('notice/VerifyCode');
        $m_verify_code->startTrans();
        try{
            if(!$m_verify_code->check_code($info['phone'],$info['code'])){
                return $m_verify_code->getError();
            }
            if($id){       //二维码id
                $info['id'] = $id;
            }
            unset($info['code']);
            $info['uid'] = $this->user['id'];
            $result = $this->db->bindCard($info);
            if($result){
                $m_verify_code->commit();
                return ['errCode'=>0,'errMsg'=>'绑定成功'];
            }else{
                $m_verify_code->rollback();
                $error = $this->db->getError();
                return ['errCode'=>1,'errMsg'=>$error?:'操作异常'];
            }
        }catch (Exception $e) {
            $m_verify_code->rollback();
        }


    }

    /**
     * 获取地理位置
     */
    public function get_location()
    {
        $lat = input('lat');
        $lng = input('lng');
        $map = new BaiduMap();
        $result = $map->province($lat.",".$lng,3);
        if($result){
            $province = Config::get('location.PROVINCE');
            return [
                'location'=>(int)$province[$result]['id'],  //获取的地理位置id
                'data'=>array_values($province),            //所有省份信息
                'name'=>array_column($province,'name')      //所有省份名称
            ];
        }else{
            return $map->getError();
        }

    }
}
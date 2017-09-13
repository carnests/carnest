<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/16 0016
 * Time: 21:38
 */

namespace app\card\controller;
use app\common\controller\Common;
class Index extends Common
{
    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = model('card/CarCard');
    }

    /**
     * 获取车辆列表
     * @return array
     */
    public function lists($session_3rd){
        $data = $this->db->where(['uid'=>$this->userId])->select();
        return ['errCode'=>0,'data'=>$data];
    }

    /**
     * 车辆信息详情
     * @param $session_3rd
     * @param $card_id
     * @return array
     */
    public function card_show($session_3rd,$card_id)
    {
        $data = $this->db->where(['id'=>$card_id])->find();
        return ['errCode'=>0,'data'=>$data];
    }

    /**
     * 通过扫描信息获取二维码状态
     * @param $scene    //二维码信息
     * @return array
     */
    public function get_code_state($session_3rd,$scene)
    {
        $data = $this->db->where(['id'=>$scene])->find();

        if(!$this->userId){
            return ['errCode'=>1,'errMsg'=>'session_3rd异常'];        //数据异常
        }

        if(!$data){
            $type = 0;
            return ['errCode'=>1,'data'=>$type];        //数据异常
        }
        if($data['status']){
            if($data['uid']==$this->userId){
                $type = 3;      //自己扫自己的码
            }else{
                $type = 2;      //挪车请求
                $phone = $this->db_user_xcx->where(['id'=>$this->user['id']])->value('phone');
            }
        }else{
            $type = 1;          //未绑定
        }
        return ['errCode'=>0,'data'=>$type,'phone'=>isset($phone)?$phone:''];
    }

}
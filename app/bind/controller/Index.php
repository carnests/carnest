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
class Index extends Common
{
    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = model('card/CarCard');
    }

    /**
     * 绑定页面
     * @return mixed
     */
    public function index()
    {
        $id = input('id');
        return $this->fetch('index',['id'=>$id]);
    }

    /**
     * 生成新的车卡
     * @param $uid
     * @return mixed
     */
    public function make_card($uid)
    {
        $id = $this->db->is_idle($uid);
        if(!$id){
            $id = $this->db->initial($uid);
        }
        return $id;
    }

    public function bind()
    {
        $info = input('info/a');
        $id = input('id');
        if(!$id){       //二维码id
            $info['id'] = $this->make_card($this->user['id']);
        }else{
            $info['id'] = $id;
        }
        $result = $this->db->bind($info);
        if($result){
            return ['errCode'=>0,'errMsg'=>'绑定成功'];
        }else{
            $error = $this->db->getError();
            dump($error);exit;
            //dump(['errCode'=>1,'errMsg'=>$error?:'操作异常']);exit;
            return ['errCode'=>1,'errMsg'=>$error?:'操作异常'];
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
            return ['data'=>$result];
        }else{
            return $map->getError();
        }

    }
}
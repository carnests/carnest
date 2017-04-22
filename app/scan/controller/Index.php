<?php

/**
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/4/8
 * Time: 20:05
 */
namespace app\scan\controller;
use app\common\controller\Common;
class Index extends Common
{
    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = model('card/CarCard');
    }

    public function index()
    {
        $id = input('id');
        $type = 0;
        if($card = $this->db->is_bind($id)){
            $type = 1;      //二维码已被绑定
            if($card['uid'] == $this->user['id']){
                $type = 2;      //判断是否为车主扫的
            }
        }
        return $this->fetch('scan/index',['id'=>$id,'type'=>$type]);
    }
}
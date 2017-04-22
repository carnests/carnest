<?php

/**
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/4/8
 * Time: 21:27
 */
namespace app\notice\controller;
use app\auth\controller\Common;
use EasyWeChat\Message\Text;
class Wechat extends Common
{
    protected $db_car_card;     //二维码表
    protected $staff;   //客服
    public function __construct()
    {
        parent::__construct();
        $this->db_car_card = model('card/CarCard');
        $this->staff = $this->wechat->staff;
    }

    public function move_car()
    {
        dump($this->staff->lists());exit;

        $id = 1;
        $car = $this->db_car_card->getCarInfo($id);
        //dump($car['license_plate']);exit;
        $message = '尊敬的用户，你的车辆（'.$car['license_plate'].'）由于停放位置欠妥，已经妨碍到其他车主的同行，请即使前往挪动！感谢您的配合。';
        //$message = new Text(['content' => $text]);
        $this->staff->message($message)->to($car['openid'])->send();
        echo"ok";exit;

    }
}
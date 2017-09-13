<?php
/**
 * 违章查询
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/30 0030
 * Time: 17:20
 */

namespace app\card\controller;
use api\juhe\Juhe;
use app\common\controller\Common;

class IllegalQuery extends Common
{
    protected $db;
    protected $db_car_card;
    protected $db_illegal_query_norm;
    public function __construct()
    {
        parent::__construct();
        $this->db = model('card/IllegalRecord');
        $this->db_car_card = model('card/CarCard');
        $this->db_illegal_query_norm = model('card/IllegalQueryNorm');
    }

    /**
     * 违章记录信息
     * @param $session_3rd
     * @param $card_id              //车牌id
     * @param string $engineno      //发动机号
     * @param string $classno       //大架号
     * @return array
     */
    public function record($session_3rd,$card_id,$engineno='',$classno='')
    {
        $card = $this->db_car_card->getCarInfo($card_id);
        if($card['uid'] != $this->userId){
            return ['errCode'=>1,'errMsg'=>'数据异常'];
        }

        $record = $this->db->getRecordInfo($card_id);
        if($record['update_time'] < date('Y-m-d').' 00:00:00'){      //检查更新时间
            if(!$card['is_perfect'] && !$engineno && !$classno){       //检查有没有完善过信息
                $data = $this->db_illegal_query_norm->where(['id'=>$card['city']])->find();
                return ['errCode'=>2,'data'=>$data];        //请填写相关查询信息，并返回数据规范信息
            }

            $data = $this->get_record($card_id,$engineno,$classno);
        }else{
            $data = unserialize($record['record'])?:[];
        }
        return ['errCode'=>0,'data'=>$data];
    }

    /**
     * 实时获取违章记录
     * @param $card_id
     * @return array
     */
    private function get_record($card_id,$engineno='',$classno='')
    {
        $card = $this->db_car_card->getCarInfo($card_id);
        $norm = $this->db_illegal_query_norm->getNormInfo($card['city']);
        $juhe = new Juhe();
        $license_plate = str_replace('·','',$card['license_plate']);        //过滤车牌中的·

        if(!$engineno && !$classno){        //判断前段是否有传发动机号或大架号
            $engineno = $card['engineno'];
            $classno = $card['vin'];
        }

        $data = $juhe->wz_query($norm['code'],$license_plate,$engineno,$classno,$card['card_type']);

        $this->db->updateRecord($card_id,$data['lists']);       //更新记录信息

        if($data){
            return $data['lists'];
        }else{
            return [];
        }
    }
}
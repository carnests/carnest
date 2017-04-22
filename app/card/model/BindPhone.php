<?php
/**
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/4/8
 * Time: 18:03
 */

namespace app\card\model;
use app\common\model\Common;
use think\Loader;

class BindPhone extends Common
{
    protected $table = 'carnest_bind_phone';
    protected $pk = 'id';
    // 关闭自动写入update_time字段
    protected $updateTime = false;
    protected $phone_limit;     //电话绑定个数上线
    protected function initialize()
    {
        parent::initialize();
        $this->phone_limit = 3;
    }

    /**
     * 绑定手机号
     * @param $card_id
     * @param $phone
     * @param bool $owner       是否为主人（绑定的第一人）
     * @return array|bool|false|int
     * @throws \Exception
     */
    public function add($card_id,$phone,$owner=false)
    {
        if(is_array($phone)){
            $add = [];
            foreach ($phone as $k=>$v){
                if(!$this->is_exist($card_id,$v)){
                    $add[$k]['card_id'] = $card_id;
                    $add[$k]['phone'] = $v;
                }
            }

            $result = $this->saveAll(array_values($add));
        }else{
            if($this->bind_num($phone) >= $this->phone_limit){
                $this->error = '已经达到手机号码数绑定上线';
                return false;
            }
            if($this->is_exist($card_id,$phone)){
                $this->error = '手机号码已经被绑定';
                return false;
            }
            $result = $this->save(['card_id'=>$card_id,'phone'=>$phone,'owner'=>$owner?1:0]);
        }
        return $result;
    }

    /**
     * 判断该手机号是否被绑定
     * @param $card_id
     * @param $phone
     * @return int|string
     */
    public function is_exist($card_id,$phone)
    {
        return $this->where(['card_id'=>$card_id,'phone'=>$phone])->count();
    }

    /**
     * 获取手机号
     * @param $card_id
     * @param bool $owner
     * @return array|mixed
     */
    public function getPhone($card_id,$owner=false)
    {
        if($owner){
            $result = $this->where(['card_id'=>$card_id,'owner'=>1])->value('phone');
        }else{
            $result = $this->where(['card_id'=>$card_id])->column('phone','id');
        }

        return $result;
    }

    public function bind_num($phone)
    {
        return $this->where(['phone'=>$phone])->count();
    }
}
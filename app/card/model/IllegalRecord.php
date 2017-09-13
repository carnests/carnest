<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/30 0030
 * Time: 18:23
 */

namespace app\card\model;
use app\common\model\Common;

class IllegalRecord extends Common
{
    protected $table = 'carnest_illegal_record';
    protected $pk = 'id';

    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * 更新违章记录信息
     * @param $card_id
     * @param $record
     * @return $this|false|int
     */
    public function updateRecord($card_id,$record)
    {
        $id = $this->where(['card_id'=>$card_id])->value('id');
        if($id){
            $result = $this->where(['id'=>$id])->update(['record'=>serialize($record)]);
        }else{
            $add['card_id'] = $card_id;
            $add['record'] = serialize($record);
            $add['update_time'] = date('Y-m-d H:i:s');
            $result = $this->save($add);
        }
        return $result;
    }

    public function getRecordInfo($card_id){
        return $this->where(['card_id'=>$card_id])->find();
    }
}
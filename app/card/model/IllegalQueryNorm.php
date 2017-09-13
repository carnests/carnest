<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/30 0030
 * Time: 18:23
 */

namespace app\card\model;
use app\common\model\Common;

class IllegalQueryNorm extends Common
{
    protected $table = 'carnest_illegal_query_norm';
    protected $pk = 'id';

    protected function initialize()
    {
        parent::initialize();
    }

    public function getNormInfo($city)
    {
        return $this->where(['id'=>$city])->find();
    }
}
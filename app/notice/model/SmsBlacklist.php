<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/22 0022
 * Time: 23:59
 */

namespace app\notice\model;
use app\common\model\Common;

class SmsBlacklist extends Common
{
    protected $table = 'carnest_sms_blacklist';
    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * 拉黑
     */
    public function defriend()
    {

    }

    /**
     * 是否在黑名单上
     * @param $blacklist
     * @return int|string
     */
    public function is_confine($blacklist)
    {
        return $this->where(['blacklist'=>$blacklist])->value('blacklist');
    }
}
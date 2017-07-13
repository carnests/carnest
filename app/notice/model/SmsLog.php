<?php

/**
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/5/8
 * Time: 21:45
 */
namespace app\notice\model;
use app\common\model\Common;
class SmsLog extends Common
{
    protected $table = 'carnest_sms_log';
    protected $pk = 'id';

    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * 验证每日上限
     * @param $phone
     * @param $type
     * @return bool
     */
    public function dailyLimit($phone,$type)
    {
        switch($type){
            case 1:
                return $this->VerifyTextDailyLimit($phone);
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * 验证短信每日限制
     * @param $phone
     * @return bool
     */
    public function VerifyTextDailyLimit($phone)
    {
        $count = $this->where(['phone'=>$phone,'day'=>date('Y-m-d'),'type'=>1])->count();
        if($count >= 5){
            $this->error = ['errCode'=>1,'errMsg'=>'今日短信验证码获取已达上限'];
            return false;
        }else{
            return true;
        }
    }
}
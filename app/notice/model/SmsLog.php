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
}
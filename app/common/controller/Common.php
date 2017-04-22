<?php

/**
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/4/6
 * Time: 22:41
 */
namespace app\common\controller;
use app\auth\controller\Mp;
class Common extends Mp
{
    public function __construct()
    {
        parent::__construct();
        $this->indexDate();
    }

    protected function indexDate()
    {
        $this->assign([
            'title' => '车巢网',
            'public_base' => '../app/common/view/base.html',
            'js' => $this->wechat->js,
        ]);
    }
}
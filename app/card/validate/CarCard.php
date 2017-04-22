<?php

/**
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/4/8
 * Time: 11:49
 */
namespace app\card\validate;
use app\common\validate\Common;
class CarCard extends Common
{
    protected $rule = [
        'id' => 'require',
        'card|车号'  =>  'require',
        'phone|手机号码' =>  'require|regex:/^1[345789][0-9]{9}$/',
    ];

    protected $message = [
        'id.require' => '操作异常',
        'card.require'  =>  '请填写车号',
        'phone.require'  =>  '请输入手机号码',
        'phone.regex'  =>  '手机号码格式错误',
    ];
}
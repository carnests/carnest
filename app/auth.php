<?php
/**
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/4/4
 * Time: 18:23
 */

return [
    '__pattern__'   => [
        'name' => '\w+',
    ],

    'auth/wechat/[:echostr]/:signature/:timestamp/:nonce'       => ['auth/Wechat/index',['method'=>'get']],
];
<?php

/**
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/4/4
 * Time: 17:32
 */
namespace app\common\tool\wechat;
use EasyWeChat\Foundation\Application;

class EasyWechat
{
    public function __construct()
    {

    }

    /**
     * 微信公众平台
     */
    public static function mp()
    {
        $options = [
            'debug' => true,                                    //当值为 false 时，所有的日志都不会记录
            /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id'  => 'wxa484dbfefb7df06d',                  // AppID
            'secret'  => '9a367be9b0807e8122080ee738bd11b0',    // AppSecret
            'token'   => 'carnest',                             // Token
            'aes_key' => '',                                    // EncodingAESKey，安全模式下请一定要填写！！！

            /**
             * 日志配置
             */
            'log' => [
                'level'      => 'debug',
                'permission' => 0777,
                'file'       => LOG_PATH.'wechat/'.date('Ym').'/'.date('d').'.log',
            ],
        ];
        return new Application($options);
    }
}
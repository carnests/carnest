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
            'app_id'  => 'wxdcc677f0fab756ed',                  // AppID
            'secret'  => '28e0f7453abe4872ea0a191f57795224',    // AppSecret
            'token'   => 'carnest',                             // Token
            'aes_key' => '',                                    // EncodingAESKey，安全模式下请一定要填写！！！

            /**
             * OAuth 配置
             *
             * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
             * callback：OAuth授权完成后的回调页地址
             */
            'oauth' => [
                'scopes'   => ['snsapi_userinfo'],
                'callback' => url('auth/mp/callback'),
            ],

            /**
             * 日志配置
             */
            'log' => [
                'level'      => 'debug',
                'permission' => 0777,
                'file'       => LOG_PATH.'wechat/'.date('Ym').'/'.date('d').'.log',
            ],

            /**
             * Guzzle 全局设置
             *
             * 更多请参考： http://docs.guzzlephp.org/en/latest/request-options.html
             */
            'guzzle' => [
                'timeout' => 3.0, // 超时时间（秒）
                //'verify' => false, // 关掉 SSL 认证（强烈不建议！！！）
            ],
        ];
        return new Application($options);
    }
}
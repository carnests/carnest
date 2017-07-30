<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/25 0025
 * Time: 21:54
 */

namespace api\juhe;
class Juhe
{
    const WZ_URL = 'http://v.juhe.cn/wz/query/';     //违章查询
    const WZ_KEY = 'f46ebd34a518335857c76b36faf1c733';  //违章查询key

    public function __construct()
    {

    }

    public function wz_query()
    {
        $url = self::WZ_URL.'?city=HB_SJZ&hphm=冀A160P7&engineno=&classno=5529&key='.self::WZ_KEY;
        $result = http_get($url);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || (isset($json['error_code']) && $json['error_code']>0)) {
                $this->errorMsg($json);
                return false;
            }
            return $json['result'];
        }
        return false;
    }
}
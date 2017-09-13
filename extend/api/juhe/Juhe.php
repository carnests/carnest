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
    const WZ_URL = 'http://v.juhe.cn/wz/query';     //违章查询
    const WZ_KEY = 'f46ebd34a518335857c76b36faf1c733';  //违章查询key

    protected $error;
    public function __construct()
    {

    }

    public function getError()
    {
        return $this->error;
    }

    /**
     * @param $coty         //城市代码
     * @param $hphm         //车牌号   （冀A160P7）
     * @param $engineno     //发动机号
     * @param $classno      //大架号
     * @param $hpzl         //号牌类型默认为02  暂只支持小型车
     * @return bool
     */
    public function wz_query($coty,$hphm,$engineno,$classno,$hpzl='02')
    {
        $url = self::WZ_URL.'?city='.$coty.'&hphm='.urlencode($hphm).'&engineno='.$engineno.'&classno='.$classno.'&hpzl='.$hpzl.'&key='.self::WZ_KEY;
        $result = http_get($url);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || (isset($json['error_code']) && $json['error_code']>0)) {
                $this->error = ['errCode'=>$json['error_code'],'errMsg'=>$json['reason']];
                return false;
            }
            return $json['result'];
        }
        return false;
    }
}
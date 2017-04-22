<?php
/**
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/4/22
 * Time: 11:02
 */

namespace api\baidu;


class Map
{
    const OUTPUT = 'json';                                      //接收格式
    const BASE_URL = 'http://api.map.baidu.com/';
    const GEOCONV = 'geoconv/v1/';                              //坐标转换
    const GEOCODER = 'geocoder/v2/';                            //坐标与地理位置互转



    protected $key;                                             //百度api key
    protected $error;                                           //错误信息
    public function __construct($options)
    {
        $this->key = isset($options['key'])?"&ak=".$options['key']:'';
    }

    public function getError()
    {
        return $this->error;
    }

    /**
     * 坐标转换
     * @param $coord    坐标
     * @param int $from 坐标类型，具体参考：http://lbsyun.baidu.com/index.php?title=webapi/guide/changeposition
     * @return bool
     */
    public function geoconv($coord,$from=1)
    {
        if(is_array($coord)){
            $coord = implode(';',$coord);
        }
        $url = self::BASE_URL.self::GEOCONV.'?coords='.$coord.'&from='.$from;
        //dump($url);exit;
        $result = http_get($url.$this->key);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || isset($json['status'])) {
                $this->errorMsg($json);
                return false;
            }
            return $json['result'];
        }
        return false;
    }

    /**
     * 坐标与地理位置互转
     * @param $coord
     * @return bool
     */
    public function geocoder($coord)
    {
        if(is_array($coord)){
            $coord = implode(',',$coord);
        }
        $url = self::BASE_URL.self::GEOCODER."?location=".$coord."&output=".self::OUTPUT;
        $result = http_get($url.$this->key);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || $json['status']!=0) {
                $this->errorMsg($json);
                return false;
            }
            return $json['result'];
        }
        return false;
    }

    /**
     * 统一错误信息
     * @param $json
     */
    public function errorMsg($json)
    {
        $this->error['errCode'] = $json['status'];
        $this->error['errMsg'] = $json['message'];
    }
}
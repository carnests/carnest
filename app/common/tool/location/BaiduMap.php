<?php

/**
 * 百度地图api
 * User: coolong
 * Date: 2017/4/18
 * Time: 22:38
 */
namespace app\common\tool\location;
use api\baidu\Map;
class BaiduMap extends Map
{
    protected $options;
    public function __construct()
    {
        $this->options = [
            'key' => 'ecEj4yoGvBrXUu93za1GgzKe',
        ];
        parent::__construct($this->options);
    }

    /**
     * 微信JS坐标获取省份
     * @param $coord
     * @param $form
     * @return bool
     */
    public function province($coord,$form)
    {
        $baidu_coord = $this->geoconv($coord,$form);
        if($baidu_coord){
            $result =$this->geocoder($baidu_coord[0]);
            if($result){
                return $result['addressComponent']['province'];
            }
            return $result;
        }
        return $baidu_coord;
    }

}
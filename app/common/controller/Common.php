<?php

/**
 * Created by PhpStorm.
 * User: coolong
 * Date: 2017/4/6
 * Time: 22:41
 */
namespace app\common\controller;
use app\auth\controller\Mp;
use app\common\tool\ed\Crypt3Des;

class Common extends Mp
{
    protected $_3d_key;     //加密密码
    protected $db_user_xcx; //小程序用户数据表
    protected $userId;
    public function __construct()
    {
        parent::__construct();
        $this->_indexDate();
        $this->_3d_key = 'd554d3f16c6f4d4ac0f79c4ae43492b2';
        $this->db_user_xcx = model('user/UserXcx');
        $session_3rd = input('session_3rd');
        $this->userId = $this->db_user_xcx->getIdBy3RdSession($session_3rd);
    }

    protected function _indexDate()
    {
        $this->assign([
            'title' => '车巢网',
            'public_base' => '../app/common/view/base.html',
            'js' => $this->wechat->js,
        ]);
    }


    /**
     * 加密
     * @param $str
     * @return string
     */
    public function _encode($str)
    {
        $crypt = new Crypt3Des($this->_3d_key, 'ECB', 'off');
        return $crypt->encrypt($str, 'hex');
    }

    /**
     * 解密
     * @param $str
     * @return string
     */
    public function _decode($str)
    {
        $crypt = new Crypt3Des($this->_3d_key, 'ECB', 'off');
        return $crypt->decrypt($str, 'hex');
    }
}
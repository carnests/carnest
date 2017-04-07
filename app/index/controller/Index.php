<?php
namespace app\index\controller;
use app\auth\controller\Common;
class Index extends Common
{
    public function index()
    {
        return $this->fetch();
    }

    public function home()
    {
        return $this->fetch('index');
    }
}

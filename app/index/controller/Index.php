<?php
namespace app\index\controller;
use app\auth\controller\Common;
class Index extends Common
{
    public function index()
    {
        dump(session('user'));exit;
        return $this->fetch();
    }
}

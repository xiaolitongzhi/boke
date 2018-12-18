<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/18
 * Time: 13:09
 */

namespace app\index\controller;
use app\common\controller\Base;

class Login extends Base
{
    public function index()
    {
        return $this->view->fetch('login/login');
    }
}
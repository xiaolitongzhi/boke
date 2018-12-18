<?php
namespace app\common\controller;

use think\Controller;
use think\facade\Session;
use think\facade\Request;


class Base extends Controller
{
    protected function initialize()
    {
//        $this -> checkToken();
        header("Access-Control-Allow-Origin:*");
    }

}
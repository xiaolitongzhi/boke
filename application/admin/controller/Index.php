<?php
/**
 * 首页
 * User: Administrator
 * Date: 2018/12/10
 * Time: 15:06
 */
namespace app\admin\controller;
use app\common\controller\Base;
use app\common\controller\Swift;
use think\facade\Request;

class Index extends Base
{

    public function index()
    {
        return $this->view->fetch('index/index');
    }

    public function welcome()
    {
        return $this->view->fetch('index/welcome');
    }
}
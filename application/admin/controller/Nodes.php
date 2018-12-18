<?php
/**
 * 权限详情.
 * User: Administrator
 * Date: 2018/12/13
 * Time: 13:29
 */

namespace app\admin\controller;
use app\common\controller\Base;
use think\facade\Request;

class Nodes extends Base
{
    //列表
    public function lists()
    {
        return $this->view->fetch('nodes/admin-rule');
    }
}
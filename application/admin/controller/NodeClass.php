<?php
/**
 * 权限分类.
 * User: Administrator
 * Date: 2018/12/13
 * Time: 13:45
 */

namespace app\admin\controller;
use app\common\controller\Base;
use think\facade\Request;

class NodeClass extends Base
{
    //列表
    public function lists()
    {
        return $this->view->fetch('nodeclass/admin-cate');
    }
}
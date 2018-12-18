<?php
/**
 * 车辆基本信息数据库.
 * User: Administrator
 * Date: 2018/12/16
 * Time: 14:49
 */

namespace app\admin\controller;
use app\common\controller\Base;

use app\common\model\CarBase;//基本数据库

class Databases extends Base
{
    //列表
    public function lists()
    {
        $data = CarBase::all();
    return $this->view->fetch('databases/car-list');
    }

}
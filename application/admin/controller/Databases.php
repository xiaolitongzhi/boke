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
use think\facade\Request;

class Databases extends Base
{
    //列表
    public function lists()
    {
        $data = CarBase::all();
        return $this->view->fetch('databases/car-list');
    }


    //搜索车牌号返回数据
    public function  searchPlateNumber()
    {
        if(Request::isPost())
        {
            $data = Request::param();//接收微信唯一id
            $result = $this->validate($data,['plate_number' => 'unique:CarBase']);

            if($result !== true){

                $code_info = CarBase::where('plate_number','=',$data['plate_number'])->find();

                if(!empty($code_info)){
                    $status=1;
                    $result=$code_info;
                }else{
                    $status=0;
                    $result='没有查到车辆信息';
                }
                return json_encode(['status'=>$status,'cardata'=>$result]);
            }
        }
    }

}
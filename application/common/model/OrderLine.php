<?php
/**
 * 订单操作流程.
 * User: Administrator
 * Date: 2018/12/14
 * Time: 17:19
 */

namespace app\common\model;
use think\Model;

class OrderLine extends Model
{
    protected $pk='id';
    protected $table='car_order_time_line';
    //开启自动时间戳
    protected $autoWriteTimestamp=true;
    //指定时间戳字段名
    protected $createTime='create_time';
    //设置输出时间格式
    protected $dateFormat = 'Y-m-d H:i';


}

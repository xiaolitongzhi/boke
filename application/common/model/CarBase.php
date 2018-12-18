<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/16
 * Time: 15:17
 */

namespace app\common\model;
use think\Model;

class CarBase extends Model
{
    protected $pk='car_id';
    protected $table='base_car_base';

    //开启自动时间戳
    protected $autoWriteTimestamp=true;
    //指定时间戳字段名
    protected $createTime='create_time';
    protected $updateTime='update_time';
    //设置输出时间格式
    protected $dateFormat = 'Y-m-d H:i';
}
<?php
/**
 * 人事-部门.
 * User: Administrator
 * Date: 2018/12/16
 * Time: 9:28
 */

namespace app\common\model;
use think\Model;

class StaffType extends Model
{
    protected $pk='id';
    protected $table='tp_personnel_type';

    //开启自动时间戳
    protected $autoWriteTimestamp=true;
    //指定时间戳字段名
    protected $createTime='create_time';
    //设置输出时间格式
    protected $dateFormat = 'Y-m-d H:i';
}
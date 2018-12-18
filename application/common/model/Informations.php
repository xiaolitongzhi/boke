<?php

namespace app\common\model;
use think\Model;

class Informations extends Model
{
    protected $pk='id';
    protected $table='bk_information';
    //开启自动时间戳
    protected $autoWriteTimestamp=true;
    //指定时间戳字段名
    protected $createTime='create_time';
    //设置输出时间格式
    protected $dateFormat = 'Y-m-d H:i:s';
}
<?php
/**
 * Created by PhpStorm.
 * User: xiaolitongzhi
 * Date: 2018-12-14
 * Time: 22:56
 */

namespace app\common\model;
use think\Model;

class Mendian extends Model
{
    protected $pk='id';
    protected $table='mendian_address';
    //开启自动时间戳
    protected $autoWriteTimestamp=true;
    //指定时间戳字段名
    protected $createTime='create_time';

    //设置输出时间格式
    protected $dateFormat = 'Y-m-d H:i';



}
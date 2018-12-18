<?php
/**
 * 订单表.
 * User: Administrator
 * Date: 2018/12/14
 * Time: 17:19
 */

namespace app\common\model;
use think\Model;

class Order extends Model
{
    protected $pk='oid';
    protected $table='car_order';
    //开启自动时间戳
    protected $autoWriteTimestamp=true;
    //指定时间戳字段名
    protected $createTime='create_time';
    //设置输出时间格式
    protected $dateFormat = 'Y-m-d H:i';


    public function getServerTimeAttr($value)
    {
        return date('Y-m-d H:i',$value);
    }


    //关联店铺信息  一对一
    public function getMendianInfo()
    {
        return $this->belongsTo('Mendian', 'brand', 'id');
    }

    //

}

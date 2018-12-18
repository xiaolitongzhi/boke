<?php
/**
 * 商品出入记录表.
 * User: xiaolitongzhi
 * Date: 2018-12-13
 * Time: 23:40
 */

namespace app\common\model;
use think\Model;

class BaseGoodsHistory extends Model
{
    protected $pk='record_id';
    protected $table='base_goods_record';

    //开启自动时间戳
    protected $autoWriteTimestamp=true;
    //指定时间戳字段名
    protected $createTime='create_time';
    protected $updateTime='update_time';
    //设置输出时间格式
    protected $dateFormat = 'Y-m-d H:i';
}
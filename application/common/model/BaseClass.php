<?php
/**
 * 商品分类.
 * User: xiaolitongzhi
 * Date: 2018-12-13
 * Time: 23:40
 */

namespace app\common\model;
use think\Model;

class BaseClass extends Model
{
    protected $pk='class_id';
    protected $table='base_goods_class';
}
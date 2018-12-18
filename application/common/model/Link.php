<?php
/*
 * 友情链接
 * */
namespace app\common\model;
use think\Model;
use think\model\concern\SoftDelete;

class Link extends Model
{
    protected $pk='id';
    protected $table='bk_link';
    //开启自动时间戳
    protected $autoWriteTimestamp=true;
    //指定时间戳字段名
    protected $createTime='create_time';
    //设置输出时间格式
    protected $dateFormat = 'Y-m-d H:i';

    //软删除
    use SoftDelete;
    protected $deleteTime='delete_time';

}
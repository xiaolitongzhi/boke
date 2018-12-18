<?php
/*
 * 轮播图
 * */
namespace app\common\model;
use think\Model;
use think\model\concern\SoftDelete;

class User extends Model
{
    protected $pk='id';
    protected $table='bk_user';
    //开启自动时间戳
    protected $autoWriteTimestamp=true;
    //指定时间戳字段名
    protected $createTime='create_time';
    protected $updateTime='update_time';

    //软删除
    use SoftDelete;
    protected $deleteTime='delete_time';
    //设置输出时间格式
    protected $dateFormat = 'Y-m-d H:i';


    public function getSexAttr($value)
    {
        switch ($value){
            case 0:
                return '女';
            case 1:
                return '男';
            default:
                return '保密';
        }
    }
}
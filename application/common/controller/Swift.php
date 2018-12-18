<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/10
 * Time: 14:40
 */

namespace app\common\controller;
use think\facade\Request;

class Swift
{
    //获取分页和页数
    public function getPageLimit()
    {
        if( !empty(Request::param('page')) &&  !empty(Request::param('limit')) ){
            $page = Request::param('page');
            $limit = Request::param('limit');
        }else{
            $page = 1;
            $limit = 10;
        }

        if($page == 1){
            $page = 0;
        }else{
            $page = (($page-1)*$limit);
        }
            $page_limit_str = "$page,$limit";
        return  $page_limit_str;
    }


    //查询条件
    public function selectPublic()
    {
        if(!isset($arr)){
            $arr = [];
        }

        //时间查询  [时间戳][区间查询]
        if ($select_start = strtotime( Request::param('select_start') ) ){
            if($select_end = strtotime( Request::param('select_end') ) ){
                $create_time = "$select_start,$select_end";
                $arr[] = ['create_time','between',$create_time];
            }
        }

        //条件查询【下拉式选项框，单条件传送搜索】
        if ($select_type = Request::param('select_type')){
            if($select_value = Request::param('select_value')){
                if (!empty($select_type)&&!empty($select_value)) {
                    $arr[] = ["$select_type",'like',"%{$select_value}%"];
                }
            }
        }
        return $arr;
    }

}
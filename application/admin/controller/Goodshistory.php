<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/18
 * Time: 16:09
 */

namespace app\admin\controller;
use app\common\controller\Base;
use app\common\controller\Swift;
use think\facade\Request;
use app\common\model\BaseGoodsHistory;

class Goodshistory extends Base
{

    public function lists()
    {

        $arr = [];
        if ($search_goods_name = Request::param('search_goods_name')){  $arr[] = ['goods_name','like',"%{$search_goods_name}%"];}


        $asc_desc = 'asc';//asc=升序；desc=降序；

        $res_arr = (new Swift())->selectPublic();
        $arr = array_merge($arr,$res_arr);
        $data_num_all = BaseGoodsHistory::all();
        $data_is	  = BaseGoodsHistory::where($arr) -> order('record_id',$asc_desc);
        $data  		  = $data_is -> paginate(10)->appends(Request::param());
        if(empty($arr)){
            $data_num = $data_num_all->count();
        }else{
            $data_num = $data_is->count();
        }
        //传递视图
        $this->view->assign('count',$data_num);//传递条数
        $this->view->assign(['data'=>$data,'count'=>$data_num]);//传递多条
        return $this->view->fetch('goodshistory/history-list');
    }
}
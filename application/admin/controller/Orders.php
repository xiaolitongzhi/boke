<?php
/**
 * 所有订单表.
 * User: Administrator
 * Date: 2018/12/14
 * Time: 15:47
 */
namespace app\admin\controller;
use app\common\controller\Base;
use app\common\controller\Swift;
use think\facade\Request;

use app\common\model\Order;
class Orders extends Base
{
    //所有列表
    public function lists()
    {
        $arr = [];
        $init_arr = [];
        if ($search_order_num = Request::param('search_order_num')){  $arr[] = ['order_num','like',"%{$search_order_num}%"];}

        $res_arr = (new Swift())->selectPublic();
        $init_arr[] = ['state','=',0];
        $arr = array_merge($init_arr,$arr,$res_arr);
        $data_num_all = Order::where($init_arr)->select();
        $data_is	  = Order::where($arr) -> order('oid','asc');
        $data  		  = $data_is -> paginate(10)->appends(Request::param());
        if(empty($arr)){
            $data_num = $data_num_all->count();
        }else{
            $data_num = $data_is->count();
        }

        $this->view->assign('count',$data_num);//传递条数
        $this->view->assign(['data'=>$data,'count'=>$data_num]);//传递多条
        return $this->view->fetch('orders/order-list');
    }

    //我的订单池
    public function index()
    {
        return $this->view->fetch('orders/order-index');
    }

    //处理中
    public function the_way()
    {
        return $this->view->fetch('orders/order-the-way');
    }

    //已完成
    public function history()
    {
        return $this->view->fetch('orders/order-index-history');
    }












}
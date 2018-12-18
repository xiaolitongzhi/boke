<?php
/**
 * 订单-客服.
 * User: xiaolitongzhi
 * Date: 2018-12-14
 * Time: 22:21
 */

namespace app\admin\controller;
use app\common\controller\Base;
use app\common\controller\Swift;
use think\facade\Request;

use app\common\model\Order;

class OrderService extends Base
{
    private $service_num = 1001;//获取的技术id


    //添加
    public function create()
    {
        return $this->view->fetch('orders/order-add');
    }

    //客服接单
    public function orderTaking()
    {

        if(Request::isPost()){
            $oid=Request::param('oid');
            $state=Order::get(['oid'=>$oid])->getData('state');
            if ($state==0){
                if(Order::update(['state'=>1,'service_state'=>0,'service_num'=>$this->service_num],['oid'=>$oid])){
                    $status=1;
                    $result='接单成功';
                }else{
                    $status=0;
                    $result='接单失败，刷新页面重试！';
                }
            }

            if($status == 1){
                (new \app\admin\controller\OrderLines())->saveLine($this->service_num,$oid,'接单成功');
            }
            return ['status'=>$status,'message'=>$result];
        }
    }


    //联系客户沟通



}


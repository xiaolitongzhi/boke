<?php
/**
 * 订单操作流水.
 * User: Administrator
 * Date: 2018/12/15
 * Time: 14:57
 */

namespace app\admin\controller;
use app\common\controller\Base;
use think\facade\Request;

use app\common\model\OrderLine;

class OrderLines extends Base
{

    private $uid = 1001;//操作人id

    public function index()
    {
        return $this->view->fetch('orderlines/index');
    }

    /**
     * 记录流水产生过程
     * @param $uid 用户id
     * @param $order_id 订单id
     * @param $content 内容
     * @param  (new \app\admin\controller\OrderLines())->saveLine('uid','order_id','内容');
     * @param bool $paginate
     * @return  1=成功 2=失败
     */
    public function saveLine($uid,$order_id,$content)
    {
        if (OrderLine::create([
            'uid'=>$uid,
            'order_id'=>$order_id,
            'content'=>$content,
        ])){
            $status=1;
        }else{
            $status=0;
        }
        return $status;
    }


    /**
     * 搜索一条订单的操作情况
     * @param $order_id 订单id
     * param(new \app\admin\controller\OrderLines())->select('order_id');
    */
    public function select($order_id)
    {
        if(!isset($order_id)){
            return null;
        }else{
            $OrderLine = OrderLine::where('order_id','=',$order_id)->select();
            return $OrderLine;
        }
    }

}
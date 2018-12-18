<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/18
 * Time: 10:38
 */

namespace app\admin\controller;
use app\common\controller\Base;
use app\common\model\User;
use think\facade\Request;

class Login extends Base
{

    public function index()
    {
        return $this->view->fetch('login/admin-login');
    }

    //核查
    public function checkLogin()
    {
        if (Request::isPost())
        {
            $status = 0;
            $data = Request::param();

            $rule=[
                'login_name|用户名'=>'require',
                'pwd|密码'=>'require',
            ];
            $back_rule=[
                'login_name.require'=>'用户名未填写！',
                'pwd.require'=>'密码未填写',
            ];

            $result=$this->validate($data,$rule,$back_rule);
            if ($result===true){
                
                echo '111111111111111111';
            }
            //数据处理完毕->为页面返回信息
            return['status'=>$status,'message'=>$result];
        }
    }

}
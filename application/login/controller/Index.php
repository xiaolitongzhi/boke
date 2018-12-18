<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/18
 * Time: 10:38
 */

namespace app\login\controller;
use app\common\controller\Base;
use app\common\model\User;
use think\facade\Request;
use think\facade\Session;

class Index extends Base
{

    public function index()
    {
        return $this->view->fetch('login/login');
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
                'password|密码'=>'require',
            ];
            $back_rule=[
                'login_name.require'=>'用户名未填写！',
                'password.require'=>'密码未填写',
            ];

            $result=$this->validate($data,$rule,$back_rule);
            if ($result===true){
                $res=$this->validate($data,['login_name'=>'mobile']);
                if ($res===true){
                    $info=User::where('phone',$data['login_name'])->find();
                }else{
                    $info=User::where('username',$data['login_name'])->find();
                }

                if (empty($info)){
                    $status=0;
                    $result='用户不存在';
                }elseif ($info['state']==0){
                    $status=0;
                    $result='用户被禁用';
                }elseif ($info['password']!=sha1($data['password'])){
                    $status=0;
                    $result='密码错误';
                }else{
                    Session::set("UserName",$info['username']);
                    Session::set("UserId",$info['id']);
                    Session::set("UserId",$info['id']);

                    $status=1;
                    $result='登录成功';
                }
            }
            //数据处理完毕->为页面返回信息
            return['status'=>$status,'message'=>$result];
        }
    }

    //退出
    public function logout()
    {
        Session::delete('UserName');
        Session::delete('UserId');
        $this->redirect('/admin/Login');
    }

}
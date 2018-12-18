<?php
/**
 * 用户
 * User: xiaolitongzhi
 * Date: 2018-12-10
 * Time: 23:42
 */

namespace app\admin\controller;
use app\common\controller\Base;
use think\facade\Request;

use app\common\model\User;//用户

class Users extends Base
{
    //会员列表
    public function lists()
    {
        $arr = [];
        if ($search_nickname = Request::param('search_nickname')){  $arr[] = ['nickname','like',"%{$search_nickname}%"];  }
        $asc_desc = 'asc';//asc=升序；desc=降序；
        $data_num_all = User::all();
        $data_is	  = User::where($arr) -> order('create_time',$asc_desc);
        $data  		  = $data_is -> paginate(10);//查询符合条件的 带分页效果

        if(empty($arr)){
            //空条件 总条数
            $data_num = $data_num_all->count();
        }else{
            //带有条件  显示带有查询条件的总条数
            $data_num = $data_is->count();
        }

        //传递视图
        $this->view->assign('count',$data_num);//传递条数
        $this->view->assign(['data'=>$data,'count'=>$data_num]);//传递多条
        return $this->view->fetch('users/member-list');
    }

    //删除会员列表
    public function del_lists()
    {
        $arr = [];
        if ($search_nickname = Request::param('search_nickname')){  $arr[] = ['nickname','like',"%{$search_nickname}%"];  }
        $asc_desc = 'asc';//asc=升序；desc=降序；
        $data_num_all = User::onlyTrashed()->all();
        $data_is	  = User::onlyTrashed()->where($arr) -> order('create_time',$asc_desc);
        $data  		  = $data_is -> paginate(10)->appends(Request::param());//查询符合条件的 带分页效果

        if(empty($arr)){
            //空条件 总条数
            $data_num = $data_num_all->count();
        }else{
            //带有条件  显示带有查询条件的总条数
            $data_num = $data_is->count();
        }
        //传递视图
        $this->view->assign('count',$data_num);//传递条数
        $this->view->assign(['data'=>$data,'count'=>$data_num]);//传递多条
        return $this->view->fetch('users/member-del');
    }

    //添加
    public function create()
    {
        return $this->view->fetch('users/member-add');
    }

    //保存
    public function save()
    {
        if( Request::isPost() )
        {
            $status=0;
            $data = Request::param();

            $rule=[
                'nickname|用户名'=>'require|min:2',
                'email|邮箱'=>'require|email|unique:user',
                'pass|密码'=>'require|min:6',
                'repass|确认密码'=>'require|confirm:pass'
            ];
            $back_rule=[
                'nickname.require'=>'昵称不能为空！','nickname.min'=>'昵称最短2两个字符！',
                'email.require'=>'邮箱必须填写','email.email'=>'邮箱格式不正确！','email.unique'=>'该邮箱已经被注册！',
                'pass.require'=>'密码不能为空','pass.min'=>'密码不能小于6位字符！',
                'repass.require'=>'请再输入一遍密码','repass.confirm'=>'两次输入的密码不一致！',
            ];
            $result=$this->validate($data,$rule,$back_rule);
            if ($result===true){
                if (User::create([
                    'nickname'=>$data['nickname'],
                    'email'=>$data['email'],
                    'state'=>1,
                    'password'=>sha1($data['pass']),
                ])){
                    $status=1;
                    $result='添加成功';
                }else{
                    $status=0;
                    $result='添加失败';
                }
            }
            return ['status'=>$status,'message'=>$result];
        }
    }

    //修改
    public function edit()
    {

    }

    //更新
    public function update()
    {

    }

    //软删除
    public function delete()
    {
        if (Request::isPost()){

            $id=Request::param('id');

            if (User::destroy($id)){
                $status=1;
                $result='删除成功';
            }else{
                $status=0;
                $result='删除失败，刷新页面重试！';
            }
            return ['status'=>$status,'message'=>$result];
        }
    }

    //彻底删除
    public function deleteTrue()
    {
        if (Request::isPost()){

            $id=Request::param('id');
            if (User::destroy($id,true)){
                $status=1;
                $result='删除成功';
            }else{
                $status=0;
                $result='删除失败，刷新页面重试！';
            }
            return ['status'=>$status,'message'=>$result];
        }
    }

    //停用-启用
    public function setStatus()
    {
        if(Request::isPost()){
            $id=Request::param('id');
            $state=User::get(['id'=>$id])->getData('state');
            if ($state==1){
                User::update(['state'=>0],['id'=>$id]);
            }else{
                User::update(['state'=>1],['id'=>$id]);
            }
        }
    }





    public function test()
    {

        $a = $_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"];

        echo $a;
        if (strpos($a, '/admin/users/lists')) {
            echo '你是lists';
        } elseif (strpos($a, '/admin/users/del_lists')) {
            echo '你是安卓del_lists';
        } else {
            echo '非法请求';
        }
    }
}
<?php
/**
 * 人事人员表.
 * User: Administrator
 * Date: 2018/12/16
 * Time: 9:21
 */

namespace app\admin\controller;
use app\common\controller\Base;
use think\facade\Request;
use app\common\model\Staffs;

class Personnel extends Base
{
    //列表
    public function lists()
    {
        $arr = [];//接收条件
        $init_arr = [];//初始值
        $res_arr = [];//方法返回值

        $arr = array_merge($init_arr,$arr,$res_arr);
        $data_num_all = Staffs::where($init_arr)->select();
        $data_is	  = Staffs::where($arr) -> order('id','asc');
        $data  		  = $data_is -> paginate(10)->appends(Request::param());
        if(empty($arr)){
            $data_num = $data_num_all->count();
        }else{
            $data_num = $data_is->count();
        }

        $this->view->assign('count',$data_num);//传递条数
        $this->view->assign(['data'=>$data,'count'=>$data_num]);//传递多条
       return $this->view->fetch('personnel/yuangong-list');
    }

    //添加
    public function create()
    {

      return $this->view->fetch('personnel/yuangon-add');
    }

    //保存
    public function save()
    {
        if(Request::isPost()){
            $status=0;
            $data=Request::param();

            $rule=[
                'type_id|部门id'=>'require',
                'username|姓名'=>'require|unique:Staffs',
                'phone|联系方式'=>'require|number|/^[1][3,4,5,6,7,8,9][0-9]{9}$/',
            ];
            $back_rule=[
                'type_id'=>'部门未选择！',
                'username'=>'请输入员工姓名！',
                'username.unique'=>'当前员工姓名已经存在！',
                'phone.require'=>'电话号码未填写！',
                'phone'=>'电话号码未填写！',
            ];
            $result=$this->validate($data,$rule,$back_rule);

            if (true===$result){
                $addInv=Staffs::create([
                    'type_id'=>$data['type_id'],
                    'username'=>$data['username'],
                    'phone'=>$data['phone'],
                    'create_time'=>time(),
                ]);
                if ($addInv){
                    $status=1;
                    $result='添加成功';
                }else{
                    $status=0;
                    $result='添加失败！';
                }
            }
            return ['status'=>$status,'message'=>$result];
        }
    }

    //修改
    public function edit()
    {
        $id = Request::param('id');
        if(isset($id)){
            $info_code = Staffs::where('id','=',$id)->find();
        }
      
      return $this->view->fetch('personnel/yuangong-edit');
    }

    //更新保存
    public function update()
    {
        if (Request::isPost())
        {
            $status = 0;
            $data = Request::param();
            $rule=[
                'type_id|部门id'=>'require',
                'username|姓名'=>'require|unique:InsideClassUser',
                'phone|联系方式'=>'require|number|/^[1][3,4,5,6,7,8,9][0-9]{9}$/',
            ];
            $back_rule=[
                'type_id'=>'部门未选择！',
                'username'=>'请输入员工姓名！',
                'username.unique'=>'当前员工姓名已经存在！',
                'phone.require'=>'电话号码未填写！',
                'phone'=>'电话号码未填写！',
            ];
            $result=$this->validate($data,$rule,$back_rule);

            if ($result===true){
                $info_code = Staffs::where('id',$data['id'])->find();
                if($info_code['type_id']==$data['type_id']&&$info_code['username']==$data['username']&&$info_code['phone']==$data['phone'])
                {
                    $status=0;
                    $result='您当前的操作为发生任何改变';
                }else{
                    if (Staffs::update([
                        'type_id'=>$data['type_id'],
                        'username'=>$data['username'],
                        'phone'=>$data['phone'],
                    ],['id'=>$data['id']])){
                        $status=1;
                        $result='修改成功！';
                    }else{
                        $status=0;
                        $result='修改失败！';
                    }
                }
            }
            return ['status'=>$status,'message'=>$result];
        }
    }
    //开关



}
<?php
/**
 * 轮播图列表.
 * User: Administrator
 * Date: 2018/12/13
 * Time: 14:08
 */

namespace app\admin\controller;
use app\common\controller\Base;
use think\facade\Request;

use app\common\model\Slide;

class Slides extends Base
{
    //轮播图列表
    public function lists()
    {
        $arr = [];//接收条件
        $init_arr = [];//初始值
        $res_arr = [];//方法返回值

        $arr = array_merge($init_arr,$arr,$res_arr);
        $data_num_all = Slide::where($init_arr)->select();
        $data_is	  = Slide::where($arr) -> order('id','asc');
        $data  		  = $data_is -> paginate(10)->appends(Request::param());
        if(empty($arr)){
            $data_num = $data_num_all->count();
        }else{
            $data_num = $data_is->count();
        }

        $this->view->assign('count',$data_num);//传递条数
        $this->view->assign(['data'=>$data,'count'=>$data_num]);//传递多条
        return $this->view->fetch('slide/slide-list');
    }

    //轮播图删除列表
    public function del_lists()
    {
        $arr = [];//接收条件
        $init_arr = [];//初始值
        $res_arr = [];//方法返回值

        $arr = array_merge($init_arr,$arr,$res_arr);
        $data_num_all = Slide::onlyTrashed()->where($init_arr)->select();
        $data_is	  = Slide::onlyTrashed()->where($arr) -> order('id','asc');
        $data  		  = $data_is -> paginate(10)->appends(Request::param());
        if(empty($arr)){
            $data_num = $data_num_all->count();
        }else{
            $data_num = $data_is->count();
        }

        $this->view->assign('count',$data_num);//传递条数
        $this->view->assign(['data'=>$data,'count'=>$data_num]);//传递多条
        return $this->view->fetch('slide/slide-del');
    }


    //添加轮播图
    public function create()
    {
        return $this->view->fetch('slide/slide-add');
    }

    //轮播图保存
    public function save()
    {

        if (Request::isPost())
        {
            $status = 0;
            $data = Request::param();

            //数据验证
            $rule=[
                'image|图片'=>'require',
                'target_url|跳转'=>'require|url',
                'describe|描述'=>'require',
            ];
            $back_rule=[
                'image'=>'活动宣传图未上传！',
                'target_url'=>'输入链接格式错误！',
                'describe'=>'请简述活动内容',
            ];
            $result=$this->validate($data,$rule,$back_rule);
            //其他数据验证限制

            //数据处理 (其他：数组批量保存，事物保存)
            if ($result===true){

                if (Slide::create([
                    'image'=>$data['image'],
                    'target_url'=>$data['target_url'],
                    'describe'=>$data['describe'],
                    'state'=>0,
                    'create_time'=>time(),
                ])){
                    $status=1;
                    $result='添加成功';
                }else{
                    $status=0;
                    $result='添加失败！';
                }

            }
            return['status'=>$status,'message'=>$result];
        }
    }

    //修改
    public function edit()
    {
        $id = Request::param('id');
        $data=Slide::where('id',$id)->find();
        $this->view->assign('data',$data);
        return $this->view->fetch('slide/slide-edit');

    }

    //更新
    public function update()
    {
        if (Request::isPost())
        {
            $status = 0;
            $data = Request::param();

            $rule=[
                'image|图片'=>'require',
                'target_url|跳转'=>'require|url',
                'describe|描述'=>'require',
            ];
            $back_rule=[
                'image'=>'活动宣传图未上传！',
                'target_url'=>'输入链接格式错误！',
                'describe'=>'请简述活动内容',
            ];
            $result=$this->validate($data,$rule,$back_rule);
            if ($result===true){
                //数据存储再处理
                $info = Slide::where('id',$data['id'])->find();
                if ($info['image']==$data['image']&&
                    $info['target_url']==$data['target_url']&&
                    $info['describe']==$data['describe']
                ){
                    $status=0;
                    $result='未作出更改';
                }else{
                    if (Slide::update([
                        'image'=>$data['image'],
                        'target_url'=>$data['target_url'],
                        'describe'=>$data['describe'],
                        'state'=>0
                    ],['id'=>$data['id']])){
                        $status=1;
                        $result='更新成功';
                    }else{
                        $status=0;
                        $result='更新失败！';
                    }
                }

            }
            return['status'=>$status,'message'=>$result];
        }

    }

    //软删除
    public function delete()
    {
        if (Request::isPost()){

            $id=Request::param('id');

            if (Slide::destroy($id)){
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

            $slide = Slide::onlyTrashed()->find($id);
            $slide->restore();
           $res =$slide->destroy($id,true);
            if ($res){
                $status=1;
                $result='删除成功';
            }else{
                $status=0;
                $result='删除失败，刷新页面重试！';
            }
            return ['status'=>$status,'message'=>$result];
        }
    }


    //恢复
    public function recovery()
    {
        if(Request::isPost()){
            $slide =new Slide();
            $id=Request::param('id');
            $res = $slide->restore(['id' => $id]);
            if ($res){
                $status=1;
                $result='恢复成功';
            }else{
                $status=0;
                $result='恢复失败！';
            }
            return ['status'=>$status,'message'=>$result];
        }
    }

    //停用-启用
    public function setStatus()
    {
        if(Request::isPost()){
            $id=Request::param('id');
            $state=Slide::get(['id'=>$id])->getData('state');
            if ($state==1){
                if (Slide::update(['state'=>0,'sort'=>null],['id'=>$id])){
                    $status=1;
                    $result='成功';
                }else{
                    $status=0;
                    $result='失败！';
                }
            }else{
                if (Slide::update(['state'=>1],['id'=>$id])){
                    $status=1;
                    $result='成功';
                }else{
                    $status=0;
                    $result='失败！';
                }
            }
            return['status'=>$status,'message'=>$result];
        }
    }

    //接收图片保存 -slide目录
    public function receiveFile()
    {
        //接收图片
        $files = Request::file();

        //将接收到的图片移动到新路径,新的路径可以定义
        $fInfo = $files['file']-> move(config('app.admin_save_path').'/slide');

        //将移动后的上传文件保存
        $fname = $fInfo -> getSaveName();

        //检测保存的文件是否存在
        $file_path = config('app.admin_save_path').'/slide/'.$fname;
        $file_path = str_replace("\\","/",$file_path);//替换路径中的反斜线
        //dump(file_exists($file_path));

        $fname = Request::domain().Request::config('admin_save_path_to').Request::config('get_upload').'/slide/'.$fname;//图片路径拼接完整路径
        $fname = str_replace("\\","/",$fname);//替换路径中的反斜线
      
        if(file_exists($file_path)){
            $arr = [
                'code'=>0,
                'msg'=>'图片上传成功',
                'data'=>[
                    'src'=>$fname
                ]
            ];
        }else{
            $arr = [
                'code'=>1,
                'msg'=>'图片上传失败',
                'data'=>[
                    'src'=>''
                ]
            ];
        }
        echo json_encode($arr);
    }

}
<?php
/**
 * 资讯管理
 * User: xiaolitongzhi
 * Date: 2018-12-10
 * Time: 22:43
 */

namespace app\admin\controller;
use app\common\controller\Base;
use think\facade\Request;
use think\facade\Env;

use app\common\model\Informations;

class Information extends Base
{	

	//列表
	public function lists()
	{
		$arr = [];

		//搜索条件传入
       $select_state = Request::param('search_state');
	    if ($select_state != null){
	    	switch ($select_state) {
	    		case 1:$arr[] = ['state','=',1];break;//备注
	    		case 0:$arr[] = ['state','=',0];break;//备注
	    	}
	    }
      
        if ($search_type = Request::param('search_type')){
            $arr[] = ['info_type','like',"%{$search_type}%"];
        }
        if ($search_title = Request::param('search_title')){
            $arr[] = ['info_title','like',"%{$search_title}%"];
        }

      
        $data_num = Informations::where($arr)->select();
       	$search_data = Request::param();
		$data=Informations::where($arr)->order('create_time','desc')->paginate(10)->appends($search_data);
        //条数
      
        if(empty($arr)){
            $data_num = $data_num->count();
        }else{
            $data_num = $data->count();
        }
        
		$this->view->assign('count',$data_num);
     	 $this->view->assign('search',$search_data);
		$this -> view -> assign('data',$data);
		return $this -> view -> fetch('information/message-list');
	}

    //删除咨询
    public function delete()
    {
        if (Request::isPost()){

            $id=Request::param('id');

            Informations::update(['state'=>0],['id'=>$id]);
            if (Informations::destroy($id)){
                $status=1;
                $result='删除成功';
            }else{
                $status=0;
                $result='删除失败，刷新页面重试！';
            }
            return ['status'=>$status,'message'=>$result];
        }
    }

	//添加
	public function create()
	{		
		return $this -> view ->fetch('information/message-add');
	}

	//软删除列表
	public function  del_lists()
    {
        $arr = [];

        //搜索条件传入
        $select_state = Request::param('search_state');
        if ($select_state != null){
            switch ($select_state) {
                case 1:$arr[] = ['state','=',1];break;//备注
                case 0:$arr[] = ['state','=',0];break;//备注
            }
        }

        if ($search_type = Request::param('search_type')){
            $arr[] = ['info_type','like',"%{$search_type}%"];
        }
        if ($search_title = Request::param('search_title')){
            $arr[] = ['info_title','like',"%{$search_title}%"];
        }

        $data_num = Informations::onlyTrashed()->where($arr)->select();
        $search_data = Request::param();
        $data=Informations::onlyTrashed()->where($arr)->order('create_time','desc')->paginate(10)->appends($search_data);
        //条数

        if(empty($arr)){
            $data_num = $data_num->count();
        }else{
            $data_num = $data->count();
        }

        $this->view->assign('count',$data_num);
        $this->view->assign('search',$search_data);
        $this -> view -> assign('data',$data);
        return $this -> view -> fetch('information/message-del');
    }

	//保存咨询
	public function save()
	{

        if (Request::isPost()) {
            $status = 0;
            $data = Request::param();

            //验证规则
            $rule=[
                'info_title|标题'=>'require',
                'info_type|信息分类'=>'require',
                'abstract|简介'=>'require|min:1|max:150',
            ];
            $back_rule=[
                'info_title'=>'标题不能为空！',
                'info_type'=>'请选择一个分类！',
                'abstract.require'=>'简介栏为空！',
                'abstract'=>'请输入一段少于150字的内容简介！',
            ];
            $result=$this->validate($data,$rule,$back_rule);
            if ($result===true){
                    if (Informations::create([
                        'info_type'=>$data['info_type'],
                        'info_title'=>$data['info_title'],
                        'abstract'=>$data['abstract'],
                        'info_content'=>$data['info_content'],
                        'info_img'=>empty($data['info_img']) ? '':$data['info_img'],
                        'create_time'=>time(),
                        'state'=>1,
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

	//修改咨询内容
	public function edit()
	{
        //接收用户信息
		$id=Request::param('id');
		$data=Informations::where('id',$id)->find();
        $this -> view -> assign('data',$data);
		return $this -> view -> fetch('information/message-edit');
	}

    //更新
    public function update()
    {

        if (Request::isPost()) {
            $status = 0;

            $data = Request::param();
            $id=$data['id'];

            $rule=[
                'info_title|标题'=>'require',
                'info_type|信息分类'=>'require',
                'abstract|简介'=>'require|min:1|max:150',
            ];
            $back_rule=[
                'info_title'=>'标题不能为空！',
                'info_type'=>'请选择一个分类！',
                'abstract.require'=>'简介栏为空！',
                'abstract'=>'请输入一段少于150字的内容简介！',
            ];
            $result=$this->validate($data,$rule,$back_rule);
            if ($result===true){
                //验证成功保存
                $info=Informations::where('id',$id)->find();
                if ($info['info_type']==$data['info_type']&&
                    $info['info_title']==$data['info_title']&&
                    $info['abstract']==$data['abstract']&&
                    $info['info_content']==$data['info_content']&&
                    $info['info_img']==$data['info_img'])
                {
                    $status=0;
                    $result='未作出更改';
                }else{
                    if (Informations::update([
                        'info_type'=>$data['info_type'],
                        'info_title'=>$data['info_title'], 
                        'abstract'=>$data['abstract'],
                        'info_content'=>$data['info_content'],
                        'info_img'=>$data['info_img'],
                        'default'=>0, 
                    ],['id'=>$id])){
                        $status=1;
                        $result='修改成功';
                    }else{
                        $status=0;
                        $result='修改失败';
                    }
                }
            }
            return['status'=>$status,'message'=>$result];
        }        
    }



    //彻底删除
    public function deleteTrue()
    {
        if (Request::isPost()){

            $id=Request::param('id');

            $informations = Informations::onlyTrashed()->find($id);
            $informations->restore();
            $res =$informations->destroy($id,true);
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
            $informations =new Informations();
            $id=Request::param('id');
            $res = $informations->restore(['id' => $id]);
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
            $state=Informations::get(['id'=>$id])->getData('state');
            if ($state==1){
                if (Informations::update(['state'=>0],['id'=>$id])){
                    $status=1;
                    $result='成功';
                }else{
                    $status=0;
                    $result='失败！';
                }
            }else{
                if (Informations::update(['state'=>1],['id'=>$id])){
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


    //图片保存-message
    public function receiveFile()
    {
        //接收图片
        $files = Request::file();

        if(!empty($files))
        {
            //将接收到的图片移动到新路径,新的路径可以定义
            $fInfo = $files['file']-> move(config('app.admin_save_path').'/message');

            //将移动后的上传文件保存
            $fname = $fInfo -> getSaveName();

            //检测保存的文件是否存在
            $file_path = config('app.admin_save_path').'/message/'.$fname;
            $file_path = str_replace("\\","/",$file_path);//替换路径中的反斜线
            //dump(file_exists($file_path));
            //dump($file_path);

            $fname = Request::domain().Request::config('admin_save_path_to').Request::config('get_upload').'message/'.$fname;//图片路径拼接完整路径
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
        }else{
            $arr = [
                'code'=>1,
                'msg'=>'图片上传未上传',
                'data'=>[
                    'src'=>''
                ]
            ];
        }
        echo json_encode($arr);
    }

    //接口-图片清除接口
    public function  delImg()
    {
        if (Request::isPost()){
            //查询图片是否存在
            $del_img_path=  Request::param('del_img_path');
            if(!empty($del_img_path)) {
                $aaa = Request::domain().Request::config('admin_save_path_to');//查询内容
                $bbb = config('app.admin_save_path');//替换的内容
                $del_img_path = str_replace($aaa,$bbb,"$del_img_path");//文件的位置
                if(file_exists($del_img_path)){
                    if(unlink($del_img_path)){
                        $arr = ['code'=>1, 'msg'=>'成功'];
                    }else{
                        $arr = ['code'=>0, 'msg'=>'失败'];
                    }
                }
            }else{
                $arr = ['code'=>0, 'msg'=>'地址不存在'];
            }
            echo json_encode($arr);
        }
    }
}

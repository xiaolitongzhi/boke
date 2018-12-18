<?php
/**
 * 友情链接
 * User: xiaolitongzhi
 * Date: 2018-12-10
 * Time: 22:43
 */

namespace app\admin\controller;
use app\common\controller\Base;
use think\facade\Request;

use app\common\model\Link;

class Links extends Base
{

    //列表
    public function lists()
    {
        $arr = [];
        if ($search_nickname = Request::param('search_nickname')){  $arr[] = ['nickname','like',"%{$search_nickname}%"];  }
        $asc_desc = 'asc';//asc=升序；desc=降序；
        $data_num_all = Link::all();
        $data_is	  = Link::where($arr) -> order('create_time',$asc_desc);
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
        return $this->view->fetch('links/index');
    }

    //删除会员列表
    public function del_lists()
    {
        $arr = [];
        if ($search_nickname = Request::param('search_nickname')){  $arr[] = ['nickname','like',"%{$search_nickname}%"];  }
        $asc_desc = 'asc';//asc=升序；desc=降序；
        $data_num_all = Link::onlyTrashed()->all();
        $data_is	  = Link::onlyTrashed()->where($arr) -> order('create_time',$asc_desc);
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
        return $this->view->fetch('links/index-del');
    }

    //保存
    public function save()
    {
        if (Request::isPost())
        {
            $status = 0;
            $data = Request::param();

            //数据验证
            $rule=[
                'name|链接名'=>'require|unique:Link',
                'link_url|URL地址'=>'require|unique:Link',
            ];
            $back_rule=[
                'name.require'=>'链接名未填写！',
                'name.unique'=>'链接名已经存在！',
                'link_url.require'=>'URL地址未填写',
                'link_url.unique'=>'URL地址已经存在',
            ];
            $result=$this->validate($data,$rule,$back_rule);
            if ($result===true){

                if ( $res = Link::create([
                    'name'=>$data['name'],
                    'link_url'=>$data['link_url'],
                ])){
                    $status=1;
                    $result='添加成功';

                }else{
                    $status=0;
                    $result='添加失败！';
                }
            }
            //数据处理完毕->为页面返回信息
            return['status'=>$status,'message'=>$result];
        }
    }

    //修改
    public function edir()
    {

    }

    //更新
    public function update()
    {
        if (Request::isPost())
        {
            $status = 0;
            $data = Request::param();
            $rule=[
                'name|链接名'=>'require|unique:Link',
                'link_url|URL地址'=>'require|unique:Link',
            ];
            $back_rule=[
                'name.require'=>'链接名未填写！',
                'name.unique'=>'链接名已经存在！',
                'link_url.require'=>'URL地址未填写',
                'link_url.unique'=>'URL地址已经存在',
            ];
            $result=$this->validate($data,$rule,$back_rule);
            if ($result===true){
                $code_info=Link::where('id',$data['id'])->find();
                if ($code_info['name']==$data['name']&&
                    $code_info['link_url']==$data['link_url'])
                {
                    $status=0;
                    $result='未作出更改';
                }else{
                    if (Link::create([
                        'name'=>$data['name'],
                        'link_url'=>$data['link_url'],
                    ])){
                        $status=1;
                        $result='修改成功';

                    }else{
                        $status=0;
                        $result='修改失败！';
                    }
                }
            }
            //数据处理完毕->为页面返回信息
            return['status'=>$status,'message'=>$result];
        }
    }

    //软删除
    public function delete()
    {
        if (Request::isPost()){

            $id=Request::param('id');

            if (Link::destroy($id)){
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

            $link = Link::onlyTrashed()->find($id);
            $link->restore();
            $res = $link->delete(true);

            if($res){
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
            $link =new Link();
            $id=Request::param('id');
            $res = $link->restore(['id' => $id]);
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
}
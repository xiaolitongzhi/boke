<?php
/**
 * 商品分类.
 * User: xiaolitongzhi
 * Date: 2018-12-13
 * Time: 23:32
 */

namespace app\admin\controller;
use app\common\controller\Base;
use think\facade\Request;
use think\db;
use app\common\model\BaseClass;
class GoodsClass extends Base
{
    //列表
    public function lists()
    {
        $arr = [];
        if ($search_class_name = Request::param('search_class_name')){  $arr[] = ['class_name','like',"%{$search_class_name}%"];  }
        $asc_desc = 'asc';//asc=升序；desc=降序；
        $data_num_all = BaseClass::all();
        $data_is	  = BaseClass::where($arr) -> order('class_id',$asc_desc);
        $data  		  = $data_is -> paginate(10)->appends(Request::param());

        foreach($data as $k=>$v){
            //统计逗号出现的次数
            $i = substr_count($v['path'],',');
            if($i == 0)
            {
                $data[$k]['path_name'] = '顶级';
            }else{
                //拼接|--
                $data[$k]['path_name'] = str_repeat('|----',$i).$data[$k]['class_name'];
            }
        }

        if(empty($arr)){
            $data_num = $data_num_all->count();
        }else{
            $data_num = $data_is->count();
        }

        //传递视图
        $this->view->assign('count',$data_num);//传递条数
        $this->view->assign(['data'=>$data,'count'=>$data_num]);//传递多条
        return $this->view->fetch('goodsclass/class-list');
    }

    //添加页面
    public function create()
    {
        $classData = self::getClassName();
        $this->view->assign('count',$classData);//传递条数
        $this->view->assign(['data'=>$classData,'count'=>$classData]);//传递多条
        return $this->view->fetch('goodsclass/class-add');
    }

    //保存
    public function save()
    {
        if (Request::isPost())
        {
            $status = 0;
            $data = Request::param();

            $rule=[
                'class_name|分类名'=>'require|unique:BaseClass',
            ];
            $back_rule=[
                'class_name.require'=>'分类名未填写！',
                'class_name.unique'=>'分类名已经存在！',
            ];
            $result=$this->validate($data,$rule,$back_rule);
            if ($result===true){


                $pid = $data['parent_id'];
                if($pid == 0){
                    $data['path'] = 0;
                }else{
                    $p_data = BaseClass::find($pid);
                    $data['path'] = $p_data['path'].','.$p_data['class_id'];
                }

                if (BaseClass::create([
                    'class_name'=>$data['class_name'],
                    'parent_id'=>$data['parent_id'],
                    'path'=>$data['path'],
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



    //停用-启用
    public function setStatus()
    {
        if(Request::isPost()){
            $id=Request::param('class_id');
            $state=BaseClass::get(['class_id'=>$id])->getData('state');
            if ($state==1){
                if (BaseClass::update(['state'=>0],['class_id'=>$id])){
                    $status=1;
                    $result='成功';
                }else{
                    $status=0;
                    $result='失败！';
                }
            }else{
                if (BaseClass::update(['state'=>1],['class_id'=>$id])){
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

    //分类添加审核
    public function adminSetStatus()
    {
        if(Request::isPost()){
            $id=Request::param('class_id');
            $type=Request::param('type');

            $state=BaseClass::get(['class_id'=>$id])->getData('state');
            //2=待审核；当标记为1的时候通过审核，否则直接删除掉
            if ($state==2){
                if ($type == 1){
                    if (BaseClass::update(['state'=>0],['class_id'=>$id])){
                        $status=1;
                        $result='审核通过';
                    }else{
                        $status=0;
                        $result='审核失败！';
                    }
                }else{
                    if (BaseClass::destroy($id,true)){
                        $status=1;
                        $result='拒绝分类添加！已经删除';
                    }else{
                        $status=0;
                        $result='警告：分类添加已经拒绝！但是未删除成功！';
                    }
                }
            }else{
                    $status=0;
                    $result='非法操作！';
            }
            return['status'=>$status,'message'=>$result];
        }
    }



    //拼接商品分类名字
    public static function getClassName(){
        $sql = "select class_id,class_name,parent_id,path,CONCAT(path,',',class_id) AS paths from base_goods_class ORDER BY paths asc";
        $classData = Db::query($sql);
        foreach($classData as $k=>$v){
            //统计逗号出现的次数
            $i = substr_count($v['path'],',');

            if($i == 0)
            {
                $data[$k]['path_name'] = '顶级';
            }else{
                //拼接|--
                $classData[$k]['class_name'] = str_repeat('|----',$i).$classData[$k]['class_name'];
            }
        }
        return $classData;
    }
}
<?php
/**
 * 人事部门表.
 * User: Administrator
 * Date: 2018/12/16
 * Time: 9:21
 */

namespace app\admin\controller;
use app\common\controller\Base;
use think\facade\Request;
use app\common\model\StaffType;
use think\Db;

class PersonnelType extends Base
{
    //列表
    public function lists()
    {
     
        $arr = [];
        if ($search_type_name = Request::param('search_type_name')){  $arr[] = ['type_name','like',"%{$search_type_name}%"];  }
        $data_num_all = StaffType::all();
        $data_is	  = StaffType::where($arr) -> order('id','asc');
        $data  		  = $data_is -> paginate(10)->appends(Request::param());//查询符合条件的 带分页效果

        foreach($data as $k=>$v){
            //统计逗号出现的次数
            $i = substr_count($v['path'],',');
            if($i == 0)
            {
                $data[$k]['path_name'] = '顶级';
            }else{
                //拼接|--
                $data[$k]['path_name'] = str_repeat('|----',$i).$data[$k]['type_name'];
            }
        }
        if(empty($arr)){
            $data_num = $data_num_all->count();
        }else{
            $data_num = $data_is->count();
        }

        $this->view->assign('count',$data_num);//传递条数
        $this->view->assign(['data'=>$data,'count'=>$data_num]);//传递多条
        return $this->view->fetch('personneltype/renshi_bumen-list');
    }

    //添加
    public function create()
    {
        $typeData = self::getTypeName();
        $this->view->assign('count',$typeData);//传递条数
        $this->view->assign(['data'=>$typeData,'count'=>$typeData]);//传递多条
        return $this->view->fetch('personneltype/renshi_bumen-add');
    }

    //保存
    public function save()
    {
        if(Request::isPost()){
            $status=0;
            $data=Request::param();

            $rule=[
                'type_name|部门名称'=>'require|unique:StaffType',
            ];
            $back_rule=[
                'type_name'=>'部门名称必选！',
                'type.unique'=>'该部门名称已经存在！',
            ];
            $result=$this->validate($data,$rule,$back_rule);
            if (true===$result){


                $pid = $data['parent_id'];
                if($pid == 0){
                    $data['path'] = 0;
                }else{
                    $p_data = StaffType::find($pid);
                    $data['path'] = $p_data['path'].','.$p_data['id'];
                }


                $addInv=StaffType::create($data);
                if ($addInv){
                    $status=1;
                    $result='添加成功';
                }else{
                    $status=0;
                    $result='添加失败，请稍后重试';
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
            $info_code = StaffType::where('id','=',$id)->find();
        }
        $typeData = self::getTypeName();
        $this->view->assign(['data'=>$typeData,'info_code'=>$info_code]);//传递多条
        return $this->view->fetch('personneltype/renshi_bumen-edit');
    }

    //更新保存
    public function update()
    {
        if (Request::isPost())
        {
            $status = 0;
            $data = Request::param();

            $rule=[
                'type_name|部门名称'=>'require|unique:StaffType',
            ];
            $back_rule=[
                'type_name'=>'部门名称必选！',
                'type_name.unique'=>'该部门名称已经存在！',
            ];
            $result=$this->validate($data,$rule,$back_rule);
            if ($result===true){
                $info_code = StaffType::where('id',$data['id'])->find();
                if($info_code['type_name']==$data['type_name']&&$info_code['parent_id']==$data['parent_id'])
                {
                    $status=0;
                    $result='您当前的操作为发生任何改变';
                }else{
                    $pid = $data['parent_id'];
                    if($pid == 0){
                        $data['path'] = 0;
                    }else{
                        $p_data = StaffType::find($pid);
                        $data['path'] = $p_data['path'].','.$p_data['id'];
                    }

                    if (StaffType::update([
                        'type_name'=>$data['type_name'],
                        'parent_id'=>$data['parent_id'],
                        'path'=>$data['path']
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

    //拼接商品分类名字
    public static function getTypeName(){
        $sql = "select id,type_name,parent_id,path,CONCAT(path,',',id) AS paths from tp_personnel_type ORDER BY paths asc";
        $typeData = Db::query($sql);
        foreach($typeData as $k=>$v){
            //统计逗号出现的次数
            $i = substr_count($v['path'],',');

            if($i == 0)
            {
                $data[$k]['path_name'] = '顶级';
            }else{
                //拼接|--
                $typeData[$k]['type_name'] = str_repeat('|----',$i).$typeData[$k]['type_name'];
            }
        }
        return $typeData;
    }

}
<?php
/**
 * 店铺地址.
 * User: Administrator
 * Date: 2018/11/30
 * Time: 15:05
 */

namespace app\admin\controller;
use app\common\controller\Base;
use app\common\controller\Swift;
use think\facade\Request;
use app\common\model\Mendian;

class Mendians extends Base
{
    //列表
    public function lists()
    {
        $arr = [];
        $res_arr = (new Swift())->selectPublic();

        $arr = array_merge($arr,$res_arr);
        $data_num_all = Mendian::select();
        $data_is	  = Mendian::where($arr) -> order('create_time','asc');
        $data  		  = $data_is -> paginate(10)->appends(Request::param());
        if(empty($arr)){
            $data_num = $data_num_all->count();
        }else{
            $data_num = $data_is->count();
        }

        $this->view->assign('count',$data_num);//传递条数
        $this->view->assign(['data'=>$data,'count'=>$data_num]);//传递多条
        return $this->view->fetch('mendians/mendian-list');
    }

    //添加
    public function create()
    {

        return $this->view->fetch('mendians/mendian-add');
    }


    //保存
    public  function save()
    {
        if (Request::isPost())
        {
            $status = 0;
            $data = Request::param();
            $rule=[
                'shop_name|店名'=>'require',
                'shop_phone|电话'=>'require',
                'shop_address|地址'=>'require',
                'category|类别'=>'require',
            ];
            $back_rule=[
                'shop_name'=>'店名为空！',
                'shop_phone'=>'服务店电话为空！',
                'shop_address'=>'服务店地址为空！',
                'category'=>'请选择服务店类别',
            ];
            $result=$this->validate($data,$rule,$back_rule);
            if ($result===true){
                if (Serviceshop::create([
                    'shop_name'=>$data['shop_name'],
                    'shop_phone'=>$data['shop_phone'],
                    'shop_address'=>$data['shop_address'],
                    'create_time'=>time(),
                    'category'=>$data['category'],
                    'state'=>1,
                ])){
                    $status = 1;
                    $result = '添加成功';
                }else{
                    $status = 0;
                    $result = '添加失败';
                }
            }
            return json_encode(['status' => $status, 'message' => $result]);
        }
    }

    //修改
    public function edit()
    {
        $id = Request::param('id');
        $data=Mendian::where('id',$id)->find();
        $this->view->assign('data',$data);
        return $this->view->fetch('mendians/mendian-edit');

    }

    //更新
    public function update()
    {

        if (Request::isPost())
        {
            $status = 0;
            $data = Request::param();
            $id = $data['id'];
            $rule=[
                'shop_name|店名'=>'require',
                'shop_phone|电话'=>'require',
                'shop_address|地址'=>'require',
                'category|店类别'=>'require',
                'state|店类别'=>'require',
            ];
            $back_rule=[
                'shop_name'=>'店名未填写！',
                'shop_phone'=>'电话未填写！',
                'shop_address'=>'手机号未填写！',
                'category'=>'店分类未选择！',
                'state'=>'未选择状态值！',
            ];
            $result=$this->validate($data,$rule,$back_rule);
            //其他数据验证限制

            //数据处理 (其他：数组批量保存，事物保存)
            if ($result===true){
                //其他的业务逻辑放前面

                //数据存储再处理
                $info = Mendian::where('id',$id)->find();
                if ($info['shop_name']==$data['shop_name']&&
                    $info['shop_phone']==$data['shop_phone']&&
                    $info['shop_address']==$data['shop_address']&&
                    $info['category']==$data['category']&&
                    $info['state']==$data['state']
                ){
                    $status=0;
                    $result='未作出更改';
                }else{
                    if (Mendian::where('id',$id)->update([
                        'shop_name'=>$data['shop_name'],
                        'shop_phone'=>$data['shop_phone'],
                        'shop_address'=>$data['shop_address'],
                        'category'=>$data['category'],
                        'state'=>$data['state'],
                    ])){
                        $status=1;
                        $result='更新成功';
                    }else{
                        $status=0;
                        $result='更新失败';
                    }
                }
            }

            return ['status'=>$status,'message'=>$result];
        }
    }



    //后台管理服务店列表
    public  function servrtShop()
    {
        $data = Mendian::select();
        $data->visible(['id','shop_name','shop_phone','shop_address','category','state'])->toArray();

        return $data;
    }

}
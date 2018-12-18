<?php
/**
 * 微信咨询中心.
 * User: Administrator
 * Date: 2018/12/17
 * Time: 10:21
 */
namespace app\api\controller;
use think\Controller;
use think\facade\Request;
use app\common\model\Informations;

class Consultation extends Controller
{

    protected function initialize()
    {
        header("Access-Control-Allow-Origin:*");
    }

    //微信中心-咨询列表
    public function lists()
    {

        $arr = [];
        $init_arr = [];
        $res_arr = [];
        $init_arr[] = ['state','=',1];
        if ($search_title = Request::param('search_title')){  $arr[] = ['info_title','like',"%{$search_title}%"];}

        $page =   empty(Request::param('page')) ? 1: Request::param('page');
        $limit =  empty(Request::param('limit')) ? 10: Request::param('limit');
        if($page == 1){ $page = 0; }else{ $page = (($page-1)*$limit); }

        $arr = array_merge($init_arr,$arr,$res_arr);
        $data_num_all = Informations::where($init_arr)->select();
        $data_is	  = Informations::where($arr) -> order('create_time','asc');
        $data  		  =	$data_is -> limit($page,$limit) ->select();

        if(empty($arr)){
            $data_num = $data_num_all->count();
        }else{
            $data_num = $data_is->count();
        }

        $res = [
            'code'=>1,
            'msg'=>'成功',
            "count"=> $data_num,
            'data'=>$data
        ];
        return json_encode($res);
    }

    //咨询详情
    public function info()
    {
        if(Request::isPost()){

            $oid = Request::param('oid');

            if(isset($oid)){
                $data=Informations::where([  ['id','=',$oid], ['state','=',1]  ])->find();

                if(!empty($data)){
                    $nextData=Informations::where([  ['id','=',($oid+1)], ['state','=',1]  ])->find();
                    $upData=Informations::where([  ['id','=',($oid-1)], ['state','=',1]  ])->find();

                    //上一条 下一条
                    $nextId = $nextData['id'];
                    $nextTielt = $nextData['info_title'];
                    $upId = $upData['id'];
                    $upTielt = $upData['info_title'];

                    //本条
                    $info_type = $data['info_type'];
                    $info_abstract = $data['abstract'];
                    $info_title = $data['info_title'];
                    $info_content = $data['info_content'];
                    $info_img = $data['info_img'];
                    $create_time = $data['create_time'];

                    $data=compact('info_type','info_title','info_abstract','info_content','info_img','create_time','nextId','nextTielt','upId','upTielt');
                }
            }





            if (!empty($data)){
                $res = [
                    'code'=>1,
                    'msg'=>'成功',
                    'data'=>$data
                ];
            }else{
                $res = [
                    'code'=>0,
                    'msg'=>'error',
                ];
            }
            return json_encode($res);
        }
    }


}
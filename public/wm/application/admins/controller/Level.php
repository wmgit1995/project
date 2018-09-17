<?php
namespace app\admins\controller;
use think\Config;
use think\Controller;
use think\Request;
use think\Db;
use think\View;
use \think\Validate;
class level extends Base{

    //会员等级列表
    public function  index(){
        if(Request::instance()->get("name")){
            $levelname = Request::instance()->get('name');
            $name="%".$levelname."%";
            $lists=Db::table('hz_level')->where('name','like',$name)->where("is_del",0)->paginate(10,false,['query'=>['title'=>$levelname]]);
        }else {
            $levelname ='';
            $lists = Db::table('hz_level')->where("is_del",0)->order("sort","asc")->paginate(10);
        }
        $list = Db::table('hz_level')->field("id,name")->where('is_del',0)->order(['sort'=>'asc','id'=>'asc'])->column('name','id');
        return $this->fetch('',['lists'=>$lists,'name'=>$levelname,'list'=>$list]);
    }

    //新增/修改会员等级
    public function level_save(){
        if(request()->isPost()){
            $data = request::instance()->param();
            unset($data['/admins/level/level_save_html']);
           if($data['id']){
               if(!Db::table('hz_level')->where('name',$data['name'])->where('id','neq',$data['id'])->where('is_del',0)->find()){
                   $res=Db::table('hz_level')->update($data);
                   if($res!==false){
                       return 'ok';
                   }
               }else{
                   return '01';
               }
           }else{
               if(!Db::table('hz_level')->where('name',$data['name'])->where('is_del',0)->find()){
                   $res=Db::table('hz_level')->insert($data);
                   if($res){
                       return 'ok';
                   }
               }else{
                   return '01';
               }

           }
        }else{
            $list = Db::table('hz_level')->field("id,name")->where('is_del',0)->order(['sort'=>'asc','id'=>'asc'])->select();
            if(Request::instance()->get("id")){
                $id=intval(Request::instance()->get("id"));
                $info=Db::table('hz_level')->where('id',$id)->find();
            }else{
                $info = [];
            }
            return $this->fetch("",['info'=>$info,'list'=>$list]);
        }
    }

    //假删除
    public function level_del(){
        $id = Request::instance()->get('id');
        $info = Db::table('hz_level')->where('front_id',$id)->where('is_del',0)->find();
        if($info){
            $data=[
                'status'=>2,
                'msg'  =>'该等级作为达标条件，请修改后删除！'
            ];

        }else{
            $res = Db::table('hz_level')->where('id',$id)->update(['is_del'=>1]);
            if($res){
                $data = ['status'=>1,'msg'=>''];
            }else{
                $data = ['status'=>0,'msg'=>'删除失败,请稍后再试'];
            }
        }
        return json($data);
    }
}
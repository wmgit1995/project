<?php
namespace app\admins\controller;
use think\Config;
use think\Controller;
use think\Request;
use think\Db;//使用数据库链接类
use app\index\model\Staff;
use think\View;
class Agent extends Base{
   //代理管理列表
	public function index(){
		if(Request::instance()->get("uid")){
		   $aid=Request::instance()->get("uid");
		   $list=Db::table('admin')->where('id',$aid)->paginate(10);
		}else{
			$list=Db::table('admin')->paginate(10);
		}
       
       // var_dump($list);
		return $this->fetch("",['list'=>$list]);
	}
	//添加代理
	public function agent_add(){
		if(Request::instance()->post("uname")){
           $uname=Request::instance()->post("uname");
           $pwd=Request::instance()->post("pwd");
           $phone=Request::instance()->post("phone");
           $rate=Request::instance()->post("rate");
           $time=time();
           $res=Db::table('admin')->insert(['admin_name'=>$uname,"admin_pass"=>md5($pwd),"admin_phone"=>$phone,"admin_rebate"=>$rate,'admin_regtime'=>$time]);
           if($res){
                return "ok";
           }else{
           	    return false;
           }
		}
		return $this->fetch();
	}
	//禁用启用
	public function status_edit(){
		 $aa=Request::instance()->param("status");
		 $aid=Request::instance()->param("aid");
		 if($aa==1){
           $status=0;
		 }else{
		   $status=1;
		 }
		 $res=Db::table('admin')->where('id',$aid)->update(['admin_status'=>$status]);

		 if($res){
            return "ok";
		 }
	}
	//代理修改
	public function agent_edit(){
        $aid=Request::instance()->param("id");
         if(Request::instance()->isPost()){
            $uname=Request::instance()->post("uname");
            $rate=Request::instance()->post("rate");
            $id=Request::instance()->post("aid");
            $res=Db::table('admin')->where('id',$id)->update(['admin_rebate'=>$rate,'admin_name'=>$uname]);
            if($res){
              return "ok";
            }
         }
        $list=Db::table('admin')->where('id',$aid)->find();
		return $this->fetch("",['list'=>$list]);
	}
	//删除代理
	public function agent_del(){
          $aid=Request::instance()->param("aid");
          $res=Db::table('admin')->where('id',$aid)->delete();
          $res2=Db::table('user')->where('agent_id',$aid)->delete();
          if($res){
            return "ok";
          }
	}
}

<?php
namespace app\admins\controller;
use think\Config;
use think\Controller;
use think\Request;
use think\Db;//使用数据库链接类
use app\index\model\Staff;
use think\View;
class Recharge extends Base{
  //彩分充值
  public function points_add(){
  	if(Request::instance()->isPost()){
            $aid=Request::instance()->post("aid");
            $points=Request::instance()->post("points");
            $time=time();
            $item=Db::table('admin')->where('id',$aid)->find();
            if(!$item){
             return "该代理不存在";
            }
            if($points>0){
             $water_due=2;
            }else{
             $water_due=1;
            }
          
            $col=$item['admin_money']+$points;
            if($col<0){
               $col==0;
            }
            $res=Db::table('admin_points')->insert(['aid'=>$aid,'points'=>$points,'addtime'=>$time,'admin_moneys'=>$col,'water_due'=>$water_due]);
               $info=Db::table('admin')->where('id',$aid)->update(['admin_money'=>$col]);
            if($res && $info){
              return "ok";
            }else{
              return "充值失败";
            }
    }
   return $this->fetch();
  }
  //代理彩分充值记录
  public function points_list(){
  	if(Request::instance()->get("uid")){
		$aid=Request::instance()->get("uid");
		$res=Db::table('admin_points')->where('aid',$aid)->where('water_status',1)->order('addtime desc')->paginate(10);
	}else{
		$res=Db::table('admin_points')->where('water_status',1)->order('addtime desc')->paginate(10);
	}
  	
  	return $this->fetch("",['list'=>$res]);
  }
  //删除彩分记录
  public function points_del(){
  	  $aid=Request::instance()->param("aid");
          $res=Db::table('admin_points')->where('point_id',$aid)->delete();
          if($res){
            return "ok";
          }
  }
}

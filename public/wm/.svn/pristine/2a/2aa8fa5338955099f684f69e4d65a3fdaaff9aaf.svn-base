<?php
namespace app\admins\controller;
use think\Config;
use think\Controller;
use think\Request;
use think\Db;//使用数据库链接类
use app\index\model\Staff;
use think\View;
class Water extends Base{
  
  //代理彩分流水记录
  public function water_list(){
  	if(Request::instance()->get("uid")){
		$aid=Request::instance()->get("uid");
    // Db::table('team')->join('user','user.id=team.uid')->where('team.uid',$uid)->select();
		$res=Db::table('admin_points')->join('admin','admin.id=admin_points.aid')->where('admin_points.aid',$aid)->order('admin_points.addtime desc')->paginate(10);
  	}else{
  		$res=Db::table('admin_points')->join('admin','admin.id=admin_points.aid')->order('admin_points.addtime desc')->paginate(10);
  	}
    	return $this->fetch("",['list'=>$res]);
  }
  //删除代理彩分流水记录
  public function water_del(){
  	  $aid=Request::instance()->param("aid");
      $res=Db::table('admin_points')->where('point_id',$aid)->delete();
      if($res){
        return "ok";
      }
  }
  //会员彩分流水记录
  public function user_water(){
      if(Request::instance()->get("uid")){
        $aid=Request::instance()->get("uid");
        //Db::table('team')->join('user','user.id=team.uid')->where('team.uid',$uid)->select();
        $res=Db::table('user_points')->where('vip_id',$aid)->order('recharge_time desc')->paginate(10);
      }else{
        $res=Db::table('user_points')->order('recharge_time desc')->paginate(10);
      }
        return $this->fetch("",['list'=>$res]);
  }
  
}

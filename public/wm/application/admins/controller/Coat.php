<?php
namespace app\admins\controller;
use think\Config;
use think\Controller;
use think\Request;
use think\Db;//使用数据库链接类
use app\index\model\Staff;
use think\View;
class Coat extends Base{
  //代理彩分反水
  public function admin_coat(){
    if(Request::instance()->get("uid")){
        $aid=Request::instance()->get("uid");

        // Db::table('team')->join('user','user.id=team.uid')->where('team.uid',$uid)->select();
        $res=Db::table('admin')->where('id',$aid)->order('admin_bets desc')->paginate(10);
        }else{
          $res=Db::table('admin')->order('admin_bets desc')->paginate(10);
        }
          return $this->fetch("",['list'=>$res]);
  }
  //确认代理反水
  public function coat_admin(){
      $aid=Request::instance()->param("aid");
      $water=Request::instance()->param("water");
      $bets=Request::instance()->param("bets");
      $rebate=Request::instance()->param("rebate");
      $list=Db::table('admin')->where('id',$aid)->find();
      $money=$list['admin_money']+$water;
      $time=time();
      $res=Db::table('admin')->where('id',$aid)->update(['admin_bets'=>0,'admin_water'=>0]);
      $info=Db::table('admin_points')->insert(['aid'=>$aid,'points'=>$water,'addtime'=>$time,'admin_moneys'=>$money,'water_status'=>2,'water_due'=>2,'admin_bet'=>$bets,'admin_reb'=>$rebate]);
      if($res && $info){
        return "ok";
      }
  }
  //会员彩分反水
  public function user_coat(){
       if(Request::instance()->get("uid")){
        $aid=Request::instance()->get("uid");
        // Db::table('team')->join('user','user.id=team.uid')->where('team.uid',$uid)->select();
        $res=Db::table('user')->where('user_id',$aid)->order('user_bets desc')->paginate(10);
        }else{
        $res=Db::table('user')->order('user_bets desc')->paginate(10);
        }
          return $this->fetch("",['list'=>$res]);
  }
  //代理反水支出
  public function admin_spend(){
        $aid=Request::instance()->param("uid");
        $start_time=strtotime(Request::instance()->param('start_time'));
        $end_time=strtotime(Request::instance()->param('end_time'));
      if($aid || $start_time || $end_time){
           
            $where['admin_points.water_status']=['=',2];
            if($start_time && $end_time){
                $where['admin_points.addtime']=array('between', array($start_time,$end_time));
            }elseif($start_time){
                $where['admin_points.addtime']=['>',$start_time];
            }elseif($end_time){
                $where['admin_points.addtime']=['<',$end_time];
            }elseif($aid){
                $where['admin_points.aid']=['=',$aid];
            }
            // var_dump(Request::instance()->param('end_time'));
            // var_dump($where);
        $res=Db::table('admin_points')->join('admin','admin.id=admin_points.aid')->where($where)->order('admin_points.addtime desc')->paginate(10);
       
      }else{
        $res=Db::table('admin_points')->join('admin','admin.id=admin_points.aid')->where('admin_points.water_status',2)->order('admin_points.addtime desc')->paginate(10);
      }
      
      return $this->fetch("",['list'=>$res]);
  }
  //会员反水支出
  public function user_spend(){
        $aid=Request::instance()->param("uid");
        $start_time=strtotime(Request::instance()->param('start_time'));
        $end_time=strtotime(Request::instance()->param('end_time'));
      if($aid || $start_time || $end_time){
            
            $where['water_status']=['=',2];
            if($start_time && $end_time){
                $where['recharge_time']=array('between', array($start_time,$end_time));
            }elseif($start_time){
                $where['recharge_time']=['>',$start_time];
            }elseif($end_time){
                $where['recharge_time']=['<',$end_time];
            }elseif($aid){
                $where['vip_id']=['=',$aid];
            }
        $res=Db::table('user_points')->where($where)->order('recharge_time desc')->paginate(10);
       
      }else{
        $res=Db::table('user_points')->where('water_status',2)->order('recharge_time desc')->paginate(10);
      }
      return $this->fetch("",['list'=>$res]);
  }
 
 
 
}

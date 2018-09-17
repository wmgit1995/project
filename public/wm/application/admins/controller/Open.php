<?php
namespace app\admins\controller;
use think\Config;
use think\Controller;
use think\Request;
use think\Db;
use think\View;
use \think\Validate;
class Open extends Base{
	//****开奖记录****//
	public function prize_list(){
		$money=0;
		$list=Db::table('lottery')->order('createtime desc')->paginate(10);
		$num=count($list);
		for($i=0;$i<$num;$i++){
			$res[$i]['mowei']=explode(',',$list[$i]['lottery_value'])['6'];
		}
		return $this->fetch("",['list'=>$list,'res'=>$res]);
	}
	//****停盘or开盘****//
	public function stop_pan(){
		$request=request();
		$ids=$request->param('id');
		if($ids==1){
			$res=Db::table('user')->select();
			foreach($res as $k=>$v){
				$arr[]=$v['user_id'];
			}
			$where['user_id']=array('in',$arr);
			$bool=Db::table('user')->where($where)->update(['sealing_plate'=>0]);
			if($bool){
				return "ok";
			}else{
				return "已经停盘";
			}
		}else{
			$res=Db::table('user')->select();
			foreach($res as $k=>$v){
				$arr[]=$v['user_id'];
			}
			$where['user_id']=array('in',$arr);
			$bool=Db::table('user')->where($where)->update(['sealing_plate'=>1]);
			if($bool){
				return "ok";
			}else{
				return "已经开盘";
			}
		}
	}
	//****停注or开注****//
	public function stop_zhu(){
		$request=request();
		$ids=$request->param('id');
		if($ids==1){
			$res=Db::table('user')->select();
			foreach($res as $k=>$v){
				$arr[]=$v['user_id'];
			}
			$where['user_id']=array('in',$arr);
			$bool=Db::table('user')->where($where)->update(['stop_bet'=>0]);
			if($bool){
				return "ok";
			}else{
				return "已经停注";
			}
		}else{
			$res=Db::table('user')->select();
			foreach($res as $k=>$v){
				$arr[]=$v['user_id'];
			}
			$where['user_id']=array('in',$arr);
			$bool=Db::table('user')->where($where)->update(['stop_bet'=>1]);
			if($bool){
				return "ok";
			}else{
				return "已经开注";
			}
		}
	}
}

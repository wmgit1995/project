<?php
namespace app\admins\controller;
use think\Config;
use think\Controller;
use think\Request;
use think\Db;
use think\View;
use \think\Validate;
class Stop extends Base{
	//****开奖记录****//
	public function stop_list(){
		$money=0;
		$list=Db::table('lottery')->order('createtime desc')->paginate(10);
		$num=count($list);
		for($i=0;$i<$num;$i++){
			$res[$i]['mowei']=explode(',',$list[$i]['lottery_value'])['6'];
		}
		return $this->fetch("",['list'=>$list,'res'=>$res]);
	}

}

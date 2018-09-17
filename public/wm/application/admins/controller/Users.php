<?php
namespace app\admins\controller;
use think\Config;
use think\Controller;
use think\Request;
use think\Db;//使用数据库链接类
use app\index\model\Staff;
use think\View;
class Users extends Base{
	//会员投注汇总
	public function users_list(){   
    $list=Db::table('sums')->select();
    $num=Db::table('nums')->find();
		return $this->fetch("",['list'=>$list,'moneys'=>$num['moneys']]);
	}
}

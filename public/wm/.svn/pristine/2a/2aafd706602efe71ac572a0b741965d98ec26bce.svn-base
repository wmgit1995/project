<?php
namespace app\admins\controller;
use think\Config;
use think\Controller;
use think\Request;
use think\Db;//使用数据库链接类
use app\index\model\Staff;
use think\View;
class Ceil extends Base{
	//下注上限
	public function ceil_edit()
    {
        // $aid=Request::instance()->param("id");
        if (Request::instance()->isPost()) {
            $rate=Request::instance()->post("rate");
            $id=Request::instance()->post("aid");
            $res=Db::table('ceil')->where('id',$id)->update(['ceil'=>$rate]);
            if ($res) {
                return "ok";
            }
        } elseif (Request::instance()->isGet()) {
        $id=Request::instance()->get("aid");
        $res=Db::table('ceil')->where('id',$id)->update(['ceil'=>0]);
            if ($res){
                return "ok";
            }
        }
        $list=Db::table('ceil')->find();
		return $this->fetch("",['list'=>$list]);
	}

}

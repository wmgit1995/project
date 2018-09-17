<?php
namespace app\admins\controller;
use think\Config;
use think\Controller;
use think\Request;
use think\Db;//使用数据库链接类
use app\index\model\Staff;
use think\View;
class Notice extends Base{
	//公告赔率
	public function notice_edit(){
         if(Request::instance()->isPost()){
            $odds=Request::instance()->post("odds");
            $rate=Request::instance()->post("rate");
            $id=Request::instance()->post("aid");
            $res=Db::table('notice')->where('id',$id)->update(['notice'=>$rate,'odds'=>$odds]);
            if($res){
              return "ok";
            }
         }
        $list=Db::table('notice')->find();
		return $this->fetch("",['list'=>$list]);
	}

}

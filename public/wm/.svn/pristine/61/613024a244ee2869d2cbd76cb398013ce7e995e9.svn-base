<?php
namespace app\admins\controller;
use think\Config;
use think\Controller;
use think\Request;
use think\Db;
use think\View;
use \think\Validate;
class Play extends Base{
	public function play_list()
    {
        $uid=Request::instance()->get("uid");
        $issue=Request::instance()->get("issue");
        if($uid){
            if($issue){
                $list=Db::table("user_betting")->where("issue",$issue)->where("user_id",$uid)->paginate(5);
            }else{
                $list=Db::table("user_betting")->where("user_id",$uid)->paginate(5);
            }
        }else if($issue){
            $list=Db::table("user_betting")->where("issue",$issue)->paginate(5);
        }else{
            $list=Db::table("user_betting")->paginate(5);
        }
        return $this->fetch("",['list'=>$list]);
    }
    public function play_lists(){
        $request=request();
        $issue=$request->param("issue");
        $data=Db::table("user_bettings")->where("issue",$issue)->paginate(5);
        return $this->fetch("",['data'=>$data]);
    }
}

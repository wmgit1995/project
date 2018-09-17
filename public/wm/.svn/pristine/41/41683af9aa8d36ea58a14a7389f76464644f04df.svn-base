<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Session;

class UserBase extends Controller {
    public function _initialize(){
        Session::set('user_id', 1);
        if(!session("user_id")){
            $this->error("你还没有登录",url('Login/login'));
        }else{
            $info = Db::table("hz_user")->alias("a")
                ->join("hz_level b","a.level_id = b.id","left")
                ->join("hz_user_addition c","a.id = c.user_id","left")
                ->field("a.*,b.sort,b.name,b.static,c.id_card,c.invitation_code,c.bank_card,c.purse_address")->where("a.id",session('user_id'))->find();
            $arr = explode('-',$info['static']);
            $info['maxnum'] = $arr[1];
            $info['minnum'] = $arr[0];
            $this->assign("user",$info);
        }
    }
}
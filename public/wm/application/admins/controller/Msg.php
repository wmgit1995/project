<?php
namespace app\admins\controller;
use think\Config;
use think\Controller;
use think\Request;
use think\Db;
use think\Session;
use think\View;
use think\Validate;

class msg extends Base{
    /*
     * 留言列表
     * type:1未回复2已回复
     */
    public function index(){
        $data = Request::instance()->param();
        if($data['type']==1){
            $status = 'eq';
        }else{
            $status = "gt";
        }
        $title = Request::instance()->get('title');
        if($title){
            $stitle = "%".$title."%";
            $lists = Db::table('hz_msg')->alias('a')
                ->join("hz_user b","a.user_id = b.id","left")
                ->field("a.*,b.user_nickname,b.mobile")
                ->where("a.title|a.content","like",$stitle)
                ->where("a.reply_time",$status,0)
                ->order('id desc')->paginate(10);
        }else{
            $lists = Db::table('hz_msg')->alias('a')
                ->join("hz_user b","a.user_id = b.id","left")
                ->field("a.*,b.user_nickname,b.mobile")
                ->where("a.reply_time",$status,0)
                ->order('id desc')->paginate(10);
        }
        return $this->fetch('index',['lists'=>$lists,'title'=>$title,'type'=>$data['type']]);
    }
    /*
     * 回复消息
     */
    public function msg_save(){
        if(request()->isPost()){
            $data = request::instance()->param();
            if($data['reply']){
                $adminid = Session::get("adminid");
                $arr = Db::table("hz_msg")->update(['id'=>$data['id'],'reply'=>$data['reply'],'reply_time'=>time(),'admin_id'=>$adminid]);
                if($arr!==false){
                    return 'ok';
                }else{
                    return '01';
                }
            }else{
                return '02';
            }
        }else{
            $id = Request::instance()->get('id');
            $type = Request::instance()->get('type');
            if($id){
                $info = Db::table("hz_msg")->alias("a")
                                ->join("hz_user b","a.user_id = b.id","left")
                                ->field("a.*,b.user_nickname,b.mobile")->where("a.id",$id)->find();
                return $this->fetch("",['info'=>$info]);
            }else{
                return $this->error('系统错误',url('index',['type'=>$type]));
            }
        }
    }
}
<?php
namespace app\admins\controller;

use think\Db;
use think\Request;

class fund extends Base{

    /**
     * 收益列表
     */
    public function index(){
        $title = Request::instance()->param("title");
        $type = Request::instance()->param("type");
        $data['title'] = $title;
        $data['type'] = $type;
        if($title){
            $stitle = "%".$title."%";
            $where['b.user_nickname']= ['like',$stitle];
            if($type>0){
                $where['a.type']= ['eq',$type];
            }
            $lists = Db::table("hz_amount_log")->alias("a")
                ->join("hz_user b","a.user_id = b.id","left")
                ->field("a.*,b.user_nickname")
                ->where($where)->order("a.id desc")->paginate(10,false,['query' => ['title'=>$title,'type'=>$type]]);
        }else{
            $lists = Db::table("hz_amount_log")->alias("a")
                ->join("hz_user b","a.user_id = b.id","left")
                ->field("a.*,b.user_nickname")
                ->order("a.id desc")->paginate(10);

        }
        $type_lists = ["1"=>"有机果","2"=>"信用押金","3"=>"信用种子","4"=>"门票"];
        $from_lists = ["1"=>"互助","2"=>"静态收益","3"=>"动态收益","4"=>"admin","5"=>"积分转换",'6'=>"充值","7"=>"提现"];
        return $this->fetch("",['lists'=>$lists,"type_lists"=>$type_lists,"from_lists"=>$from_lists,'data'=>$data]);
    }
    /**
     * 收益统计
     */
    public function total(){
        $title = Request::instance()->param("title");
        if($title){
            $stitle = "%".$title."%";
            $lists = Db::table("hz_user")->where("user_nickname",'like',$stitle)->field("id,user_status,user_nickname,user_login,ticket,woc_num,credit_deposit,credit_seed,level_id,parent")->paginate(10,false,['query'=>['title'=>$title]])->each(function ($item,$key){
                //会员等级
                $item['level_name'] = Db::table("hz_level")->where("id",$item['level_id'])->value("name");
                //团队人数
                $item['team_num'] = Db::table("hz_user")->where("parent",'like',$item['parent']."-".$item['id']."-%")->count('*');
                //完成的排单
                $item['form_done'] = Db::table("hz_formation")->where("user_id",$item['id'])->where("status",3)->count("*");
                //进行中的排单
                $item['form_doing'] = Db::table("hz_formation")->where("user_id",$item['id'])->where("status",'lt',3)->count("*");
                //排单总金额
                $item['form_total'] = Db::table("hz_formation")->where("user_id",$item['id'])->where("status",'elt',3)->value("sum(`amount`)");
                //排单总收益
                $item['form_income'] = Db::table("hz_amount_log")->where("user_id",$item['id'])->where("from",2)->value("sum(`num`)");
                //累计奖金
                $item['award'] = Db::table("hz_amount_log")->where("user_id",$item['id'])->where("from",3)->value("sum(`num`)");
                return $item;
            });
        }else{
            $lists = Db::table("hz_user")->field("id,user_status,user_nickname,user_login,ticket,woc_num,credit_deposit,credit_seed,level_id,parent")->paginate(10)->each(function ($item,$key){
                //会员等级
                $item['level_name'] = Db::table("hz_level")->where("id",$item['level_id'])->value("name");
                //团队人数
                $item['team_num'] = Db::table("hz_user")->where("parent",'like',$item['parent']."-".$item['id']."%")->count('*');
                //完成的排单
                $item['form_done'] = Db::table("hz_formation")->where("user_id",$item['id'])->where("status",3)->count("*");
                //进行中的排单
                $item['form_doing'] = Db::table("hz_formation")->where("user_id",$item['id'])->where("status",'lt',3)->count("*");
                //排单总金额
                $item['form_total'] = Db::table("hz_formation")->where("user_id",$item['id'])->where("status",'elt',3)->value("sum(`amount`)");
                //排单总收益
                $item['form_income'] = Db::table("hz_amount_log")->where("user_id",$item['id'])->where("from",2)->value("sum(`num`)");
                //累计奖金
                $item['award'] = Db::table("hz_amount_log")->where("user_id",$item['id'])->where("from",3)->value("sum(`num`)");
                return $item;
            });
        }
        return $this->fetch('',['lists'=>$lists,'title'=>$title]);
    }
}
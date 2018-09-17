<?php
namespace app\admins\controller;
use think\Config;
use think\Controller;
use think\Request;
use think\Db;
use think\Url;
use think\View;
use think\Validate;

class tree extends Base{
    /*
     * 树状图
     */
    public function index(){
        $title = Request::instance()->get('title');
        if($title){
            $stitle = '%'.$title.'%';
            $lists = Db::table("hz_user")->alias("a")->join("hz_level b","a.level_id = b.id",'left')
                        ->field("a.id,a.user_nickname as name,a.parent,b.name as lname")
                        ->where("a.user_nickname",'like',$stitle)
                        ->order("a.id asc")->paginate(10,false,['query'=>['title'=>$title]])
                        ->each(function ($item,$key){
                            $item['pId'] = 0;
                            $arr =  Db::table("hz_user")->alias("a")
                                ->join("hz_level b","a.level_id = b.id",'left')
                                ->field("a.id,a.user_nickname as name,a.parent,b.name as lname")->where("a.parent",$item['parent']."-".$item['id'])->order("a.id desc")->select();
                            foreach ($arr as $k=>$v){
                                $arr[$k]['pId'] = $item['id'];
                            }
                            $item['lists'] = $arr;
                            unset($arr);
                            return $item;
                        });
        }else{
            $lists = Db::table("hz_user")->alias("a")->join("hz_level b","a.level_id = b.id",'left')
                        ->field("a.id,a.user_nickname as name,a.parent,b.name as lname")->where("a.parent","0")
                        ->order("a.id asc")->paginate(10)
                        ->each(function ($item,$key){
                            $item['pId'] = 0;
                            $arr =  Db::table("hz_user")->alias("a")->join("hz_level b","a.level_id = b.id",'left')
                                ->field("a.id,a.user_nickname as name,a.parent,b.name as lname")->where("a.parent",$item['parent']."-".$item['id'])->order("a.id desc")->select();
                            foreach ($arr as $k=>$v){
                                $arr[$k]['pId'] = $item['id'];
                            }
                            $item['lists'] = $arr;
                            unset($arr);
                            return $item;
                        });
        }
        $newlists = $lists->all();
        $arr = [];
        foreach ($newlists as $v){
            //unset($v['parent']);
            if(empty($v['lists'])){
                $v['isParent'] = true;
                $list = [];
            }else{
                $list = $v['lists'];
                unset($v['lists']);
            }
            $v['url'] = "/admins/tree/index.html?title=".$v['name'];
            $v['name'] = $v['name']."[会员ID:{$v['id']},会员等级:{$v['lname']}]";
            $v['target'] = "_self";
            $arr[] = $v;
            if($list){
                foreach ($list as $vl){
                    $vl['url'] = "/admins/tree/index.html?title=".$vl['name'];
                    $vl['name'] = $vl['name']."[会员ID:{$vl['id']},会员等级:{$vl['lname']}]";
                    $vl['target'] = "_self";
                    $arr[] = $vl;
                }
                unset($list);
            }
        }
          $last = json_encode($arr,JSON_UNESCAPED_UNICODE);
        return $this->fetch('',['lists'=>$lists,'title'=>$title,'last'=>$last]);
    }
}
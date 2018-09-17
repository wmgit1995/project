<?php
namespace app\admins\controller;
use think\Config;
use think\Controller;
use think\Request;
use think\Db;//使用数据库链接类
use app\index\model\Staff;
use think\View;
class Pics extends Base{
	//公告赔率
	public function pic_edit(){
        // $aid=Request::instance()->param("id");
         if(Request::instance()->isPost()){
           
                $pid=Request::instance()->param('pid');
                $file=Request::instance()->file('file');
                $res=Db::table("pic")->find();
                    if($file){
                        $info = $file->move(ROOT_PATH . 'public/static/uploads');
                        if($info){
                            $url="/static/uploads/".date("Ymd",time())."/".$info->getFilename();
                        }
                    }
                    $time=time();
//                    $res=Db::table("oa_kefu")->insert($data);
                    $list=Db::execute("update pic set url='$url',create_time='$time'");
//                    $res=$data;
                    if($list){
                       return "ok";
                    }
                
          
         }
        $list=Db::table('pic')->find();
        $pic=substr($list['url'],strpos($list['url'],'/')+1);
        $pic2="/".$pic;
        // var_dump($pic);
		return $this->fetch("",['list'=>$list,'pic'=>$pic2]);
	}

}

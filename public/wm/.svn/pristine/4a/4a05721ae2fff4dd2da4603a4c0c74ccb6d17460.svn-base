<?php
namespace app\admins\controller;
use think\Controller;
use think\Db;
use think\Session;
class Login extends Controller
{
    /**
     *  后台登录打算
     */
    public function login(){
        $prefix=config("database.prefix");
        if (request()->isPost()){
             $data=input('post.');
             $res=Db("admins")->where('username',input('post.username'))->find();
             if($res['username']){;
                if(md5(md5(input('post.password')))==$res['password']){
                    Session::set('username',$res['username']);
                    Session::set('adminid',$res['id']);
                    return $this->redirect('Index/index');
                }else{
                    $this->success('账号或密码不正确！',url('Login/login'));
                }
             }else{
                    $this->success('账号不存在！',url('Login/login'));
             }
        }
        return view('login');
      }
//    public function  test(){
//        //select admin.admin_phone,count(*) FROM `user` INNER JOIN admin ON admin.id=`user`.agent_id GROUP BY admin.id
//        $res=Db::table("admin")->field('admin.admin_phone,COUNT(*) as total')->join('user','user.agent_id=admin.id','LEFT')->group('admin.id')->select();
//        var_dump($res);
//    }

}

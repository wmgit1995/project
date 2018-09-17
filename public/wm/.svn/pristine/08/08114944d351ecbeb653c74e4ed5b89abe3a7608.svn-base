<?php
namespace app\admins\controller;
use think\Controller;
class Index extends Base
{
    public function index()
    {

        return view('index');
    }

    public function left()
    {
        echo 4324;
        return $this->fetch();
    }

    public function right()
    {
        return $this->fetch();
    }

    public function header()
    {
        return $this->fetch();
    }
    public function welcome()
    {
        return $this->fetch();
    }
    /*
    退出
     */
    public function exit_system(){
        // dump($_SESSION);die;
        // Session::delete('think.username');
        session(null, 'think');
        $this->redirect('login/login');
    }
}

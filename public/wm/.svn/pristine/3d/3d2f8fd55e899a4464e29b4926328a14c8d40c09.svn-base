<?php
namespace app\admins\controller;
use think\Config;
use think\Controller;
use think\Request;
use think\Db;//使用数据库链接类
use app\index\model\Staff;
use think\View;
class Base extends Controller{
	/*protected function __construct(){

	}*/
    //初始化父类construct构造函数
    protected function _initialize()
    {
    		parent::_initialize();
    		//过滤不需要登陆的行为
    		$allowUrl = ['admin/index/login',
    		'admin/index/logout',
    		'admin/index/getverify',
    		];
        if(!session('username')){
          $this->success('请先登录！',url('Login/login'));
        }
    }
}
?>

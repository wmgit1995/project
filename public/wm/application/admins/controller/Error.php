<?php
namespace app\admins\controller;
/*空控制器、空操作。单入口空模块在配置文件里加*/
class Error{
			public function index(){
					$url=$_SERVER['HTTP_HOST'];
					redirect($url);
					// return '控制器不存在';
			}
			public function _empty($method)
			{
					$url=$_SERVER['HTTP_HOST'];
					redirect($url);
			    return '你访问的方法'.$method.'不存在哦';
			}
}

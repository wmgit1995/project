<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Request;
use think\captcha\Captcha;
class Tradingcenter extends Controller{

	/**
	 * 交易中心
	 */
	public function index(){
		
		return $this->fetch();
		
	}
	/**
 	 * C2C
	 */
	public function c2c(){

		return $this->fetch();

	}
	/**
	 * 互助记录
	 */
	public function huzhujilu(){

		return $this->fetch();

	}
	/**
	 * 互助系统
	 */
	public function huzhuxitong()
	{
		return $this->fetch();
	}
}
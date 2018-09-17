<?php
/**
 * @desc 公告文章模块前台
 * @author by tmf
 * @date 2018-09-04 21:10
 */
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;
class Account extends UserBase
{
	/**
	 * @desc 公告列表
	 */
	public function index()
	{
		// $lists = Db::table('hz_announce_cate')
  //               ->where('parent_id', 0)
  //               ->order('id desc')
  //               ->select();
                
  //       $data = array();
  //       if (is_array($lists) && count($lists) > 0) {
  //       	foreach ($lists as $key => $value) {
  //       		$sLists = db('announce')
  //       				->where('cate_id', $value['id'])
  //       				->select();
  //       		$data[$value['cate_name']] = $sLists;
  //       	}
  //       }

  //       if (is_array($data) && count($data) > 0) {
  //       	foreach ($data as $dKey => $dValue) {
  //       		if (count($dValue) <= 0) {
  //       			unset($data[$dKey]);
  //       		}
  //       	}
  //       }

		return $this->fetch();
	}

	/**
	 * 修改密码
	 */
	public function edit_paw()
	{
		$getParams = Request::instance()->param();
		// var_dump($getParams);
		return $this->fetch();		
	}
	public function edit_loginpwd()
	{
		// echo "11";
		if(request()->isPost()){
			$id=Session::get("user_id");
			$oldpwd=md5(Request::instance()->post("oldpwd"));
			$newpwd=md5(Request::instance()->post("newpwd"));
			$res=db("user")->where("id",$id)->field('user_pass')->find();
			if($res['user_pass'] != $oldpwd){
				return "旧密码输入不正确";
			}
			$res1=db("user")->where("id",$id)->update(['user_pass'=>$newpwd]);
			if($res1){
				return "ok";
			}else{
				return "修改失败";
			}
		}
	}
	/**
	 * 修改交易密码
	 */
	public function edit_jypwd()
	{
		if(request()->isPost()){
			$id=Session::get("user_id");
			$oldjyw=md5(Request::instance()->post("oldjyw"));
			$newjyw=md5(Request::instance()->post("newjyw"));
			$res=db("user")->where("id",$id)->field('transaction_password')->find();
			if($res['transaction_password'] != $oldpwd){
				return "旧密码输入不正确";
			}
			$res1=db("user")->where("id",$id)->update(['transaction_password'=>$newjyw]);
			if($res1){
				return "ok";
			}else{
				return "修改失败";
			}
		}
	}
	/**
	 * 收款信息
	 */
	public function receipt()
	{
		return $this->fetch();	
	}

	/**
	 * 未绑定钱包地址
	 */
	public function unbind_purse_addr()
	{
		return $this->fetch();	
	}

	/**
	 * 已绑定钱包地址
	 */
	public function bind_purse_addr()
	{
		return $this->fetch();	
	}

	/**
	 * 未绑定实名认证
	 */
	public function unbind_certificate()
	{
		return $this->fetch();	
	}

	/**
	 * 已绑定实名认证
	 */
	public function bind_certificate()
	{
		$id=Session::get("uid");
		$list=db("user_addition")->where("user_id",$id)->find();
		return $this->fetch("",['list'=>$list]);	
	}
}

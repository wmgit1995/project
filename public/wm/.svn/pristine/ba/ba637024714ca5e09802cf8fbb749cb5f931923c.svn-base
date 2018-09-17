<?php
/**
 * @desc 公告文章模块前台
 * @author by tmf
 * @date 2018-09-01 14:30
 */
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;
class Consult extends Controller
{
	/**
	 * @desc 咨询管理首页
	 */
	public function index()
	{

		return $this->fetch();
	}

	/**
	 * @desc 公告管理
	 */
	public function announce()
	{
		return $this->fetch();
	}

	/**
	 * @desc 关于我们
	 */
	public function about_us()
	{
		return $this->fetch();
	}

	/**
	 * @desc 联系我们
	 */
	public function contact_us()
	{
		return $this->fetch();
	}

	/**
	 * @desc 咨询留言
	 */
	public function consult_message()
	{
		if(request()->isPost()){
			$data = request::instance()->param();
			$user_id = Session::get("user_id");
            $insertdata['user_id'] = $user_id;
            $insertdata['title'] = $data['title'];
            $insertdata['content'] = $data['content'];
            $insertdata['add_time'] = time();

			if(empty($data['title']) || empty($data['content'])){
				return "请完整填写内容";
			}
			$res1 = db('msg')->insert($insertdata);
			if($res1){
				return "ok";
			}else{
				return "服务器繁忙，请稍后再试";
			}
		}
		return $this->fetch();
	}

	/**
	 * @desc 咨询记录
	 */
	public function consult_record()
	{
		$lists = Db::table('hz_msg')
                ->order('id desc')
                ->select();
		return $this->fetch('', array('list' => $lists));
	}

	/**
	 * @desc 咨询记录
	 */
	public function consult_record_detail()
	{
		$getParams = Request::instance()->param();
		$info = db('msg')->where('id', $getParams['id'])->find();
		return $this->fetch('', array('info' => $info));
	}

	/**
	 * @desc 客服咨询
	 */
	public function custom_consult()
	{
		return $this->fetch();
	}
}

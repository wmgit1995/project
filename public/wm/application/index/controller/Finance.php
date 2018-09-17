<?php
/**
 * @desc 公告文章模块前台
 * @author by tmf
 * @date 2018-09-04 19:56
 */
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
class Finance extends Controller
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
	 * 购买有机果
	 */
	public function buy_fruit()
	{
		return $this->fetch();	
	}

	/**
	 * 转送信用种子
	 */
	public function transit_credit_seed()
	{
		return $this->fetch();		
	}

	/**
	 * 转票
	 */
	public function transit_ticket()
	{
		return $this->fetch();	
	}

	/**
	 * 转送记录
	 */
	public function transit_record()
	{
		return $this->fetch();	
	}

	/**
	 * 收益记录
	 */
	public function income()
	{
		return $this->fetch();	
	}
}

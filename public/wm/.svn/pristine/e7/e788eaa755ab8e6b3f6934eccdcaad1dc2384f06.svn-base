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
class Announce extends Controller
{
	/**
	 * @desc 公告列表
	 */
	public function index()
	{
		$lists = Db::table('hz_announce_cate')
                ->where('parent_id', 0)
                ->order('id desc')
                ->select();
                
        $data = array();
        if (is_array($lists) && count($lists) > 0) {
        	foreach ($lists as $key => $value) {
        		$sLists = db('announce')
        				->where('cate_id', $value['id'])
        				->select();
        		$data[$value['cate_name']] = $sLists;
        	}
        }

        if (is_array($data) && count($data) > 0) {
        	foreach ($data as $dKey => $dValue) {
        		if (count($dValue) <= 0) {
        			unset($data[$dKey]);
        		}
        	}
        }

		return $this->fetch('',['lists'=>$lists,'data'=>$data]);
	}

	/**
	 * @desc 公告详情
	 */
	public function detail()
	{
		$params = Request::instance()->param();
		$id = isset($params['id']) ? intval($params['id']) : 0;
		
		$announceInfo = db('announce')->where('id', $id)->find();
		return $this->fetch('',['anInfo'=>$announceInfo]);
	}
}

<?php
namespace app\admins\controller;
use think\Config;
use think\Controller;
use think\Request;
use think\Db;
use think\Backup;
use think\response\Json;
use think\View;
use app\index\controller\Plan;
class setting extends Base
{

    /**
     * 匹配设置
     */
    public function match()
    {
        if (request()->isPost()) {
            $data = Request::instance()->param();
            unset($data['/admins/setting/match_html']);
            $save['option_name'] = "match_setting";
            $save['option_value'] = json_encode($data);
            if ($data['id']) {
                $save['id'] = $data['id'];
                $res = Db::table("hz_option")->update($save);
            } else {
                $res = Db::table("hz_option")->insert($save);
            }
            if ($res !== false) {
                return 'ok';
            } else {
                return '01';
            }
        } else {
            $info = Db::table("hz_option")->where("option_name", "match_setting")->find();
            $setting = json_decode($info['option_value'], true);
            $setting['id'] = $info['id'];
            $register = ['开放', '关闭注册', '仅邀请'];
            return $this->fetch('', ['setting' => $setting, 'register' => $register]);
        }
    }

    /**
     * 数据库备份管理
     */
    public function backuplist()
    {
        @ini_set("memory_limit", "1024M");
        @ini_set("max_execution_time", "1000");
        $db = new Backup();
        $list = $db->fileList();
        return $this->fetch('', ['list' => $list]);
    }

    /**
     * 删除数据库备份
     */
    public function backup_del()
    {
        $id = Request::instance()->get('id');
        if ($id) {
            @ini_set("memory_limit", "1024M");
            @ini_set("max_execution_time", "1000");
            $db = new Backup();
            $res = $db->delFile($id);
            if ($res == $id) {
                $data = ['status' => 1, 'msg' => ''];
            } else {
                $data = ['status' => 2, 'msg' => '删除失败'];
            }
        } else {
            $data = ['status' => 0, 'msg' => '系统错误,刷新后再试'];
        }
        return json($data);
    }

    /**
     * 新增数据库备份
     */
    public function backup_add()
    {
        $plan = new Plan();
        $event = $plan->exportDatabase();
        if ($event == 'ok') {
            return "ok";
        } else {
            return "01";
        }
    }

    /**
     * 数据库备份下载
     */
    public function backup_down()
    {
        $id = Request::instance()->param('id');
        if ($id) {
            @ini_set("memory_limit", "1024M");
            @ini_set("max_execution_time", "1000");
            $db = new Backup();
            $db->downloadFile($id);
        } else {
            echo '非法下载!';
        }
    }

    /**
     * 轮播图管理
     */
    public function banner()
    {
        $lists = Db::table("hz_banner")->order("status asc,sort asc")->paginate(10);
        return $this->fetch('', ['list' => $lists]);
    }

    /**
     * 上传轮播图
     */
    public function banner_save()
    {
        if (Request::instance()->isPost()) {
            $insdata['id'] = Request::instance()->param("id");
            $insdata['imgurl'] = Request::instance()->param("imgurl");
            $insdata['link'] = Request::instance()->param("link");
            $insdata['status'] = Request::instance()->param("status");
            $insdata['sort'] = Request::instance()->param("sort");
            if($insdata['id']){
                $res = Db::table("hz_banner")->update($insdata);
            }else{
                $res = Db::table("hz_banner")->insert($insdata);
            }
            if($res!==false){
                return 'ok';
            }else{
                return '01';
            }
        } else {
            $id = Request::instance()->param("id");
            $info = [];
            if ($id) {
                $info = Db::table("hz_banner")->where("id", $id)->find();
            }else{
                $info =[];
            }
            return $this->fetch('', ['info' => $info]);
        }

    }

    /**
     * 删除轮播图
     */
    public function banner_del()
    {
        $id = Request::instance()->get('id');
        $info = Db::table('hz_banner')->where('id',$id)->find();
        $res = Db::table('hz_banner')->where('id',$id)->delete();
        if($info){
            $url = $info['imgurl'];
            if(is_file('.'.$url)){
                unlink('.'.$url);
            }
            $data = ['status'=>1,'msg'=>''];
        }else{
            $data = ['status'=>0,'msg'=>'删除失败,请稍后再试'];
        }

        return json($data);
    }

    /**
     * 图片上传
     */

    public function upload()
    {
        $file = request()->file('thumb');
        $res = ['url'=>'','msg'=>''];
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $res['url'] = '/uploads/'.$info->getSaveName();
                $res['msg'] = 'ok';
            }else{
                $res['msg'] =  $file->getError();
            }
        }
        return json($res);
    }
}
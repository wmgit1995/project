<?php
/**
 * @title 公告后台管理模块功能
 * @desc 该功能模块包括公告分类和公告增删改查功能
 * @author by tangmeifang
 * @date 2018-08-30 14:00
 */
namespace app\admins\controller;
use think\Config;
use think\Controller;
use think\Request;
use think\Db;
use think\View;

class Announce extends Base
{
    /*
     * @desc 公告列表
     */
    public function index()
    {
        $title = Request::instance()->get('title');
        $stitle = "%".$title."%";
        $lists = Db::table('hz_announce')->alias('a')
                    ->join("hz_announce_cate b","a.cate_id = b.id","left")
                    ->field("a.*,b.cate_name")
                    ->where("a.title","like",$stitle)
                    ->order('id desc')->paginate(10);
        return $this->fetch('',['lists'=>$lists,'title'=>$title]);
    }

    /*
     * @desc 公告列表
     */
    public function cate_index()
    {
        $title = Request::instance()->get('title');
        $stitle = "%".$title."%";
        $lists = Db::table('hz_announce_cate')
                ->where('cate_name','like',$stitle)
                ->where('parent_id', 0)
                ->order('id desc')
                ->paginate(10);
        // var_dump(Db::table('hz_announce_cate')->getlastsql());
        return $this->fetch('',['lists'=>$lists,'title'=>$title]);
    }

    /*
     * @desc 公告列表
     */
    public function cate_sub_index()
    {
        $requestParams = Request::instance()->param();
        $parent_id = isset($requestParams['parent_id']) ? $requestParams['parent_id'] : 0;

        $title = isset($requestParams['title']) ? $requestParams['title'] : '';
        $stitle = "%".$title."%";
        $lists = Db::table('hz_announce_cate')
                ->where('cate_name','like',$stitle)
                ->where('parent_id', $parent_id)
                ->order('id desc')
                ->paginate(10);

        return $this->fetch('',['lists'=>$lists,'title'=>$title,'parent_id'=>$parent_id]);
    }

    /*
     * @desc 添加|编辑公告文章
     */
    public function add()
    {
        $getParams = Request::instance()->param();
        $anId = count($getParams) > 0 ? $getParams['id'] : 0;
        $cResult = Db::table('hz_announce')->where('id', $anId)->find();
        $anInfo = is_array($cResult) ? $cResult : array();

        // $status_option = isset($anInfo['cate_id']) ? $this->getCateListTree($anInfo['cate_id']) : '';
        $status_option = isset($anInfo['cate_id']) ? 
                        $this->getCateListTree($anInfo['cate_id']) : 
                        $this->getCateListTree();

        if( request()->isPost() ){
            // 验证参数
            $data = request::instance()->param();
            $insertdata['cate_id'] = $data['cate_id'];
            $insertdata['user_id'] = 1; // ？？？？？？？？？？？？？？？
            $insertdata['title'] = $data['title'];
            $insertdata['content'] = $data['content'];
            $insertdata['status'] = $data['status'];

            // 验证分类名称不能为空
            if (empty($insertdata['title'])) {
                $this->error('公告标题不能为空', 'add', array('$insertdata' => $data));
            }

            // 验证不能添加相同文章标题
            $result = Db::table('hz_announce')->where('title', $data['title'])->find();
            if ( is_array($result) && $anId <= 0 ) {
                $this->error('不可添加相同文章标题', 'add', array('$insertdata' => $data));
            }

            // 验证启用禁用状态
            if ($data['status'] != 1 && $data['status'] != 0) {
                $this->error('状态填写错误', 'add', array('$insertdata' => $data));
            }

            $res = !isset($data['id']) ? 
                    db('announce')->insert($insertdata) : 
                    db('announce')->where('id', $data['id'])->update($insertdata);
            if($res) {
                $title = Request::instance()->get('title');
                $stitle = "%".$title."%";
                $lists = Db::table('hz_announce')->alias('a')
                            ->join("hz_announce_cate b","a.cate_id = b.id","left")
                            ->field("a.*,b.cate_name")
                            ->where("a.title","like",$stitle)
                            ->order('id desc')->paginate(10);
                return $this->fetch('index',['lists'=>$lists,'title'=>$title]);
            }
        }
            
        return $this->fetch('',['status_option'=>$status_option, 'an_info'=>$anInfo]);
    }

    public function uEditorImgUpload()
    {
        $action = $this->request->param('action');
        $result = '';
        switch($action){
            case 'config':
                $result = file_get_contents(ROOT_PATH.'public/static/admins/Ueditor/php/config.json');
                break;
            case 'uploadimage':
                $file = $this->request->file('upfile');
                if($file){
                    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/announce');
                    $res = $info->getInfo();
                    $res['state'] = 'SUCCESS';
                    $res['url'] = $info->getSaveName();
                    $result = json_encode($res);
                }
                break;
            default:
                break;
        }
        return $result;
    }

    /*
     * @desc 添加|编辑公告分类
     */
    public function cate_add(){
        $getParams = Request::instance()->param();
       
        $cateParentId = isset($getParams['parent_id']) ? $getParams['parent_id'] : 0;
        $cateId = isset($getParams['id']) ? $getParams['id'] : 0;
        $cResult = Db::table('hz_announce_cate')->where('id', $cateId)->find();
        $cateInfo = is_array($cResult) ? $cResult : array();

        // 添加下级分类
        $id = isset($getParams['is_sub']) ? $getParams['id'] : $cateParentId;
        $cateParentInfo = Db::table('hz_announce_cate')->where('id', $id)->find();

        // 下级分类编辑
        $cateSubInfo = Db::table('hz_announce_cate')->where('id', $cateParentInfo['parent_id'])->find();

        $cate_parent_status_option = isset($getParams['is_sub']) ?
                                    array("{$cateSubInfo['id']}" => $cateSubInfo['cate_name']) : 
                                    array("{$cateParentInfo['id']}" => $cateParentInfo['cate_name']);

        $status_option = $cateId > 0 && !$getParams['is_sub'] ? $this->getCateList() : $cate_parent_status_option;
        
        if( request()->isPost() ){
            // 验证参数
            $data = request::instance()->param();
            $insertdata['parent_id'] = $data['cate_id'];
            $insertdata['user_id'] = 1; // ？？？？？？？？？？？？？？？
            $insertdata['cate_name'] = $data['cate_name'];
            $insertdata['cate_desc'] = $data['cate_desc'];
            $insertdata['status'] = $data['status'];

            $cateId = $data['cateId'];
            $cResult = Db::table('hz_announce_cate')->where('id', $cateId)->find();
            $cateInfo = is_array($cResult) ? $cResult : array();

            // 验证分类名称不能为空
            if (empty($insertdata['cate_name']) && $cateId <= 0) {
                $this->error('分类名称不能为空', 'cate_add', array('$insertdata' => $data));
            }

            // 验证启用禁用状态
            if ($data['status'] != 1 && $data['status'] != 0) {
                $this->error('状态填写错误', 'cate_add', array('$insertdata' => $data));
            }

            // 验证不能添加相同分类名称
            $result = Db::table('hz_announce_cate')->where('cate_name', $data['cate_name'])->find();
            if ( is_array($result) && $cateId <= 0 ) {
                $this->error('不可添加相同分类名称', 'cate_add');
            }

            $res = !isset($data['id']) ? 
                    db('announce_cate')->insert($insertdata) : 
                    db('announce_cate')->where('id', $data['id'])->update($insertdata);
            if($res) {
                $title = Request::instance()->get('title');
                $stitle = "%".$title."%";
                $lists = Db::table('hz_announce_cate')
                        ->where('cate_name','like',$stitle)
                        ->where('parent_id', 0)
                        ->order('id desc')
                        ->paginate(10);
                return $this->fetch('cate_index',['lists'=>$lists,'title'=>$title,'status_option'=>$status_option, 'cate_info'=>$cateInfo]);
            }
        }

        return $this->fetch('',['status_option'=>$status_option, 'cate_info'=>$cateInfo, 'cateId' => $cateId]);
    }

    /*
     * 启用/禁用公告
     */
    public function anIsForbidden()
    {
        $getParams = Request::instance()->param();
        $anId = count($getParams) > 0 ? $getParams['id'] : 0;

        $cResult = Db::table('hz_announce')->where('id', $anId)->find();
        $anInfo = is_array($cResult) ? $cResult : array();

        if ($anId <= 0 || count($anInfo) <= 0) {
            $this->error('操作失败');
        }

        $updateStatus = $anInfo['status'] > 0 ? 0 : 1;
        $result = db('announce')->where('id', $anId)->update(['status' => $updateStatus]);
        if (!$result) {
            $this->error('服务器繁忙，请稍后再试');
        } else {
            $this->success('操作成功', 'index');
        }
    }

    /*
     * 启用/禁用公告分类
     */
    public function cateIsForbidden()
    {
        $getParams = Request::instance()->param();
        $cateId = count($getParams) > 0 ? $getParams['id'] : 0;

        $cResult = Db::table('hz_announce')->where('id', $cateId)->select();
        $anInfo = is_array($cResult) ? $cResult : array();
        if ($anId <= 0 || count($anInfo) <= 0) {
            $this->error('操作失败');
        }

        $updateStatus = $aninfo['status'] > 0 ? 0 : 1;
        $result = db('announce')->where('id', $anId)->update(['status' => $updateStatus]);
        if (!$result) {
            $this->error('服务器繁忙，请稍后再试');
        } else {
            $this->success('操作成功', 'index');
        }
    }

    /*
     * @desc 删除公告
     */
    public function anIsDelete()
    {
        $getParams = Request::instance()->param();
        $anId = count($getParams) > 0 ? $getParams['id'] : 0;
        $res = db('announce')->where('id', $anId)->delete();
        if ($anId <= 0  || !$res) {
            $this->error('删除操作失败');
        } else {
            $this->success('删除操作成功', 'index');
        }
    }
    
    /*
     * @desc 删除公告分类
     */
    public function cateIsDelete()
    {
        $getParams = Request::instance()->param();
        $cateId = count($getParams) > 0 ? $getParams['id'] : 0;
        $res = db('announce_cate')->where('id', $cateId)->delete();
        $viewIndex = isset($getParams['is_sub']) && $getParams['is_sub'] ? 'cate_sub_index' : 'cate_index';
        if ($cateId <= 0 || !$res) {
            $this->error('删除操作失败');
        } else {
            $this->success('删除操作成功', $viewIndex);
        }
    }

    /*
     * @desc 获取公告分类普通列表
     */
    public function getCateList($parentId = 0)
    {
        $result = db('announce_cate') -> where('parent_id', $parentId) ->select();
        $status_option = ['顶级','新闻','体育','六合彩','公告'];
        
        $cateList = array();
        if (is_array($result)) {
            foreach ($result as $key=>$value){
                $cateList[$value['id']] = $value['cate_name'];
            }
        }

        array_unshift($cateList, '顶级');
        return $cateList;
    }

    /*
     * @desc 获取公告分类树形列表
     */
    public function getCateListTree($selectedId = 0)
    {
        $result = db('announce_cate') -> select();

        $tree_data=array();
        if (is_array($result)) {
            foreach ($result as $key=>$value){
                $tree_data[$value['id']]=array(
                    'id'=>$value['id'],
                    'parentid'=>$value['parent_id'],
                    'name'=>$value['cate_name']
                );
            }
        }

        $str = "<option value=\$id \$selected>\$spacer\$name</option>";
        $tree = new Tree();
        $tree->init($tree_data);
        $optionStr = "<div class='layui-form-mid layui-word-aux'><select name='cate_id' style='display:block;width:190px;' class='layui-select'>";
        $optionStr .= $tree->get_tree(0, $str, $selectedId);
        $optionStr .= "</select></div>";

        return $optionStr;
    }
}


/**
* 通用的树型类，可以生成任何树型结构
*/
class tree {
    /**
    * 生成树型结构所需要的2维数组
    * @var array
    */
    public $arr = array();
 
    /**
    * 生成树型结构所需修饰符号，可以换成图片
    * @var array
    */
    public $icon = array('│','├','└');
    public $nbsp = " ";
 
    /**
    * @access private
    */
    public $ret = '';
 
    /**
    * 构造函数，初始化类
    * @param array 2维数组，例如：
    * array(
    *      1 => array('id'=>'1','parentid'=>0,'name'=>'一级栏目一'),
    *      2 => array('id'=>'2','parentid'=>0,'name'=>'一级栏目二'),
    *      3 => array('id'=>'3','parentid'=>1,'name'=>'二级栏目一'),
    *      4 => array('id'=>'4','parentid'=>1,'name'=>'二级栏目二'),
    *      5 => array('id'=>'5','parentid'=>2,'name'=>'二级栏目三'),
    *      6 => array('id'=>'6','parentid'=>3,'name'=>'三级栏目一'),
    *      7 => array('id'=>'7','parentid'=>3,'name'=>'三级栏目二')
    *      )
    */
    public function init($arr=array()){
       $this->arr = $arr;
       $this->ret = '';
       return is_array($arr);
    }
 
    /**
    * 得到父级数组
    * @param int
    * @return array
    */
    public function get_parent($myid){
        $newarr = array();
        if(!isset($this->arr[$myid])) return false;
        $pid = $this->arr[$myid]['parentid'];
        $pid = $this->arr[$pid]['parentid'];
        if(is_array($this->arr)){
            foreach($this->arr as $id => $a){
                if($a['parentid'] == $pid) $newarr[$id] = $a;
            }
        }
        return $newarr;
    }
 
    /**
    * 得到子级数组
    * @param int
    * @return array
    */
    public function get_child($myid){
        $a = $newarr = array();
        if(is_array($this->arr)){
            foreach($this->arr as $id => $a){
                if($a['parentid'] == $myid) $newarr[$id] = $a;
            }
        }
        return $newarr ? $newarr : false;
    }
 
    /**
    * 得到当前位置数组
    * @param int
    * @return array
    */
    public function get_pos($myid,&$newarr){
        $a = array();
        if(!isset($this->arr[$myid])) return false;
        $newarr[] = $this->arr[$myid];
        $pid = $this->arr[$myid]['parentid'];
        if(isset($this->arr[$pid])){
            $this->get_pos($pid,$newarr);
        }
        if(is_array($newarr)){
            krsort($newarr);
            foreach($newarr as $v){
                $a[$v['id']] = $v;
            }
        }
        return $a;
    }
 
    /**
    * 得到树型结构
    * @param int ID，表示获得这个ID下的所有子级
    * @param string 生成树型结构的基本代码，例如："<option value=\$id \$selected>\$spacer\$name</option>"
    * @param int 被选中的ID，比如在做树型下拉框的时候需要用到
    * @return string
    */
    public function get_tree($myid, $str, $sid = 0, $adds = '', $str_group = ''){
        $number=1;
        $child = $this->get_child($myid);
        if(is_array($child)){
            $total = count($child);
            foreach($child as $id=>$value){
                $j=$k='';
                if($number==$total){
                    $j .= $this->icon[2];
                }else{
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $spacer = $adds ? $adds.$j : '';
                $selected = $id==$sid ? 'selected' : '';

                @extract($value);
                $parentid == 0 && $str_group ? eval("\$nstr = \"$str_group\";") : eval("\$nstr = \"$str\";");
                $this->ret .= $nstr;
                $nbsp = $this->nbsp;
                $this->get_tree($id, $str, $sid, $adds.$k.$nbsp,$str_group);
                $number++;
            }
        }
        return $this->ret;
    }
    /**
    * 同上一方法类似,但允许多选
    */
    public function get_tree_multi($myid, $str, $sid = 0, $adds = ''){
        $number=1;
        $child = $this->get_child($myid);
        if(is_array($child)){
            $total = count($child);
            foreach($child as $id=>$a){
                $j=$k='';
                if($number==$total){
                    $j .= $this->icon[2];
                }else{
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $spacer = $adds ? $adds.$j : '';
                
                $selected = $this->have($sid,$id) ? 'selected' : '';
                @extract($a);
                eval("\$nstr = \"$str\";");
                $this->ret .= $nstr;
                $this->get_tree_multi($id, $str, $sid, $adds.$k.' ');
                $number++;
            }
        }
        return $this->ret;
    }
     /**
    * @param integer $myid 要查询的ID
    * @param string $str   第一种HTML代码方式
    * @param string $str2  第二种HTML代码方式
    * @param integer $sid  默认选中
    * @param integer $adds 前缀
    */
    public function get_tree_category($myid, $str, $str2, $sid = 0, $adds = ''){
        $number=1;
        $child = $this->get_child($myid);
        if(is_array($child)){
            $total = count($child);
            foreach($child as $id=>$a){
                $j=$k='';
                if($number==$total){
                    $j .= $this->icon[2];
                }else{
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $spacer = $adds ? $adds.$j : '';
                
                $selected = $this->have($sid,$id) ? 'selected' : '';
                @extract($a);
                if (empty($html_disabled)) {
                    eval("\$nstr = \"$str\";");
                } else {
                    eval("\$nstr = \"$str2\";");
                }
                $this->ret .= $nstr;
                $this->get_tree_category($id, $str, $str2, $sid, $adds.$k.' ');
                $number++;
            }
        }
        return $this->ret;
    }
    
    /**
     * 同上一类方法，jquery treeview 风格，可伸缩样式（需要treeview插件支持）
     * @param $myid 表示获得这个ID下的所有子级
     * @param $effected_id 需要生成treeview目录数的id
     * @param $str 末级样式
     * @param $str2 目录级别样式
     * @param $showlevel 直接显示层级数，其余为异步显示，0为全部限制
     * @param $style 目录样式 默认 filetree 可增加其他样式如'filetree treeview-famfamfam'
     * @param $currentlevel 计算当前层级，递归使用 适用改函数时不需要用该参数
     * @param $recursion 递归使用 外部调用时为FALSE
     */
    function get_treeview($myid,$effected_id='example',$str="<span class='file'>\$name</span>", $str2="<span class='folder'>\$name</span>" ,$showlevel = 0 ,$style='filetree ' , $currentlevel = 1,$recursion=FALSE) {
        $child = $this->get_child($myid);
        if(!defined('EFFECTED_INIT')){
           $effected = ' id="'.$effected_id.'"';
           define('EFFECTED_INIT', 1);
        } else {
           $effected = '';
        }
        $placeholder =     '<ul><li><span class="placeholder"></span></li></ul>';
        if(!$recursion) $this->str .='<ul'.$effected.'  class="'.$style.'">';
        foreach($child as $id=>$a) {
 
            @extract($a);
            if($showlevel > 0 && $showlevel == $currentlevel && $this->get_child($id)) $folder = 'hasChildren'; //如设置显示层级模式@2011.07.01
            $floder_status = isset($folder) ? ' class="'.$folder.'"' : '';        
            $this->str .= $recursion ? '<ul><li'.$floder_status.' id=\''.$id.'\'>' : '<li'.$floder_status.' id=\''.$id.'\'>';
            $recursion = FALSE;
            if($this->get_child($id)){
                eval("\$nstr = \"$str2\";");
                $this->str .= $nstr;
                if($showlevel == 0 || ($showlevel > 0 && $showlevel > $currentlevel)) {
                    $this->get_treeview($id, $effected_id, $str, $str2, $showlevel, $style, $currentlevel+1, TRUE);
                } elseif($showlevel > 0 && $showlevel == $currentlevel) {
                    $this->str .= $placeholder;
                }
            } else {
                eval("\$nstr = \"$str\";");
                $this->str .= $nstr;
            }
            $this->str .=$recursion ? '</li></ul>': '</li>';
        }
        if(!$recursion)  $this->str .='</ul>';
        return $this->str;
    }
    
    /**
     * 获取子栏目json
     * Enter description here ...
     * @param unknown_type $myid
     */
    public function creat_sub_json($myid, $str='') {
        $sub_cats = $this->get_child($myid);
        $n = 0;
        if(is_array($sub_cats)) foreach($sub_cats as $c) {            
            $data[$n]['id'] = iconv(CHARSET,'utf-8',$c['catid']);
            if($this->get_child($c['catid'])) {
                $data[$n]['liclass'] = 'hasChildren';
                $data[$n]['children'] = array(array('text'=>' ','classes'=>'placeholder'));
                $data[$n]['classes'] = 'folder';
                $data[$n]['text'] = iconv(CHARSET,'utf-8',$c['catname']);
            } else {                
                if($str) {
                    @extract(array_iconv($c,CHARSET,'utf-8'));
                    eval("\$data[$n]['text'] = \"$str\";");
                } else {
                    $data[$n]['text'] = iconv(CHARSET,'utf-8',$c['catname']);
                }
            }
            $n++;
        }
        return json_encode($data);        
    }
    private function have($list,$item){
        return(strpos(',,'.$list.',',','.$item.','));
    }
}
?>
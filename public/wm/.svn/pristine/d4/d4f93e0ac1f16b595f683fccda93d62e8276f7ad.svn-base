<?php
namespace app\admins\controller;
use think\Config;
use think\Controller;
use think\Request;
use think\Db;
use think\View;
use think\Validate;

class formation extends Base{

    /*
     *排单列表
     */
    public function index(){
        $title = Request::instance()->get('title');
        if($title){
            $stitle = "%".$title."%";
            $lists = Db::table('hz_formation')->alias('a')
                        ->join("hz_user b","a.user_id = b.id","left")
                        ->field("a.*,b.user_nickname,b.mobile")
                        ->where("a.user_id|b.user_nickname|b.mobile","like",$stitle)
                        ->where("b.user_status",1)
                        ->order('id desc')->paginate(10,false,['query'=>['title'=>$title]]);
        }else{
            $lists = Db::table('hz_formation')->alias('a')
                        ->join("hz_user b","a.user_id = b.id","left")
                        ->field("a.*,b.user_nickname,b.mobile")
                        ->where("b.user_status",1)
                        ->order('id desc')->paginate(10);
        }
        $status_option = ['排单中','匹配中','交易中','完成'];
        return $this->fetch('',['lists'=>$lists,'title'=>$title,'status_option'=>$status_option]);
    }

    /*
     * 排单-指定匹配
     */
    public function match_save(){
        if(request()->isPost()){
            $data = request::instance()->param();
            unset($data['/admins/formation/match_save_html']);
            $paylist = array_filter($data['name']);
            if($paylist){
                $ids = array_keys($paylist);
            }else{
                $msg = ['status'=>0,'msg'=>"数据错误"];
            }
            $listnum = count($paylist);
            Db::startTrans();
            $info = Db::table("hz_formation")->lock(true)->where("id",$data['id'])->find();
            if($info['status']>1){
                Db::rollback();
                $msg = ['status'=>1,'msg'=>"该排单不需要匹配"];
            }else{
                $adddata = [];
                if($info['status']==1){
                    //匹配中 查询指定的排单
                    $lists = Db::table('hz_formation')->lock(true)->alias('a')
                        ->join("hz_user b","a.user_id = b.id","left")
                        ->field("a.*,b.user_nickname,b.mobile")
                        ->where("b.user_status",1)
                        ->where("a.status",0)
                        ->where("a.id","in",$ids)
                        ->select();
                    foreach ($lists as $key=>$val){
                        $paynum = Db::table("hz_order")->lock(true)->where('from_form_id',$val['id'])->where("status",'lt',2)->value("sum(amount)");
                        $overnum = $val['amount'] - (int)$paynum;
                        if($overnum >= $paylist[$val['id']]){
                            $or_sn = date('YmdHis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
                            $arr = ['or_sn'=>$or_sn,
                                'from_user_id'=>$val['user_id'],
                                'from_form_id'=>$val['id'],
                                'to_user_id'=>$info['user_id'],
                                'to_form_id'=>$info['id'],
                                'amount'=>$paylist[$val['id']],
                                'add_time'=>time(),
                                'status'=>0,
                                'end_time'=>0,
                                'msg_status'=>0
                            ];
                            $adddata[] = $arr;
                            unset($arr);
                        }
                    }
                    if($adddata){
                        $res = Db::table("hz_order")->insertAll($adddata);
                        if($res){
                            Db::commit();
                            check_user_formation(['0'=>$data['id']]);
                            $msg = ['status'=>0,'msg'=>"该排单匹配{$listnum}个,成功{$res}个"];
                        }else{
                            Db::rollback();
                            $msg = ['status'=>1,'msg'=>"请稍候再试"];
                        }
                    }else{
                        Db::rollback();
                    }
                }elseif ($info['status']==0){
                    //排单中 显示指定出场
                    $lists = Db::table('hz_formation')->lock(true)->alias('a')
                        ->join("hz_user b","a.user_id = b.id","left")
                        ->field("a.*,b.user_nickname,b.mobile")
                        ->where("b.user_status",1)
                        ->where("a.status",1)
                        ->where("a.id","in",$ids)->select();
                    foreach ($lists as $key=>$val){
                        $paynum = Db::table("hz_order")->lock(true)->where('to_form_id',$val['id'])->where("status",'lt',2)->value("sum(amount)");
                        $sumnum = $val['amount']/100 * (100+$val['income_scale']*10);//floor((time()-$val['add_time'])/24/60/60));
                        $overnum = $sumnum - (int)$paynum;
                        if($overnum >= $paylist[$val['id']]){
                            $or_sn = date('YmdHis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
                            $arr = ['or_sn'=>$or_sn,
                                'from_user_id'=>$info['user_id'],
                                'from_form_id'=>$info['id'],
                                'to_user_id'=>$val['user_id'],
                                'to_form_id'=>$val['id'],
                                'amount'=>$paylist[$val['id']],
                                'add_time'=>time(),
                                'status'=>0,
                                'end_time'=>0,
                                'msg_status'=>0
                            ];
                            $adddata[] = $arr;
                            unset($arr);
                        }
                    }
                    if($adddata){
                        $res = Db::table("hz_order")->insertAll($adddata);
                        if($res){
                            Db::commit();
                            check_user_formation($ids);
                            $msg = ['status'=>0,'msg'=>"该排单匹配{$listnum}个,成功{$res}个"];
                        }else{
                            Db::rollback();
                            $msg = ['status'=>1,'msg'=>"请稍候再试"];
                        }
                    }else{
                        Db::rollback();
                    }
                }
            }
            return json($msg);
        }else{
            $id = Request::instance()->get('id');
            if($id){
                $info = Db::table('hz_formation')->where("id",$id)->find();
                $lists = [];
                if($info['status']>=1){
                    //预期总收益
                    $info['sumnum'] = $info['amount']/100 * (100+$info['income_scale']*10);//floor((time()-$info['add_time'])/24/60/60));
                    //已匹配
                    $info['paynum'] = Db::table("hz_order")->where('to_form_id',$id)->where("status",'lt',2)->value("sum(amount)");
                    //剩余
                    $info['overnum'] = $info['sumnum'] - $info['paynum'];
                }else{
                    //已匹配
                    $info['paynum'] = Db::table("hz_order")->where('from_form_id',$id)->where("status",'lt',2)->value("sum(amount)");
                    //剩余
                    $info['overnum'] = $info['amount'] - $info['paynum'];
                }
                $status_option = ['排单中','匹配中','交易中','完成'];
                if($info['status']==0){
                    //排单中 显示出场列表
                    $lists = Db::table('hz_formation')->alias('a')
                        ->join("hz_user b","a.user_id = b.id","left")
                        ->field("a.*,b.user_nickname,b.mobile")
                        ->where("b.user_status",1)
                        ->where("a.status",1)
                        ->order('a.id asc')->limit(10)->select();
                    foreach ($lists as $key=>$val){
                        $paynum = Db::table("hz_order")->where('to_form_id',$val['id'])->where("status",'lt',2)->value("sum(amount)");
                        $val['sumnum'] = $val['amount']/100 * (100+$val['income_scale']*10);//floor((time()-$val['add_time'])/24/60/60));
                        $lists[$key]['paynum'] = (int)$paynum;
                        $lists[$key]['overnum'] = $val['sumnum'] - (int)$paynum;
                    }
                }elseif($info['status']==1){
                    //匹配中 显示排单中列表
                    $lists = Db::table('hz_formation')->alias('a')
                        ->join("hz_user b","a.user_id = b.id","left")
                        ->field("a.*,b.user_nickname,b.mobile")
                        ->where("b.user_status",1)
                        ->where("a.status",0)
                        ->order('a.id asc')->limit(10)->select();
                    foreach ($lists as $key=>$val){
                        $paynum = Db::table("hz_order")->where('from_form_id',$val['id'])->where("status",'lt',2)->value("sum(amount)");
                        $lists[$key]['paynum'] = (int)$paynum;
                        $lists[$key]['overnum'] = $val['amount'] - (int)$paynum;
                    }
                }
                    //显示订单信息
                    //去哪了
                    $order['to'] = Db::table('hz_order')->alias("a")
                                            ->join("hz_user b","a.to_user_id = b.id","left")
                                            ->field("a.*,b.user_nickname")
                                            ->where("a.from_form_id",$id)->select();
                    //哪来的
                    $order['from'] = Db::table('hz_order')->alias("a")
                                            ->join("hz_user b","a.from_user_id = b.id","left")
                                            ->field("a.*,b.user_nickname")
                                            ->where("a.to_form_id",$id)->select();
                $ostatus_option = ['交易中','完成','取消'];
                $msg_status_option = ['未通知','通知付款','通知到账'];
                return $this->fetch('',['info'=>$info,'lists'=>$lists,'order'=>$order,'status_option'=>$status_option,'ostatus_option'=>$ostatus_option,'msg_status_option'=>$msg_status_option]);
            }else{
                return $this->redirect('index');
            }
        }
    }

    /*
     * 订单列表
     */
    public function order(){
        $title = Request::instance()->get("title");
        if($title){
            $stitle = "%".$title."%";
            $lists = Db::table('hz_order')->alias("a")
                ->join("hz_user b","a.from_user_id = b.id","left")
                ->join("hz_user c","a.to_user_id = c.id","left")
                ->field("a.*,b.user_nickname as user1,c.user_nickname as user2")
                ->where("a.or_sn|b.user_nickname|c.user_nickname","like",$stitle)
                ->order('id desc')->paginate(10,false,['query'=>['title'=>$title]]);
        }else{
            $lists = Db::table('hz_order')->alias("a")
                            ->join("hz_user b","a.from_user_id = b.id","left")
                            ->join("hz_user c","a.to_user_id = c.id","left")
                            ->field("a.*,b.user_nickname as user1,c.user_nickname as user2")
                            ->order('id desc')->paginate(10);
        }
        $status_option = ['交易中','完成','取消'];
        $msg_status_option = ['未通知','通知付款','通知到账'];
        return $this->fetch('',['lists'=>$lists,'title'=>$title,'status_option'=>$status_option,'msg_status_option'=>$msg_status_option]);
    }

    /*
     * 押金退出申请
     */
    public function apply(){
        $title = Request::instance()->get('title');
        if($title){
            $stitle = "%".$title."%";
            $lists = Db::table('hz_apply')->alias('a')
                ->join("hz_user b","a.user_id = b.id","left")
                ->field("a.*,b.user_nickname,b.mobile")
                ->where("a.user_id|b.user_nickname|b.mobile","like",$stitle)
                ->order('status asc,id desc')->paginate(10,false,['query'=>['title'=>$title]]);
        }else{
            $lists = Db::table('hz_apply')->alias('a')
                ->join("hz_user b","a.user_id = b.id","left")
                ->field("a.*,b.user_nickname,b.mobile")
                ->order('status asc,id desc')->paginate(10);
        }
        $status_option = ['审核中','审核通过','拒绝申请'];
        return $this->fetch('',['lists'=>$lists,'title'=>$title,'status_option'=>$status_option]);
    }
    /*
     * 申请操作
     */
    public function apply_change(){
        $data = Request::instance()->param();
        if($data['id']){
         $info = Db::table("hz_apply")->where("id",$data['id'])->where("status",0)->find();
         if($info){
             $res = Db::table("hz_apply")->update(["id"=>$data['id'],"status"=>$data['type'],"apply_time"=>time()]);
             if($res){
                 $this->success("修改成功",'apply');
             }else{
                 $this->error("修改失败","apply");
             }
         }else{
             $this->error("修改失败","apply");
         }
        }else{
            $this->error("系统错误","apply");
        }
    }
    /*
     * 门票赠送记录
     */
    public function ticket(){
        $title = Request::instance()->get('title');
        if($title){
            $stitle = '%'.$title.'%';
            $lists = Db::table("hz_amount_log")->alias("a")
                            ->join("hz_user b","a.user_id = b.id",'left')
                            ->where("a.from&a.type",4)
                            ->where("b.user_nickname",'like',$stitle)
                            ->field("a.*,b.user_nickname")->order("a.id desc")->paginate(10,false,['query'=>['title'=>$title]]);
        }else{
            $lists = Db::table("hz_amount_log")->alias("a")
                            ->join("hz_user b","a.user_id = b.id",'left')
                            ->where("a.from&a.type",4)
                            ->field("a.*,b.user_nickname")->order("a.id desc")->paginate(10);
        }
        return $this->fetch('',['lists'=>$lists,'title'=>$title]);
    }
    /*
     * 赠送门票
     */
    public function ticket_add(){
        if(request()->isPost()){
            $data = request::instance()->param();
            if($data['name']){
                Db::startTrans();
                $info = Db::table("hz_user")->lock(true)->where("user_nickname",$data['name'])->find();
                if($info){
                    $arr['user_id'] = $info['id'];
                    $arr['type'] = 4;
                    $arr['num'] = abs($data['num']);
                    $arr['from'] = 4;
                    $arr['add_time'] = time();
                    if($data['num']>0){
                        $arr['vector'] = 1;
                        $arr['nownum'] = $info['ticket'] + $data['num'];
                    }else{
                        $arr['vector'] = 2;
                        $arr['nownum'] = $info['ticket'] - abs($data['num']);
                    }
                    $res = Db::table("hz_amount_log")->insert($arr);
                    if($res){
                        $ures = Db::table("hz_user")->where('id',$info['id'])->update(["ticket"=>$arr['nownum']]);
                        if($ures){
                            Db::commit();
                            return 'ok';
                        }else{
                            Db::rollback();
                            return '02';
                        }
                    }else{
                        Db::rollback();
                        return '02';
                    }
                }else{
                    Db::rollback();
                    return '01';
                }
            }
        }else{
            return $this->fetch();
        }
    }

    /*
     * 信用种子赠送记录
     */
    public function credit_seed(){
        $title = Request::instance()->get('title');
        if($title){
            $stitle = '%'.$title.'%';
            $lists = Db::table("hz_amount_log")->alias("a")
                ->join("hz_user b","a.user_id = b.id",'left')
                ->where("a.from",4)->where("a.type",3)
                ->where("b.user_nickname",'like',$stitle)
                ->field("a.*,b.user_nickname")->order("a.id desc")->paginate(10,false,['query'=>['title'=>$title]]);
        }else{
            $lists = Db::table("hz_amount_log")->alias("a")
                ->join("hz_user b","a.user_id = b.id",'left')
                ->where("a.from",4)->where("a.type",3)
                ->field("a.*,b.user_nickname")->order("a.id desc")->paginate(10);
        }
        return $this->fetch('',['lists'=>$lists,'title'=>$title]);
    }

    /*
   * 赠送信用种子
   */
    public function credit_seed_add(){
        if(request()->isPost()){
            $data = request::instance()->param();
            if($data['name']){
                Db::startTrans();
                $info = Db::table("hz_user")->lock(true)->where("user_nickname",$data['name'])->find();
                if($info){
                    $arr['user_id'] = $info['id'];
                    $arr['type'] = 3;
                    $arr['num'] = abs($data['num']);
                    $arr['from'] = 4;
                    $arr['add_time'] = time();
                    if($data['num']>0){
                        $arr['vector'] = 1;
                        $arr['nownum'] = $info['credit_seed'] + $data['num'];
                    }else{
                        $arr['vector'] = 2;
                        $arr['nownum'] = $info['credit_seed'] - abs($data['num']);
                    }
                    $res = Db::table("hz_amount_log")->insert($arr);
                    if($res){
                        $ures = Db::table("hz_user")->where('id',$info['id'])->update(["credit_seed"=>$arr['nownum']]);
                        if($ures){
                            Db::commit();
                            return 'ok';
                        }else{
                            Db::rollback();
                            return '02';
                        }
                    }else{
                        Db::rollback();
                        return '02';
                    }
                }else{
                    Db::rollback();
                    return '01';
                }
            }
        }else{
            return $this->fetch();
        }
    }
}
<?php
//
//                            _ooOoo_
//                           o8888888o
//                           88" . "88
//                           (| -_- |)
//                            O\ = /O
//                        ____/`---'\____
//                      .   ' \\| |// `.
//                       / \\||| : |||// \
//                     / _||||| -:- |||||- \
//                       | | \\\ - /// | |
//                     | \_| ''\---/'' | |
//                      \ .-\__ `-` ___/-. /
//                   ___`. .' /--.--\ `. . __
//                ."" '< `.___\_<|>_/___.' >'"".
//               | | : `- `.;`\ _ /`;.`/ - ` : | |
//                 \ \ `-. \_ __\ /__ _/ .-` / /
//         ======`-.____`-.___\_____/___.-`____.-'======
//                            `=---='
//
//         .............................................
//                  佛祖保佑             永无BUG
//
//          佛曰:
//
//                  写字楼里写字间，写字间里程序员；
//
//                  程序人员写程序，又拿程序换酒钱。
//
//                  酒醒只在网上坐，酒醉还来网下眠；
//
//                  酒醉酒醒日复日，网上网下年复年。
//
//                  但愿老死电脑间，不愿鞠躬老板前；
//
//                  奔驰宝马贵者趣，公交自行程序员。
//
//                  别人笑我忒疯癫，我笑自己命太贱；
//
//                  不见满街漂亮妹，哪个归得程序员？
//
namespace app\index\controller;
use app\admin\validate\Config;
use think\Db;
use think\Backup;

class plan {
    /**
     * 随机匹配
     */
    public function match(){
        @ini_set("max_execution_time","1000");
        Db::startTrans();
        //出队列
        $out_lists = [];
        $listo = Db::table("hz_formation")->alias("a")->lock(true)
            ->join("hz_user b","a.user_id = b.id","left")
            ->field("a.*")->where("a.status",1)
            ->where("b.user_status",1)->order("a.id asc")->select();
        foreach ($listo as  $val){
            $paynum = Db::table("hz_order")->where('to_form_id',$val['id'])->where("status",'lt',2)->value("sum(amount)");
            $sumnum = $val['amount']/100 * (100+$val['income_scale']*10);//floor((time()-$val['add_time'])/24/60/60));
            $val['overnum'] = $sumnum - (int)$paynum;
            if($val['overnum']>0){
                $out_lists[] =$val;
            }
        }
        //入队列
        $in_lists = [];
        $total_num = 0;
        $listi = Db::table("hz_formation")->alias("a")->lock(true)
            ->join("hz_user b","a.user_id = b.id","left")
            ->field("a.*")->where("a.status",0)
            ->where("b.user_status",1)->order("a.id asc")->select();
        foreach ($listi as $vo){
            $paynum = Db::table("hz_order")->where('from_form_id',$vo['id'])->where("status",'lt',2)->value("sum(amount)");
            $vo['overnum'] = $vo['amount'] - (int)$paynum;
            if($vo['overnum']>0){
                $total_num+=$vo['overnum'];
                $in_lists[] = $vo;
            }
        }
        if($out_lists){
            if($in_lists){
                $adddata = [];
                //开始匹配(填坑模式,先来后到)
                foreach ($out_lists as $v){
                    //array_multisort(array_column($in_lists,'overnum','id'), SORT_DESC,$in_lists);//排序
                    if($v['overnum']>$total_num){
                        //无法匹配
                    }else{
                        foreach($in_lists as $k =>$vvv){
                            if($v['overnum']!=0){
                                if($v['overnum']>$vvv['overnum']){
                                    $or_sn = date('YmdHis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
                                    $arr = ['or_sn'=>$or_sn,
                                        'from_user_id'=>$vvv['user_id'],
                                        'from_form_id'=>$vvv['id'],
                                        'to_user_id'=>$v['user_id'],
                                        'to_form_id'=>$v['id'],
                                        'amount'=>$vvv['overnum'],
                                        'add_time'=>time(),
                                        'status'=>0,
                                        'end_time'=>0,
                                        'msg_status'=>0
                                    ];
                                    $adddata[] = $arr;
                                    $total_num-=$vvv['overnum'];
                                    $v['overnum'] -=$vvv['overnum'];
                                    unset($in_lists[$k]);
                                }else{
                                    $or_sn = date('YmdHis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
                                    $arr = ['or_sn'=>$or_sn,
                                        'from_user_id'=>$vvv['user_id'],
                                        'from_form_id'=>$vvv['id'],
                                        'to_user_id'=>$v['user_id'],
                                        'to_form_id'=>$v['id'],
                                        'amount'=>$v['overnum'],
                                        'add_time'=>time(),
                                        'status'=>0,
                                        'end_time'=>0,
                                        'msg_status'=>0
                                    ];
                                    $adddata[] = $arr;
                                    $total_num-=$vvv['overnum'];
                                    $v['overnum'] = 0;
                                }
                            }
                        }
                        $in_lists = array_values($in_lists);
                    }
                }
                if($adddata){
                    $res = Db::table("hz_order")->insertAll($adddata);
                    if($res){
                        Db::commit();
                        $ids = array_column($adddata,'to_form_id');
                        check_user_formation($ids);
                    }else{
                        Db::rollback();
                    }
                }
            }else{
                //无入队
                //预留后期救市计划
                Db::rollback();
            }
        }else{
            //无出队
            $order = Db::table("hz_order")->select();
            if(empty($order)){
                if($in_lists){
                    //注册虚拟用户
                   $virtual_id =  Db::table("hz_user")->lock(true)->where("user_login","woc666666")->value("id");
                   //不存在创建用户
                    if(!$virtual_id){
                        $insertdata['user_type']=1;
                        $insertdata['sex']=1;
                        $insertdata['user_status']=1;
                        $insertdata['user_login']="woc666666";
                        $insertdata['user_pass']=md5("woc888888");
                        $insertdata['user_nickname']="woc666666";
                        $insertdata['mobile']="12345678901";
                        $insertdata['ticket']=0;
                        $insertdata['woc_num']=0;
                        $insertdata['credit_deposit']=0;
                        $insertdata['credit_seed']=0;
                        $insertdata['level_id']=1;
                        $insertdata['parent']=0;
                        $insertdata['last_login_time']=time();
                        $insertdata['create_time']=time();
                        $virtual_id = Db::table("hz_user")->insertGetId($insertdata);
                    }
                    if($virtual_id){
                        //自动生成虚拟出单暂定金额20000
                        $virtual = ['user_id'=>$virtual_id,'amount'=>20000,'income_scale'=>0,
                            'cycle'=>10,'status'=>1,'add_time'=>time()];
                        $res = Db::table("hz_formation")->insert($virtual);
                        if($res){
                            //虚拟出单添加成功
                            Db::commit();
                        }else{
                            //虚拟出单添加失败
                            Db::rollback();
                        }
                    }else{
                        //虚拟用户添加失败
                        Db::rollback();
                    }
                }else{
                    //无订单无入队
                    Db::rollback();
                }
            }else{
                //有订单无出队
                //后期回收计划
                Db::rollback();
            }

        }
    }
    /**
     *队列状态变更
     * 周期结束后让排单中转变成匹配中
     */
    public function  match_check(){
        @ini_set("max_execution_time","1000");
        $list = Db::table("hz_formation")->alias("a")
                        ->join("hz_order b","a.id = b.from_form_id","left")
                        ->join("hz_user c","a.user_id = c.id","left")
                        ->field("a.id,a.amount,a.add_time,a.cycle,IFNULL(sum(b.amount),0) as paynum")
                        ->where("a.status",0)->where("c.user_status",1)->group("b.from_form_id")->select();
        foreach ($list as $vo){
            if($vo['add_time']+$vo['cycle']*24*60*60>time()){
                if($vo['paynum']==$vo['amount']){
                    Db::table("hz_formation")->update(['id'=>$vo['id'],'status'=>1]);
                }
            }
        }
    }
    /**
     * 队列状态变更
     * 支付超时处理1.队列交易中变更为匹配中2.订单取消3.逾期者冻结账号
     */
    public function order_check(){
        @ini_set("max_execution_time","1000");
        $time = time()-24*60*60;
        $list = Db::table("hz_order")->alias("a")
            ->join("hz_user b","a.from_user_id = b.id","left")
            ->field("a.id,a.from_user_id,a.to_form_id")
            ->where("a.status&a.msg_status",0)->where("b.user_status","1")
            ->where("a.add_time","elt",$time)->select();
        foreach ($list as $val){
            Db::table("hz_order")->update(['id'=>$val['id'],'status'=>2]);
            Db::table("hz_formation")->update(['id'=>$val['to_form_id'],'status'=>1]);
            Db::table("hz_user")->update(['id'=>$val['from_user_id'],'user_status'=>0]);
        }
    }
    /**
     * 短信通知付款
     */
    public function msg(){
        @ini_set("max_execution_time","1000");
        $time = time()-12*60*60;
        $list = Db::table("hz_order")->alias("a")
            ->join("hz_user b","a.from_user_id = b.id","left")
            ->field("a.id,a.add_time,b.user_nickname as name")
            ->where("a.status&a.msg_status",0)->where("b.user_status","1")
            ->where("a.add_time","elt",$time)->select();
        foreach ($list as $val){
            $end_time = date("m-d H:i",$val['add_time']+24*60*60);
            $content = "亲爱的用户{$val['name']},你有新的订单未支付为了不影响你的正常使用,请在{$end_time}前完成";
            //亲爱的用户【变量】,你有订单已经完成,请登录用户中心查看
            //发送短信调用短信接口
            Db::table("hz_order")->update(['id'=>$val['id'],'msg_status'=>1]);
        }
    }

    /**
     * 数据库备份
     * 每天记得备份哦
     */
    public function exportDatabase(){
        @ini_set("memory_limit","1024M");
        @ini_set("max_execution_time","1000");
        $db= new Backup();
        $list = $db->dataList();
        $file=['name'=>date('Ymd-His'),'part'=>1];
        foreach ($list as $val){
             $db->setFile($file)->backup($val['name'], 0);
        }
        operate_log(3,0,date("Y-m-d",time())."数据库备份完成");
        return 'ok';
    }
    /**
     * 排单收益
     * 推荐每天结算一次
     */
    public function days_change(){
        @ini_set("max_execution_time","1000");
        $start_time = mktime(0,0,0,date('m'),date('d'),date('Y'));
        $nowtime = time();
        Db::startTrans();
        $info = Db::table("hz_amount_log")->lock(true)->where("from",2)->where("add_time","egt",$start_time)->find();
        if($info){
            //你就饶了我吧,我今天跑过了
            Db::rollback();
        }else{
            //领赏了
            $lists = Db::table("hz_formation")->lock(true)->where("status","elt",2)->select();
            if($lists){
                $insdata = [];
                foreach($lists as $val){
                    //到期否
                    $one =[];
                    //暂时周期内发放
                    if(ceil((time()-$val['add_time'])/(24*60*60))<$val['cycle']){
                        //发钱啦
                        $num = $val['amount']*$val['income_scale']/100;
                        if($num){
                            $one['user_id'] = $val['user_id'];
                            $one['type'] = 1;
                            $one['num'] = $num;
                            $one['vector'] = 1;
                            $one['from'] = 2;
                            $one['from_id'] = $val['id'];
                            $one['add_time'] = $nowtime;
                            $insdata[] = $one;
                        }
                    }
                }
                if($insdata){
                    $res = Db::table("hz_amount_log")->insertAll($insdata);
                    if($res){
                        //正确日志 保持微笑
                        Db::commit();
                        operate_log(3,0,date("Y-m-d",$nowtime)."静态收益结算成功");
                    }else{
                        //错误日志走一波
                        Db::rollback();
                        operate_log(3,0,date("Y-m-d",$nowtime)."静态收益结算失败");
                    }
                }else{
                    //钱包保住了
                    Db::rollback();
                    operate_log(3,0,date("Y-m-d",$nowtime)."无静态收益");
                }
            }else{
                //人呢
                Db::rollback();
                operate_log(3,0,date("Y-m-d",$nowtime)."无静态收益");
            }
        }
    }

}
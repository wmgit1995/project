<?php
use think\Db;
/**
 * 检查并改变用户排单状态
 * 主要用来检查匹配后用户由匹配中变更为交易中1->2
 * $ids为排单id
 */
function check_user_formation($ids=[]){
    $lists = Db::table('hz_formation')->alias('a')
                    ->join("hz_user b","a.user_id = b.id","left")
                    ->field("a.*")
                    ->where("b.user_status",1)
                    ->where("a.status",1)
                    ->where("a.id","in",$ids)->select();
                foreach ($lists as $key=>$val) {
                    $paynum = Db::table("hz_order")->where('to_form_id', $val['id'])->where("status", 'lt', 2)->value("sum(amount)");
                    $sumnum = $val['amount'] / 100 * (100 + $val['income_scale'] * 10);//floor((time()-$val['add_time'])/24/60/60));
                    if($sumnum ==(int)$paynum){
                        Db::table('hz_formation')->update(['id'=>$val['id'],'status'=>2]);
                    }
                }
}
/**
 * 检查并改变用户排单状态
 * 主要用来检查排单用户支付后由交易中变更为完成2->3
 * $id为支付排单id
 */
function change_user_formation($id=''){
    $info = Db::table('hz_formation')->alias('a')
                    ->join("hz_user b","a.user_id = b.id","left")
                    ->field("a.*")
                    ->where("b.user_status",1)
                    ->where("a.status",2)
                    ->where("a.id",$id)->find();
                if($info){
                    $paynum = Db::table("hz_order")->where('to_form_id', $info['id'])->where("status", 1)->value("sum(amount)");
                    $sumnum = $info['amount'] / 100 * (100 + $info['income_scale'] * 10);//floor((time()-$val['add_time'])/24/60/60));
                    if($sumnum ==(int)$paynum){
                        Db::table('hz_formation')->update(['id'=>$info['id'],'status'=>3]);
                    }
                }
}
function p($data){
    echo '<pre>';
    return print_r($data);
}
function pp($data){
    echo '<pre>';
    return var_dump($data);
}
/**
 * 无限极分类获取分类树状信息
 * @param  [type]  $data  [description]
 * @param  integer $pid   [description]
 * @param  string  $html  [description]
 * @param  integer $level [description]
 * @return [type]         [description]
 */
function getCate($data,$pid=0,$html="|---",$level=0)
{
    $temp = array();
    foreach ($data as $k => $v) {
        if($v['pid'] == $pid){

            $str = str_repeat($html, $level);
            $v['html'] = $str;
            $temp[] = $v;

            $temp = array_merge($temp,getCate($data,$v['cate_id'],'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|---',$level+1));
        }

    }
    return $temp;
}
//请求数据到短信接口，检查环境是否 开启 curl init。
function Post($curlPost,$url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
}
 
//将 xml数据转换为数组格式。
function xml_to_array($xml){
    $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
    if(preg_match_all($reg, $xml, $matches)){
        $count = count($matches[0]);
        for($i = 0; $i < $count; $i++){
        $subxml= $matches[2][$i];
        $key = $matches[1][$i];
            if(preg_match( $reg, $subxml )){
                $arr[$key] = xml_to_array( $subxml );
            }else{
                $arr[$key] = $subxml;
            }
        }
    }
    return $arr;
}

//random() 函数返回随机整数。
function random($length = 6 , $numeric = 0) {
    PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
    if($numeric) {
        $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
    } else {
        $hash = '';
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
        $max = strlen($chars) - 1;
        for($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
    }
    return $hash;
}
/*
var_dump
 */
function dd(){
        $label = (null === $label) ? '' : rtrim($label) . ':';

        ob_start();
        var_dump($var);
        $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', ob_get_clean());

        if (IS_CLI) {
            $output = PHP_EOL . $label . $output . PHP_EOL;
        } else {
            if (!extension_loaded('xdebug')) {
                $output = htmlspecialchars($output, $flags);
            }

            $output = '<pre>' . $label . $output . '</pre>';
        }

        if ($echo) {
            echo($output);
            return;
        }

        return $output;
}
    /**
     * 发送验证码
     * $mobile  接受这手机号
     * $mobile_code 消息内容
     */
    function send_msg($mobile,$mobile_code){
        if(Request()->isPost()){
            //短信接口地址
            $target = "http://106.ihuyi.com/webservice/sms.php?method=Submit";
            $mobile = $_POST['mobile'];
            // $mobile_code = random(6,1);
            $mobile_code=$_POST['send_code'];//发送内容
            if(empty($mobile)){
                exit('手机号码不能为空');
            }
            //防用户恶意请求
            // if(empty($_SESSION['send_code']) or $send_code!=$_SESSION['send_code']){
            //     exit('请求超时，请刷新页面后重试');
            // }
            $appid='C45135418';
            $secret='1feec8227933ed6629f9e4c59182c65c';
            $post_data = "account=".$appid."&password=".$secret."&mobile=".$mobile."&content=".rawurlencode("尊敬的顾客，您好！您的订单验证码是".$mobile_code."，请在页面填写验证码完成验证。(如非本人操作，可不予理会)");
            $gets =  xml_to_array(Post($post_data, $target));
            if($gets['SubmitResult']['code']==2){
                $_SESSION['mobile'] = $mobile;
                $_SESSION['mobile_code'] = $mobile_code;
            }
            echo $gets['SubmitResult']['msg'];
            if($gets['SubmitResult']['msg']=='提交成功'){
                $res=['status'=>200,'msg'=>'发送成功！'];
                return json($res);
            }
        }
    }

    /**
     * 添加操作日志
     * type1用户2管理员3计划任务4错误信息
     * user_id 默认0
     */
    function operate_log($type,$user_id=0,$content=''){
        $data =['user_id'=>$user_id,'type'=>$type,'change'=>$content,'add_time'=>time()];
        Db::table("hz_record_log")->insert($data);

    }

    /**
     * 得到系统设置
     */

    function get_setting(){
        $info = Db::table("hz_option")->where("option_name", "match_setting")->find();
        $setting = json_decode($info['option_value'], true);
        return $setting;
    }
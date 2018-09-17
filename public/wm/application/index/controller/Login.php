<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Request;
use think\Captcha;
use think\Cookie;
use think\Validate;
class Login extends Controller{
    /*
     * 会员登录
     */
    public function login(){
       $prefix=config("database.prefix");
        if (request()->isPost()){
            $captcha = new \think\captcha\Captcha();
            if(!$captcha->check(input('post.captcha')) )
            {
                $data = [
                    'status' => 0,
                    'msg' => '验证码错误！',
                ];
                return json($data);//json对象
                // return json_encode($data);//JSON_ENCODE json字符串
            }else{
                $data=input('post.');
                $res=Db('user')->where(['user_login'=>input('post.user_login')])->find();
                if(empty($res['user_login'])){
                    $data = [
                        'status' => -1,
                        'msg' => '用户不存在！',
                    ];
                    return json($data);
                }
                if($res['user_login']){

                       if(md5(input('post.user_pass'))==$res['user_pass']){
                            if($res['user_status']==0){
                                $data = [
                                    'status' => -2,
                                    'msg' => '该用户已被禁用！',
                                ];
                                return json($data);
                            }
                            if ($res['session_id'] === session_id()) {
                                session::set('user_id', $res['id']);
                                session::set('user_name', $res['user_login']);
                                Cookie::set('user_name', $res['user_login'], 3600*24*30);
                                $ip = $_SERVER['REMOTE_ADDR'];
                                $login_time = time();
                                $session_id = session_id();
                                $info = Db('user')->where('id', $res['id'])->update(['session_id' => $session_id, 'last_login_time' => $login_time]);
                                return 'ok';
                            } else {
                                session::set('user_id', $res['id']);
                                session::set('user_name', $res['user_login']);
                                Cookie::set('user_name', $res['user_login'], 3600*24*30);
                                $ip = $_SERVER['REMOTE_ADDR'];
                                $login_time = time();
                                $session_id = session_id();
                                $info = Db('user')->where('id', $res['id'])->update(['session_id' => $session_id, 'last_login_time' => $login_time]);
                                return 'ok';
                                if ($info) {
                                    return "你的账号尝试在另一个设备上登录";
                                }
                            }
                            return 'ok';
                       }else{
                           // $this->success('账号或密码不正确！',url('Login/login'));
                           $data = [
                              'status' => -3,
                              'msg' => '账号或密码不正确！',
                           ];
                           return json($data);
                       }
                }
            }
        }else{
            return $this->fetch();
        }
    }
    /**
     * 获取图形验证码
     */
    public function verify()
    {
        // var_dump(Config('captcha'));
        //dump(\think\Config::get());
        // die;
        $captcha = new \think\captcha\Captcha(Config('captcha'));
        // pp($captcha);die;
        return $captcha->entry();
    }
      
    //验证规则
    protected $rule =   [
        'user_login' => ['unique:cate|require|length:4,25'],
        'user_login' => ['user_login.unique'=>'require|unique:User,user_login^id'], //必填。验证邮在user表中唯一性
        
        ];
    //验证对应方法
    protected $scene = [
        'register' => ['user_login.unique'=>'require|unique:User,user_login^id'],
    ];
    //错误提示
    //验证不符返回msg
    protected $message  =   [
        'user_login.require'      => '账号必须'
    ];
    /**
     * 注册
     */
    public function register(){
        //邀请码 推荐用户id
        if(request()->isPost()){
            $requset = Request::instance();
            $data = $requset->param();
            $uid=array();
            $info=Db('user')->field('id')->select();
            $mobile=db('user')->where(['mobile'=>$data['mobile']])->find();
            if($mobile['mobile_register_nums']==3){
                $res=[
                    'status'=>-1,
                    'msg'   =>'一个手机号只能注册3个用户！'
                ];
                return json($res);
            }

            foreach ($info as $key => $v1) {
               foreach ($v1 as $key => $v2) {
                   $uid[]=$v2;
               }
            }
// Session::set('send_code','1');
// dump(Session::get('send_code'));
// die;
// file_put_contents('1.txt', session::get('send_code'));
            $name=array();
            $info=Db('user')->field('user_login')->select();
            foreach ($info as $key => $v1) {
               foreach ($v1 as $key => $v2) {
                   $name[]=$v2;
               }
            }

            if(empty($data['user_login'])){
                $res=[
                    'status'=>-1,
                    'msg'   =>'用名不能空！'
                ];
                return json($res);
            }

            else if(!preg_match("/^[a-zA-Z0-9_-]{6,16}|[a-zA-Z]{6,16}|[a-zA-Z0-9]{6,16}$/",$data['user_login'])){
                $res=[
                    'status'=>-2,
                    'msg'   =>'用名格式不正确！,必须是6-16位字母或字母加数字！'
                ];
                return json($res);
            }
            //判断新用户是否已经存在
            else if(in_array($data['user_login'], $name)){
                $res=[
                    'status'=>-2,
                    'msg'   =>'该用户已经存在！请换个用户名试试！'
                ];
                return json($res); 
            }


            else if(empty($data['mobile'])){
                $res=[
                    'status'=>-3,
                    'msg'   =>'手机号不能空！'
                ];
                return json($res);
            }else if(!preg_match("/^(((13[0-9]{1})|(14[0-9]{1})|(17[0]{1})|(15[0-3]{1})|(15[5-9]{1})|(18[0-9]{1})|(19[0-9]{1}))+\d{8})$/", $data['mobile'])){
                $res=[
                    'status'=>-3,
                    'msg'   =>'手机号格式不正确！'
                ];
                return json($res);

            }

            else if(empty($data['send_code'])){
                $res=[
                    'status'=>-4,
                    'msg'   =>'手机验证码不能空！'
                ];
                return json($res);
            }else if($data['send_code']!==session::get('send_code')){
                $res=[
                    'status'=>-9,
                    'msg'   =>'手机验证码不正确！'
                ];
                return json($res);
            }
            else if($data['upwd'][0]==''){
                $res=[
                    'status'=>-7,
                    'msg'   =>'请输入密码！'
                ];
                return json($res);
            }
            else if($data['upwd'][1]==''){
                $res=[
                    'status'=>-7,
                    'msg'   =>'请再次输入密码！'
                ];
                return json($res);
            }
            else if($data['upwd'][1]!==$data['upwd'][0]){
                $res=[
                    'status'=>-8,
                    'msg'   =>'两次输入密码不一致！'
                ];
                return json($res);
            }
            //获取注册限制配置信息 0.不需要邀请码。1.关闭这侧 2.邀请注册
            $re_config=get_setting();
            if($re_config["register"]==2){
                if(empty($data['yqm_code'])){
                    $res=[
                        'status'=>-5,
                        'msg'   =>'邀请码不能空！'
                    ];
                    return json($res);
                    //邀请码就是上级用户ID
                }else if(!in_array($data['yqm_code'], $uid)){
                    $res=[
                        'status'=>-6,
                        'msg'   =>'邀请码不存在！'
                    ];
                    return json($res);
                }
            }else if($re_config["register"]==1){
                $res=[
                    'status'=>-1,
                    'msg'   =>'暂停注册！'
                ];
                return json($res);
            }
            //开放注册不需要邀请码
            if($re_config["register"]==0){
                $inserData['yqm_code']=0;
            }else{
                $inserData['yqm_code']=$data['yqm_code'];
            }

            //348577
            $inserData=[
                'user_login'    =>$data['user_login'],
                'user_pass'     =>md5($data['upwd'][0]),
                'mobile'        =>$data['mobile'],
                //'upid'          =>$data['yqm_code'],
                'user_nickname'  =>$data['user_login'],
                'create_time'   =>time(),
            ];

            // dump($inserData);die;
            $resultId=db('user')->insertGetId($inserData);
            $registerInfo=db('user')->where(['mobile'=>$data['mobile']])->find();
//            dump(db('user')->getLastSql());
//            dump($registerInfo);die;
            //$counts=db('user')->count();
            if($re_config["register"]==0){
                $parent=0;
                $upid=0;//父级ID,即邀请码
            }else if($re_config['register']==2){
                $puInfo=db('user')->where(['id'=>$data['yqm_code']])->field('id,parent')->find();
                $parent=$puInfo['parent'].'-'.$puInfo['id'];
                $upid=Request::instance()->param('yqm_code');
            }
            $res=db('user')->where('id',$resultId)-> update(['parent'=>$parent,'upid'=>$upid]);
            if($resultId){
                $res=[
                    'status'=>1,
                    'msg'   =>'恭喜注册成功！'
                ];
                if($registerInfo['mobile_register_nums']<3){
                    $regnums=$registerInfo['mobile_register_nums'] + 1;
                    db('user')->where(['mobile'=>$registerInfo['mobile']])->update(['mobile_register_nums'=>$regnums]);
//                    dump(db('user')->getLastSql());die;
                }
                //add
                Session::delete('send_code');
                return json($res); 
            }
            
        }else{
           return $this->fetch();
        }
    }
    /**
     * 找回密码
     */
    public function find_mima(){
        //邀请码 推荐用户id
        if(request()->isPost()){
            $requset = Request::instance();
            $data = $requset->param();
            $uid=array();
            $info=Db('user')->field('id')->select();
            $mobile=db('user')->where(['mobile'=>$data['mobile']])->find();
            foreach ($info as $key => $v1) {
               foreach ($v1 as $key => $v2) {
                   $uid[]=$v2;
               }
            }
// dump($_POST);die;
// Session::set('send_code','1');
// dump(Session::get('send_code'));
// die;
// file_put_contents('1.txt', session::get('send_code'));
            $name=array();
            $info=Db('user')->field('user_login')->select();
            foreach ($info as $key => $v1) {
               foreach ($v1 as $key => $v2) {
                   $name[]=$v2;
               }
            }

            if(empty($data['user_login'])){
                $res=[
                    'status'=>-1,
                    'msg'   =>'用名不能空！'
                ];
                return json($res);
            }

            else if(!preg_match("/^[a-zA-Z0-9_-]{6,16}|[a-zA-Z]{6,16}|[a-zA-Z0-9]{6,16}$/",$data['user_login'])){
                $res=[
                    'status'=>-2,
                    'msg'   =>'用名格式不正确！,必须是6-16位字母或字母加数字！'
                ];
                return json($res);
            }
            //判断新用户是存在
            // else if(!in_array($data['user_login'], $name)){
            //     $res=[
            //         'status'=>-2,
            //         'msg'   =>'该用户不存在！请换个用户名试试！'
            //     ];
            //     return json($res); 
            // }


            else if(empty($data['mobile'])){
                $res=[
                    'status'=>-3,
                    'msg'   =>'手机号不能空！'
                ];
                return json($res);
            }else if(!preg_match("/^(((13[0-9]{1})|(14[0-9]{1})|(17[0]{1})|(15[0-3]{1})|(15[5-9]{1})|(18[0-9]{1})|(19[0-9]{1}))+\d{8})$/", $data['mobile'])){
                $res=[
                    'status'=>-3,
                    'msg'   =>'手机号格式不正确！'
                ];
                return json($res);

            }
            else if($mobile['mobile_register_nums']==3){
                $res=[
                    'status'=>-1,
                    'msg'   =>'一个手机号只能注册3个用户！'
                ];
                return json($res);
            }
            else if(empty($data['send_code'])){
                $res=[
                    'status'=>-4,
                    'msg'   =>'手机验证码不能空！'
                ];
                return json($res);
            }

            else if($data['upwd']==''){
                $res=[
                    'status'=>-5,
                    'msg'   =>'请输入密码！'
                ];
                return json($res);
            }
            else if($data['send_code']!==session::get('send_code')){
                $res=[
                    'status'=>-6,
                    'msg'   =>'手机验证码不正确！'
                ];
                return json($res);
            }
            //348577
            $psotData=[
                'user_login'    =>$data['user_login'],
                'user_pass'     =>$data['upwd'],
                'mobile'        =>$data['mobile']
            ];
            $infp=db('user')->where(['user_login'=>$data['user_login'],'mobile'=>$data['mobile']])->find();
            if($info){
                $res=db('user')->where('user_login',$data['user_login'])-> update(['user_pass'=>md5($data['upwd'])]);
                if($res){
                    $res=[
                        'status'=>1,
                        'msg'   =>'修改成功！'
                    ];
                    Session::delete('send_code');
                    return json($res); 
                }

            }else{
                if($res){
                    $res=[
                        'status'=>1,
                        'msg'   =>'用户名和手机号绑定不是一个！'
                    ];
                    return json($res); 
                } 
            }
            
        }else{
           return $this->fetch();
        }
    }
    /**
     * 发送验证码
     */
    public function send_code(){
        if(Request()->isPost()){
            //短信接口地址
            $target = "http://106.ihuyi.com/webservice/sms.php?method=Submit";
            //用PSOTman 模拟提交
            //  1
            $mobile = $_POST['mobile'];
            //获取验证码
            // $send_code = $_POST['send_code'];
            //生成的随机数
            //
            //
            $mobile_code = random(6,1);
            if(empty($mobile)){
                exit('手机号码不能为空');
            }
            //防用户恶意请求
            // if(empty($_SESSION['send_code']) or $send_code!=$_SESSION['send_code']){
            //     exit('请求超时，请刷新页面后重试');
            // }
            $appid='C45135418';
            $secret='1feec8227933ed6629f9e4c59182c65c';
            $post_data = "account=".$appid."&password=".$secret."&mobile=".$mobile."&content=".rawurlencode("您的验证码是：".$mobile_code."。请不要把验证码泄露给其他人。");

            //查看用户名 登录用户中心->验证码通知短信>产品总览->API接口信息->APIID
            //查看密码 登录用户中心->验证码通知短信>产品总览->API接口信息->APIKEY
            $gets =  xml_to_array(Post($post_data, $target));
            if($gets['SubmitResult']['code']==2){
                $_SESSION['mobile'] = $mobile;
                $_SESSION['mobile_code'] = $mobile_code;
            }
            echo $gets['SubmitResult']['msg'];
            if($gets['SubmitResult']['msg']=='提交成功'){
                Session::set('send_code',$mobile_code);
            }
            // dump($_POST);
        }
    }

    function demo1(){
        $mobile=$_POST['mobile'];
        $mobile_code=$_POST['send_code'];
        send_msg($mobile,$mobile_code);
    }
    function demo2(){
        dump(get_setting());
    }

}
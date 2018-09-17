<?php
namespace app\admins\controller;
use think\Config;
use think\Controller;
use think\Request;
use think\Db;
use think\View;
use \think\Validate;
class User extends Base{
	//
	protected $scene = [
	'add' => ['username.unique'=>'require|unique:User,username^id'],
	];
    //禁用启用
    public function status_edit(){
        $aa=Request::instance()->param("status");
        $aid=Request::instance()->param("aid");
        if($aa==1){
            $status=0;
        }else{
            $status=1;
        }
        $res=Db('user')->where('id',$aid)->update(['user_status'=>$status]);

        if($res){
            return "ok";
        }
    }
	/*
	添加会员
	 */

	public function member_add(){
		if(request()->isPost()){
			$data=request::instance()->param();
			$insertdata['user_type']=$data['user_type'];
			$insertdata['sex']=$data['sex'];
			$insertdata['user_status']=$data['user_status'];
			$insertdata['user_login']=$data['user_login'];
			$insertdata['user_pass']=md5($data['user_pass']);
            $insertdata['user_nickname']=$data['user_nickname'];
            $insertdata['mobile']=$data['mobile'];
            $insertdata['ticket']=$data['ticket'];
            $insertdata['woc_num']=$data['woc_num'];
            $insertdata['credit_deposit']=$data['credit_deposit'];
            $insertdata['credit_seed']=$data['credit_seed'];
            $insertdata['level_id']=$data['level_id'];
            $insertdata['parent']=$data['parent'];
            $insertdata['last_login_time']=time();
            $insertdata['create_time']=time();
			// dump($data);die;
			$result=db('user')->field('user_login')->select();
			$name=[];
			foreach ($result as $key => $v1) {
				foreach($v1 as $v2){
					$name[]=$v2;
				}
			}
			if(in_array($data['user_login'],$name)){
				 echo "
				 	<script>alert('用户名已存在！');location.href='/admins/user/member_add';</script>
				 ";

			}
			$result=db('user')->where("mobile",$data['mobile'])->select();
            if(count($result) >= 3){
                echo "
				 	<script>alert('手机号已注册三个！');location.href='/admins/user/member_add';</script>
				 ";
            }
//			if(in_array($data['mobile'],$name)){
//				return '02';
//			}
			if(!preg_match("/^(((13[0-9]{1})|(14[0-9]{1})|(17[0]{1})|(15[0-3]{1})|(15[5-9]{1})|(18[0-9]{1}))+\d{8})$/",$data['mobile'])){
                echo "
				 	<script>alert('手机号格式不正确！');location.href='/admins/user/member_add';</script>
				 ";
			}
			$res=db('user')->insert($insertdata);

			if($res){
				return 'ok';
			}
		}else{
			return $this->fetch();
		}
	}
	/**
	会员列表
	*/
	public function index(){
				// dump(\think\Config::get('paginate'));
				if(Request::instance()->get("uid")){
					$aid=intval(Request::instance()->get("uid"));
					// var_dump($aid);
					 $lists=Db('user')->where('id',$aid)->paginate(10);
					 
				}else{
					 $lists=Db('user')->paginate(10);
				}
	          

				return $this->fetch("",['lists'=>$lists]);
	}
	// 修改会员基本信息
	public function member_edit(){
		if(request()->isPost()){
				$request = Request::instance();
				$data=input('post.');
				$res=db::table('hz_user')->where(['user_id'=>$data['user_id']])->update($data);
				// dump(db::table('user')->getLastSql());
				// $this->success('fds',url('user/index'));die;
				if($res){
						return "ok";
				}else{
						return "修改失败！";
				}
		}else{
			$request = Request::instance();
			$get = $request->get();
			$res=DB::name('hz_user')->where(['user_id'=>$get['id']])->find();
			// dump($res);
			$this->assign('userinfo',$res);
			return $this->fetch();
		}
	}
	//修改会员密码
	public function member_password(){
		if(request()->isPost()){
				$request = Request::instance();
				$data=input('post.');
				$data['pwd']=md5($data['pwd']);
				// dump($data);die;
				$res=db::table('hz_user')->where(['user_id'=>$data['user_id']])->update($data);
				// dump(db::table('user')->getLastSql());
				// $this->success('fds',url('user/index'));die;
				if($res){
						return "ok";
				}else{
						return "修改失败！";
				}
		}else{
			$request = Request::instance();
			$get = $request->get();
			$res=DB::table('hz_user')->where(['user_id'=>$get['id']])->find();
			// dump($res);
			$this->assign('userinfo',$res);
			return $this->fetch();
		}
	}
	//删除会员
	public function  member_del(){
		$request = Request::instance();
		$get = $request->get('user_id');
		// dump($get);die;
		$res=db::table('hz_user')->where('user_id',$get)->delete();
		if($res){
				$data=[
						'status'=>1,
						'msg'  =>'删除成功！'
				];
				return json($data);
		}else{
			$data=[
					'status'=>0,
					'msg'  =>'删除失败！'
			];
			return json($data);
		}
	}
	// 模糊查询 保留分页
	public function search(){
		$id = Request::instance()->param('username');
		// dump($username);die;
		// 保留分页模糊查询
		$lists = Db::table('hz_user')->where('user_id',$username)->paginate(10);
	    $page = $lists->render();
		// dump(Db::table('user')->getLastSql());
		$this -> assign('lists',$lists);
		$this->assign('page', $page);
		return $this->fetch('index');
	}
	public function stop_user(){
        $request=request();
        $id=$request->param('id');
        if($id){
            $bool=Db::table('hz_user')->where('user_id',$id)->update(['status'=>0]);
            if($bool){
                return "ok";
            }else{
                return "设置失败";
            }
        }
	}
    public function start_user(){
    	$request=request();
        $id=$request->param('id');
        if($id){
    		$bool=Db::table('hz_user')->where('user_id',$id)->update(['status'=>1]);
    		if($bool){
    			return "ok";
    		}else{
    			return "设置失败";
    		}
        }
    }

}

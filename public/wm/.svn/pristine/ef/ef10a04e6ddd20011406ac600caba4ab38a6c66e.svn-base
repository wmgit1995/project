<?php
namespace app\admins\controller;
use think\Config;
use think\Controller;
use think\Request;
use think\Db;
use think\View;
use think\Validate;
use think\Session;
class Lottery extends Base{
  //分页显示数据
  public function index(){
    $lists = Db::table('lottery')->order('id','desc')->paginate(8);
    $page = $lists->render();
    $count=Db::table('lottery')->order('id','desc')->count();
    $this->assign('count',$count);
    $this->assign('lists', $lists);
    $this->assign('page', $page);
    return $this->fetch();
  }
  //添加彩票信息 7位数
  public function lottery_add(){
    // dump($date);
    if(request()->isPost()){
        // echo date('Ym',time());
        /*$i='';
        $info=db('lottery')->order('id','desc')->find();
        if(empty($info['date'])){
            // $date=intval(date('Ym',time()).'000')+1;
            $date=intval(date('Ym',time())).'1';
        }
        if(!empty($info['date'])){
          $re=substr($info['date'], 6,strlen($info['date']-6));
          // dump($re);
          $date=intval(date('Ym',time()));
          $re=$re+1;
          $date=$date.$re;
          // dump($date);
        }*/
        $info=db('kjdate')->order('q_id','desc')->find();
        if(empty($info['kjdate'])){
            // $date=intval(date('Ym',time()).'000')+1;
            $date=intval(date('Ym',time())).'1';
        }
        $date=$info['kjdate']+1;
        $lotteryInfo=db('lottery')->where('date',$date)->field('set_num')->find();
        if($lotteryInfo['set_num']==1){
            return '-1';
        }
        
        $data=request::instance()->post();
        //查询通知信息及赔率
        $find=db('notice')->find();
        $data['odds']=$find['odds'];
        $data['notice']=$find['notice'];

        $data['date']=$date;
        $data['createtime']=time();
        // $shux=[
        //   '鼠'=>'11233547',
        //   '牛'=>'10223446',
        //   '虎'=>'09213345',
        //   '兔'=>'08203244',
        //   '龙'=>'07193143',
        //   '蛇'=>'06183042',
        //   '马'=>'05172941',
        //   '羊'=>'04162840',
        //   '猴'=>'03152739',
        //   '鸡'=>'02142638',
        //   '狗'=>'0113253749',
        //   '猪'=>'12243648'
        // ];
        $shux=[
            '11'=>'鼠',
            '23'=>'鼠',
            '35'=>'鼠',
            '47'=>'鼠',
            '10'=>'牛',
            '22'=>'牛',
            '34'=>'牛',
            '16'=>'牛',
            '09'=>'虎',
            '21'=>'虎',
            '33'=>'虎',
            '45'=>'虎',
            '08'=>'兔',
            '20'=>'兔',
            '32'=>'兔',
            '44'=>'兔',
            '07'=>'龙',
            '19'=>'龙',
            '31'=>'龙',
            '43'=>'龙',
            '06'=>'蛇',
            '18'=>'蛇',
            '30'=>'蛇',
            '42'=>'蛇',
            '05'=>'马',
            '17'=>'马',
            '29'=>'马',
            '41'=>'马',
            '04'=>'羊',
            '16'=>'羊',
            '28'=>'羊',
            '40'=>'羊',
            '03'=>'猴',
            '15'=>'猴',
            '27'=>'猴',
            '39'=>'猴',
            '02'=>'鸡',
            '14'=>'鸡',
            '26'=>'鸡',
            '38'=>'鸡',
            '01'=>'狗',
            '13'=>'狗',
            '25'=>'狗',
            '37'=>'狗',
            '49'=>'狗',
            '12'=>'猪',
            '24'=>'猪',
            '36'=>'猪',
            '48'=>'猪',
        ];
        $lvalue=$data['lottery_value'];
        $dvalue=explode(',', $lvalue);
        foreach ($dvalue as $k => $v) {
            // dump($shux);
            //echo $v;die;
            $sx='';
            $res='';
            if(array_key_exists($v, $shux)){
                $sx.= $shux[$v].',';
                // // echo $sx;
                // $res.=rtrim($sx,',');
                // echo $res;
                // echo $sx;
                if($k==0){
                    session::set('sx1',$sx);
                }else if($k==1){
                    session::set('sx2',$sx);
                }else if($k==2){
                    session::set('sx3',$sx);
                }else if($k==3){
                    session::set('sx4',$sx);
                }else if($k==4){
                    session::set('sx5',$sx);
                }else if($k==5){
                    session::set('sx6',$sx);
                }else if($k==6){
                    session::set('sx7',$sx);
                }
            }

        }
        $datasx=session::get('sx1').session::get('sx2').session::get('sx3').session::get('sx4').session::get('sx5').session::get('sx6').session::get('sx7');
        $data['sx']=rtrim($datasx,',');
        // dump($data['sx']);
        // die;
        // dump($dvalue);
        // dump($data);die;
        $res=Db::table('lottery') -> insertGetId($data);
        $res=Db::table('lottery') ->where('id',$res)-> update(['set_num'=>1]);
        // dump($res);die;
        // dump(Db('lottery')->getLastSql());
        if($res){
            session::delete('sx1');
            session::delete('sx2');
            session::delete('sx3');
            session::delete('sx4');
            session::delete('sx5');
            session::delete('sx6');
            session::delete('sx7');

            return 'ok';
        }else{
            return 'fail';
        }
    }else{
      return $this->fetch();
    }
  }
  //生产随机数
  public function getStr(){
      if(request()->isPost()){
          $data= rand(1,49);
          $str='';
          for($i=0;$i<7;$i++){
              $data=rand(1,49);
              if(strlen($data)==1){
                  $data='0'.$data;
                  // dump($data);
              }
              // die;
              $str.=$data.',';
          }
          $str2=substr($str,0,strlen($str)-1);
          // return json($str2);
          echo $str2;
      }
  }
  // 修改开奖号码信息
	public function lottery_edit(){
		if(request()->isPost()){
				$request = Request::instance();
				$data=input('post.');
				$res=db::table('lottery')->where(['id'=>$data['id']])->update($data);
				// dump(db::table('user')->getLastSql());
				if($res){
						return "ok";
				}else{
						return "修改失败！";
				}
		}else{
			$request = Request::instance();
			$get = $request->get();
			$res=DB::name('lottery')->where(['id'=>$get['id']])->find();
			$this->assign('info',$res);
			return $this->fetch();
		}
	}

  //开奖间隔时间
  public function lottery_date(){
    $lists = Db::table('lottery_date')->find();
    $this->assign('lists', $lists);
    return $this->fetch();
  }
  // 修改开奖间隔时间
	public function lottery_edit_date(){
		if(request()->isPost()){
				$request = Request::instance();
				$data=input('post.');
        // dump($data);die;
        $data['open_date']=intval($data['open_date']);
				$res=db::table('lottery_date')->where(['id'=>$data['id']])->update($data);
				// dump(db::table('user')->getLastSql());
				if($res){
						return "ok";
				}else{
						return "修改失败！";
				}
		}else{
			$request = Request::instance();
			$get = $request->get();
      $get['id']=1;
			$res=DB::name('lottery_date')->where(['id'=>$get['id']])->find();
			$this->assign('info',$res);
			return $this->fetch();
		}
	}

  //开奖通知
  public function notice(){
    $lists = Db::table('notice')->find();
    $this->assign('lists', $lists);
    return $this->fetch();
  }
  // 修改开奖通知
  public function edit_notice(){
    if(request()->isPost()){
        $request = Request::instance();
        $data=input('post.');
        // dump($data);die;
        $data['odds']=intval($data['odds']);
        $res=db::table('notice')->where(['id'=>$data['id']])->update($data);
        // dump(db::table('user')->getLastSql());
        if($res){
            return "ok";
        }else{
            return "修改失败！";
        }
    }else{
      $request = Request::instance();
      $get = $request->get();
      $res=DB::name('notice')->where(['id'=>$get['id']])->find();
      $this->assign('info',$res);
      return $this->fetch();
    }
  }




}
 ?>

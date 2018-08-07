<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use app\common\controller\Backend;
use think\Controller;
class User extends Backend
{
    public function _initialize() {
        parent::_initialize();
        $this->view->engine->layout('Layout/index');
    }
	public function index(){
		$result = Db::name('user')->order("`userid` desc")->select();
		$this->assign('result', $result);
		$arr = array();
		 foreach($result as $v){
			$arr['id'] =  $v['userid'];
		 }
		 //sprint ($arr);
		 //die();
		 $this->assign('arr',$arr);
		 return $this->fetch();
	}
	public function adduser(){
		$t = time();
	if($this->request->isPost()){
			$path = ROOT_PATH.'public/static/Upload/header/'.date("Ymd");
            $file = request()->file('photo');
			$photo="";
			if ($file) {
			$info = $file->rule('uniqid')->move($path);
            if($info){
                $photo =  $info->getFilename();
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
			}
       $data=array(
		   'username'=>$this->request->param('username'),
		   'realname'=>$this->request->param('realname'),
		   'sex'=>$this->request->param('sex'),
		   'password'=>md5($this->request->param('password')),
		   'salt'=>$this->request->param('salt'),
		   'work_number'=>$this->request->param('work_number'),
		   'national'=>$this->request->param('national'),
		   'card_id'=>$this->request->param('card_id'),
		   'jiguan'=>$this->request->param('jiguan'),
		   'xueli'=>$this->request->param('xueli'),
		   'marriage'=>$this->request->param('marriage'),
		   'professional'=>$this->request->param('professional'),
		   'graduated'=>$this->request->param('graduated'),
		   'begin_work_time'=>$this->request->param('begin_work_time'),
		   'join_work_time'=>$this->request->param('join_work_time'),
		   'department_id'=>$this->request->param('department_id'),
		   'work_id'=>$this->request->param('work_id'),
		   'work_name'=>$this->request->param('work_name'),
		   'political'=>$this->request->param('political'),
		   'zhicheng'=>$this->request->param('zhicheng'),
		   'home_address'=>$this->request->param('home_address'),
		   'home_mobile'=>$this->request->param('home_mobile'),
		   'email'=>$this->request->param('email'),
		   'photo'=>$photo,
		   'login_num'=>$this->request->param('login_num'),
		   'status'=>$this->request->param('status'),
		   'createtime'=>$t,
		   'logintime'=>$t,
		   'loginip'=>$this->request->param('loginip'),
		  ); 
		  $result = Db::name('user')->insert($data);
		  $userId = Db::name('user')->getLastInsID();
		  //sprint ($userId);
		  //die;
		  if(isset($_POST['items'])){
		  	$pkvs =  $_POST['items'];
			//sprint ($pkvs);
			//die();
		  foreach($pkvs as $value){
          Db::name('role_user')->insert(['role_id'=>$value,'user_id'=>$userId]);
          }
		  }
		  $this->redirect('user/index');
		  }
		$id = $this->request->param('id');
		$result = Db::name('user')->where("`userid`=$id")->find();
//		  sprint ($result);
//		  die;
		$this->assign('result',$result);
		$role = DB::name('role')->select();
		$this->assign('role',$role);

		  $this->assign('t',$t);//$t = time();
		  return $this->fetch();
	}
	public function edituser(){
			$t = time();
		if($this->request->isPost()){
			$path = ROOT_PATH.'public/static/Upload/header/'.date("Ymd");
            $file = request()->file('photo');
			$photo="";
			if ($file) {
			$info = $file->rule('uniqid')->move($path);
            if($info){
                $photo =  $info->getFilename();
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
			}
			$id = $this->request->param('userid');
       $data=array(
		   'username'=>$this->request->param('username'),
		   'realname'=>$this->request->param('realname'),
		   'sex'=>$this->request->param('sex'),
		   //'password'=>($this->request->param('password'))?md5($this->request->param('password')):($this->request->param('password1')),
		   //'salt'=>$this->request->param('salt'),
		   'work_number'=>$this->request->param('work_number'),
		   'national'=>$this->request->param('national'),
		   'card_id'=>$this->request->param('card_id'),
		   'jiguan'=>$this->request->param('jiguan'),
		   'xueli'=>$this->request->param('xueli'),
		   'marriage'=>$this->request->param('marriage'),
		   'professional'=>$this->request->param('professional'),
		   'graduated'=>$this->request->param('graduated'),
		   'begin_work_time'=>$this->request->param('begin_work_time'),
		   'join_work_time'=>$this->request->param('join_work_time'),
		   'department_id'=>$this->request->param('department_id'),
		   'work_id'=>$this->request->param('work_id'),
		   'work_name'=>$this->request->param('work_name'),
		   'political'=>$this->request->param('political'),
		   'zhicheng'=>$this->request->param('zhicheng'),
		   'home_address'=>$this->request->param('home_address'),
		   'home_mobile'=>$this->request->param('home_mobile'),
		   'email'=>$this->request->param('email'),
		   'login_num'=>$this->request->param('login_num'),
		   'status'=>$this->request->param('status'),
		   'createtime'=>$t,
		   'logintime'=>$t,
		   'loginip'=>$_SERVER['REMOTE_ADDR'],
		  ); 
		  if(!empty($photo)){	  
			  Db::name('user')->where("`userid`=$id")->update(['photo'=>$photo]);
		  }
		  if(!empty($_POST['password'])){
			  $dd =md5($_POST['password']);
			  Db::name('user')->where("`userid`=$id")->update(['password'=>$dd]);
		  }
		  // echo Db::name('role_user')->getLastSql();
		  // die();
		  $result = Db::name('user')->where("`userid`=$id")->update($data);
		  if(isset($_POST['items'])){
		  $pkvs=$_POST['items'];
	      Db::name('role_user')->where('user_id',$id)->delete();
          foreach($pkvs as $value){
            Db::name('role_user')->insert(['role_id'=>$value,'user_id'=>$id]);
          }
		  $this->redirect('user/index');
		  }else{
			  Db::name('role_user')->where('user_id',$id)->delete();
			  $this->redirect('user/index');
		  }		  
		  }else{
			  $t = time();
              //echo date("Ymd");
			  $id = $this->request->param('id');
			  $result = Db::name('user')->where("`userid`=$id")->find();
			  //echo Db::name('user')->getLastSql();
			  //die;
			$this->assign('result',$result);
			$role = DB::name('role')->select();
		    $this->assign('role',$role);
			$this->assign('t',$t);
			$role_user = Db::name('role_user')->field('role_id')->where("`user_id`=$id")->select();
			//echo Db::name('access')->getLastSql();
			//sprint ($role_user);
			//die();
			//二维数组转换一维数组
              $xx=array();
			  foreach ($role_user as $value1) {
                $xx[] = $value1['role_id'];
              }
			//sprint ($xx);
			//die();
			//$nodeid = str_split($access['node_id']);
			//sprint ($nodeid);
			//die();
			$this->assign('xx',$xx);
            $this->assign('role_user',$role_user );
            return $this->fetch();
		  }
	}
	public function deluser(){
		$id = $this->request->param('id');
	    Db::name('user')->where("`userid`=$id")->delete();
		Db::name('role_user')->where("`user_id`=$id")->delete();
		$this->success("操作成功", url('user/index', array(), 'html'));
	}
}
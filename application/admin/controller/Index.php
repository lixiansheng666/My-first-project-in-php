<?php
namespace app\admin\controller;
use app\common\controller\Backend;
use think\Session;
use think\Db;
error_reporting(0);
class Index extends Backend
{

	public function _initialize() {
		parent::_initialize();

    }

    public function index()
    {
        $this->view->engine->layout('Layout/index');
        // $userid=Session::get('uid');
       // // echo $userid;
       // $ss= db::name('role_user')->field('role_id')->where('user_id',$userid)->select();
        // //sprint($ss);
        // $s1=array();
        // foreach ($ss as $value) {
            // $s1[]=$value['role_id'];
        // }
        // //sprint($s1);
        // $s3=array();
        // foreach ($s1 as $s2) {
          // $s3[]= Db::name('access')->field('node_id')->where('role_id',$s2)->select(); 
        // }
        // //sprint($s3);
       // $c1= Db::name('node')->where('level',1)->select();
       // foreach($c1 as &$value){
                    // $value['childrens']=Db::name('node')->where('pid',$value['id'])->select();
                              // }
       // // // sprint($c1);
	   // // // die();
         // $this->assign('c1',$c1);
		// $result = Db::name('user')->select();
		// $this->assign('result',$result);
         return $this->fetch('index');
    }
	public function signout(){
		// 删除（当前作用域）
         session('username', null);
		 $this->success("退出成功");
	}
    public function editpassword(){
		if($this->request->isPost()){
			 $id = $_POST['aa'];
			  //echo $id;
			 $userid=Session::get('uid');
			 $result = Db::name('user')->where("`userid`=$userid")->find();
			 // sprint ($result['password']);
			 // die();
			 $ps = $result['password'];			 
			 if(md5($id)==$ps){
                echo "1111";
			 }else{
				echo "2222";
				echo "2222";
			 }
			if(!empty($_POST['password3'])){
			  $dd =md5($_POST['password3']);
			  Db::name('user')->where("`userid`=$userid")->update(['password'=>$dd]);
		    }
			 // $data=array(
			 // 'password'=>md5($this->request->param('password3')),
			 // );
			 //Db::name('user')->where("`userid`=$userid")->update($data);
			 // echo Db::name('user')->getLastSql();
			 // die();
			 $this->success('修改成功',url('index/index'));
		}else{	
			$this->view->engine->layout('Layout/index');
			//$userid=Session::get('uid');	
			//echo $userid;	
			// $result = Db::name('user')->where("`userid`=$userid")->find();
			// // sprint ($result);
			// // die();			
			// $this->assign('result',$result);
			return $this->fetch();
		}	
	}

}
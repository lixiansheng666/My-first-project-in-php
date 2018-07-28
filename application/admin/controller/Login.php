<?php
namespace app\admin\controller;
use think\Db;
use think\Session;

class Login extends \think\Controller
{
    public function index()
    {
		if ($this->request->isPost()) {
			$username = $this->request->post("username");
			$pwd      = $this->request->post("pwd");
			//  echo $username;
			//  echo $pwd;
			$ss=md5($pwd);
			$aaa=array(
				'username'=>$username,
				'password'=>$ss,
				'status'=>1
				);
	   $aa= Db::name('user')->where($aaa)->find();
	    // echo Db::name('admin')->getLastSql();
		// die();
		// 	 dump($aa);
		// SELECT * FROM `hr_admin` WHERE `username` = 'root' AND `password` = '63a9f0ea7bb98050796b649e85481845' AND `status` = 1 LIMIT 1
        //die;
      if($aa){
      	    $uid=$aa['userid'];
      	    Session::set('uid', $uid);
      	    Session::set('username', $username);
			$this->success('登录成功', url('index/index', []));
      }else{
      	$this->error('账号密码不正确或权限不正确请重新登录',url('login/index'),[]);
      }
		}   
        // 模板输出
        return $this->fetch('index');
    }
}
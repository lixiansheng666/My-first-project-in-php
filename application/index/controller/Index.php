<?php
namespace app\index\controller;
use think\Db;
use think\Request;
use think\Paginator;
use think\Session;
class Index extends \think\Controller
{
    public function index()
    {
		$one = Db::name('case')->order("case_id desc")->limit(1)->find();
		//sprint ($one);
		//echo Db::name('case')->getLastSql();
		//die;
		$this->assign('one',$one);
        return $this->fetch();
    }

	public function test() {
        return $this->fetch();
	}
    public function newslist()
    {
		$result = Db::name('article_category')->select();
		$this->assign('result', $result);
		$result5 = Db::name('article')->paginate(1);
		//$page = $result5->render();
		$this->assign('result5', $result5);
        return $this->fetch();
    }
    public function news1()
    {   
	    $id = $this->request->param('id');
		Db::name('article')->where('article_id',$id)->update(['click'=>['exp','click+1']]);
		//echo Db::name('article')->getLastSql();
		//die;
		
	    $result5 = Db::name('article')->select();
		$this->assign('result5', $result5);
		//sprint ($result5);
		//die;
		
		$result6 = Db::name('article')->where('article_id',$id)->select();
		$this->assign('result6', $result6);
		
		$article_body = Db::name('article_body')->where("`article_id`=$id")->find();
		$this->assign('article_body',$article_body);
		
		$id = $this->request->param('id');
		$n = Db::name('article')->field('article_id')->where("`article_id`>$id")->order('article_id asc')->limit(1)->find();
	    //echo Db::name('article')->getLastSql();
		//sprint ($id);
		//die;
		$p = Db::name('article')->field('article_id')->where("`article_id`<$id")->order('article_id desc')->limit(1)->find();
		$this->assign('n',$n);
		$this->assign('p',$p);
        return $this->fetch();
    }
    public function case1()
    {
		$id = $this->request->param('id');
		$result = Db::name('case')->where("`case_id`=$id")->select();
		//sprint ($result);
		//die;
		$this->assign('result',$result);
		
		//$id = $this->request->param('id');
		//echo $id;
		$result2 = Db::name('case_flag')->where("`case_id`=$id")->find();
		$this->assign('result2',$result2);
		

		$n = Db::name('case')->field('case_id')->where("`case_id`>$id")->order('case_id asc')->limit(1)->find();
	    //echo Db::name('case')->getLastSql();
		//sprint 
		//die;
		$p = Db::name('case')->field('case_id')->where("`case_id`<$id")->order('case_id desc')->limit(1)->find();
		$this->assign('n',$n);
		$this->assign('p',$p);
		
		
		$case_body = Db::name('case_body')->where("`case_id`=$id")->find();
		//sprint ($case_body);//一维数组直接输出
		//die;
		$this->assign('case_body',$case_body);
		
        return $this->fetch();
    }
    public function case2()
    {
        return $this->fetch();
    }
    public function case3()
    {
        return $this->fetch();
    }
    public function caseslist()
    {
		$result = Db::name('case_category')->select();
		$this->assign('result', $result);
		
		$id = $this->request->param('id');
		if($id==0){
		$result2 = Db::name('case')->paginate(1);
		}else{
		$result2 = Db::name('case')->where("`catid`=$id")->paginate(1);
		}
		$this->assign('result2',$result2);
        return $this->fetch();
    }
    public function message()
    {
		if($this->request->isPost()){
			$data=array(
			'nickname'=>$this->request->param('nickname'),
			'realname'=>$this->request->param('realname'),
			'sex'=>$this->request->param('sex'),
			'email'=>$this->request->param('email'),
			'mobile'=>$this->request->param('mobile'),
			'message_body'=>$this->request->param('message_body'),
			);
			// sprint ($data);
			// die();
			$result = Db::name('message')->insert($data);
			// echo Db::name('message')->getLastSql();
			// die();
			$this->success('留言成功','index/index');
		}
        return $this->fetch();
    }
    public function login(){
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
	   $aa= Db::name('indexuser')->where($aaa)->find();
	    // echo Db::name('admin')->getLastSql();
		// die();
		// 	 dump($aa);
		// SELECT * FROM `hr_admin` WHERE `username` = 'root' AND `password` = '63a9f0ea7bb98050796b649e85481845' AND `status` = 1 LIMIT 1
        //die;
      if($aa){
      	    $uuid=$aa['userid'];
      	    Session::set('uuid', $uuid);
      	    Session::set('indexusername', $username);
			$this->success('登录成功', url('index/index', []));
      }else{
      	$this->error('账号密码不正确或权限不正确请重新登录',url('login/index'),[]);
      }
		}   
        // 模板输出
        return $this->fetch();
	}
	public function register(){
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
	   $aa= Db::name('indexuser')->insert($aaa);
	    // echo Db::name('admin')->getLastSql();
		// die();
		// 	 dump($aa);
		// SELECT * FROM `hr_admin` WHERE `username` = 'root' AND `password` = '63a9f0ea7bb98050796b649e85481845' AND `status` = 1 LIMIT 1
        //die;
       $this->success('注册成功', url('index/index', []));
		}   
        // 模板输出
        return $this->fetch();
	}
	public function out(){
		// 删除（当前作用域）
         session('uuid', null);
		 $this->success("退出成功");
	}
}

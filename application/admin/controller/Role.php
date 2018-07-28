<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use app\common\controller\Backend;
use think\Controller;

class Role extends Backend
{
    public function _initialize() {
        parent::_initialize();
        $this->view->engine->layout('Layout/index');
    }
	public function index(){
		 $result = Db::name('role')->order("`id` desc")->select();
		 $this->assign('result',$result);
		 //sprint ($result);
		 //die();
		 // $arr = array();
		 // foreach($result as $v){
			// $arr[] =  $v['id'];
		 // }
		 // sprint ($arr);
		 // die();
		 // $this->assign('arr',$arr);
		 return $this->fetch();
	}
	public function addrole(){
	if($this->request->isPost()){
		$data=array(
		'name'=>$this->request->param('name'),
		//'pid'=>$this->request->param('pid'),
		'name'=>$this->request->param('name'),
		'status'=>$this->request->param('status'),
		'remark'=>$this->request->param('remark'),
		);
		$result = Db::name('role')->insert($data);
		$userId = Db::name('role')->getLastInsID();
		//sprint ($userId);
		//die;
		if(isset($pkvs)){
			$pkvs =  $_POST['items'];
			//sprint ($pkvs);
			//die();
          foreach($pkvs as $value){
          Db::name('access')->insert(['node_id'=>$value,'role_id'=>$userId]);
          }
		//sprint ($result_access);
		//die();
		}
		$this->assign('result',$result);
		$this->redirect('role/index');
	}
	     // 先取出一个二维数组（level = 1的三条数据）
           $list=Db::name('node')->where('level',1)->select();
		      //sprint($list); 
              //die;   
	     //将上述的子数组引用传值给$value，$value三个字数组的数据的id分别是1,2,3  ，
		 //再查询node表，将pid=1,2,3的数据查出来（也就是所有“应用”下叫“控制器”的数据），
		 //从而达到了树状的关系
              foreach($list as &$value){
                   $value['childrens']=Db::name('node')->where('pid',$value['id'])->select();
				                       }
              //sprint($list); 
              //die();
              $this->assign('list',$list);
		      return $this->fetch();
	}
	public function editrole(){
	  if($this->request->isPost()){
		$id = $this->request->param('id');
		$pkvs=$_POST['items'];
		$data=array(
		'name'=>$this->request->param('name'),
		'status'=>$this->request->param('status'),
		'remark'=>$this->request->param('remark'),
		);
		$result = Db::name('role')->where("`id`=$id")->update($data);
		//echo  Db::name('role')->getLastSql();
		//die;
		Db::name('access')->where('role_id',$id)->delete();
          foreach($pkvs as $value){
            Db::name('access')->insert(['node_id'=>$value,'role_id'=>$id]);
          };
		$this->redirect('role/index');
		}else{
			$id = $this->request->param('id');
			$result = Db::name('role')->where("`id`=$id")->find();
			$this->assign('result',$result);
		// 先取出一个二维数组（level = 1的三条数据）
           $list=Db::name('node')->where('level',1)->select();
	     //将上述的子数组引用传值给$value，$value三个字数组的数据的id分别是1,2,3  ，
		 //再查询node表，将pid=1,2,3的数据查出来（也就是所有“应用”下叫“控制器”的数据），
		 //从而达到了树状的关系
                 foreach($list as &$value){
                 $value['childrens']=Db::name('node')->where('pid',$value['id'])->select();
				 }
              // sprint($list); 
              // die();     
            $this->assign('list',$list);
			
			$access = Db::name('access')->field('node_id')->where("`role_id`=$id")->select();
			//echo Db::name('access')->getLastSql();
			//sprint ($access);
			//die();
			//二维数组转换一维数组
              $xx=array();
			  foreach ($access as $value1) {
                $xx[] = $value1['node_id'];
              }
			// sprint ($xx);
			// die();
			//$nodeid = str_split($access['node_id']);
			//sprint ($nodeid);
			//die();
			$this->assign('xx',$xx);
            $this->assign('access',$access);
            return $this->fetch();
		}
	}
	public function delrole(){
		$id = $this->request->param('id');
		Db::name('role')->where("`id`=$id")->delete();
		//echo  Db::name('role')->getLastSql();
		//die();
		Db::name('access')->where("`role_id`=$id")->delete();
		$this->success('操作成功',url('role/index', array(), 'html'));
	}
}
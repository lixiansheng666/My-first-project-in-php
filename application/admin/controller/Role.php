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
	     // ��ȡ��һ����ά���飨level = 1���������ݣ�
           $list=Db::name('node')->where('level',1)->select();
		      //sprint($list); 
              //die;   
	     //�����������������ô�ֵ��$value��$value��������������ݵ�id�ֱ���1,2,3  ��
		 //�ٲ�ѯnode����pid=1,2,3�����ݲ������Ҳ�������С�Ӧ�á��½С��������������ݣ���
		 //�Ӷ��ﵽ����״�Ĺ�ϵ
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
		// ��ȡ��һ����ά���飨level = 1���������ݣ�
           $list=Db::name('node')->where('level',1)->select();
	     //�����������������ô�ֵ��$value��$value��������������ݵ�id�ֱ���1,2,3  ��
		 //�ٲ�ѯnode����pid=1,2,3�����ݲ������Ҳ�������С�Ӧ�á��½С��������������ݣ���
		 //�Ӷ��ﵽ����״�Ĺ�ϵ
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
			//��ά����ת��һά����
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
		$this->success('�����ɹ�',url('role/index', array(), 'html'));
	}
}
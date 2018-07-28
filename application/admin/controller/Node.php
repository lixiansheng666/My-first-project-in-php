<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use app\common\controller\Backend;
use think\Controller;

class Node extends Backend
{
    public function _initialize() {
        parent::_initialize();
        $this->view->engine->layout('Layout/index');
    }
	public function index(){
		 $catid = (int)$this->request->param('id');
	     $result = Db::name('node')
		 ->alias('c')
		 ->field('c.*,c2.name as name2')
		 ->join('hr_node c2','c2.id=c.pid','left')
		 ->where("c.pid=$catid")
		 ->select();
		 $this->assign('catid',$catid);
		 $this->assign('result',$result);
		 return $this->fetch();
	}
	public function addnode(){
	if($this->request->isPost()){
		$data=array(
		'name'=>$this->request->param('name'),
		'title'=>$this->request->param('title'),
		'status'=>$this->request->param('status'),
		'remark'=>$this->request->param('remark'),
		'sort'=>$this->request->param('sort'),
		'pid'=>$this->request->param('pid'),
		'level'=>$this->request->param('level'),
		);
		$mm=$data['pid'];
		$result = Db::name('node')->insert($data);
		$this->assign('result',$result);
		$this->redirect('node/index',array('id' => $mm));
	}
	    $catid = (int)$this->request->param('id');
		$catData = Db::name('node')->where('id', $catid)->find();
		$this->assign('catData',$catData);//分配到前台
		return $this->fetch();
	}
	public function delnode(){
		//单条删除，跳转到当前页面（找上一条数据，取出pid，实现跳转），有弊端，如果上一条数据不属于当前页面，改进方法（建俩表）
		$id = $this->request->param('id');
		Db::name('node')->where("`id`=$id")->delete();
		//echo  Db::name('role')->getLastSql();
		//die();
        //$result = Db::name('node')->field('id')->where("`id`<$id")->order('id desc')->limit(1)->find();
		//$num = $result['id'];
		//$n = Db::name('node')->field('pid')->where("`id`=$num")->find();
		//sprint ($n);
		//die();
		//$this->success('操作成功',url('admin/node', array('id'=>$n['pid']), 'html'));
	    $this->success('操作成功',url('node/index', array(), 'html'));
	}
	public function editnode(){
     if($this->request->isPost()){
		$id = $this->request->param('id');
		//sprint ($id);
		//die();
	 $data=array(
		'name'=>$this->request->param('name'),
		'title'=>$this->request->param('title'),
		'status'=>$this->request->param('status'),
		'remark'=>$this->request->param('remark'),
		'sort'=>$this->request->param('sort'),
		'pid'=>$this->request->param('pid'),
		'level'=>$this->request->param('level'),
		);
		$mm=$data['pid'];
		$result = Db::name('node')->where("`id`=$id")->update($data);
		//echo  Db::name('node')->getLastSql();
		//die();
		$this->redirect('node/index',array('id'=>$mm));
		}else{
			$id = $this->request->param('id');
			$result = Db::name('node')->where("`id`=$id")->find();
			$this->assign('result',$result);
		    $catid = (int)$this->request->param('id');
		    $catData = Db::name('node')->where('id', $id)->find();
			//sprint ($catData);
			//die();
		    $this->assign('catData',$catData);//分配到前台
            return $this->fetch();
		}
	}
}

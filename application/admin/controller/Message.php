<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use app\common\controller\Backend;
use think\Controller;

class Message extends Backend
{
    public function _initialize() {
        parent::_initialize();
        $this->view->engine->layout('Layout/index');
    }
	public function index(){
		 $catid = (int)$this->request->param('id');
	     $result = Db::name('message')->select();
		 $this->assign('result',$result);
		 return $this->fetch();
	}
	public function delnode(){
		//单条删除，跳转到当前页面（找上一条数据，取出pid，实现跳转），有弊端，如果上一条数据不属于当前页面，改进方法（建俩表）
		$id = $this->request->param('id');
		Db::name('message')->where("`message_id`=$id")->delete();
		//echo  Db::name('role')->getLastSql();
		//die();
        //$result = Db::name('node')->field('id')->where("`id`<$id")->order('id desc')->limit(1)->find();
		//$num = $result['id'];
		//$n = Db::name('node')->field('pid')->where("`id`=$num")->find();
		//sprint ($n);
		//die();
		//$this->success('操作成功',url('admin/node', array('id'=>$n['pid']), 'html'));
	    $this->success('操作成功',url('message/index', array(), 'html'));
	}

}

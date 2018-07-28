<?php

namespace app\common\controller;
use think\Db;
use think\Controller;
use think\Session;



/**
 * 后台控制器基类
 */
class Backend extends Controller
{    

    public function _initialize() {
        $is_login = Session::get('username');
		if (!$is_login) {
			$this->redirect(url('login/index', []));
		}
		//sprint ($is_login);//成功获取已登录的用户名(username)：暂时打印出“李成功”
		//die();
		//后台左侧菜单栏
        $userid=Session::get('uid');
        //echo $userid; //成功获取已登录的用户id：暂时打印出“3”
		// 获取角色的所有 id 是二维数组
        $ss= db::name('role_user')->field('role_id')->where('user_id',$userid)->select();
        //sprint ($ss);
		//遍历二维数组 成一维数组
        $s1=array();
        foreach ($ss as $value) {
            $s1[]=$value['role_id'];
        }
        //sprint($s1);
        // s3 根据角色id 查询所有节点的id
        $s3=array();
        foreach ($s1 as $s2) {
          $s3[]= Db::name('access')->field('node_id')->where('role_id',$s2)->select();
        }
          // sprint ($s3);
          // die();
		  //三维数组变一维数组
        $s4 = array();
        foreach($s3 as $v){
            foreach ($v as $value) {
             $s4[]=$value['node_id'];
          }
        }
        // sprint ($s4);
		// die();
		//去除数组中重复的值
        $s5=array_unique($s4);
		//排序 按照 值升序排序
        asort($s5);
		// sprint ($s5);
        $this->assign('s5',$s5);
		//节点树状分类
		$c1= Db::name('node')->where('level',1)->order("`sort`")->select();
        foreach($c1 as &$value){
            $value['childrens']=Db::name('node')->where('pid',$value['id'])->select();
		}
		   // sprint($c1);
		   // die();
		   $this->assign('c1',$c1);
		   $c2 = Db::name('user')->where("`userid`=$userid")->find();
		   $this->assign('c2',$c2);
		   // sprint($c2);
		   // die();
		
    }
    

}

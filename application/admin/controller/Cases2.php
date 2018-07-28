<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use app\common\controller\Backend;
use think\Controller;

class Cases2 extends Backend
{
    public function _initialize() {
        parent::_initialize();
        $this->view->engine->layout('Layout/index');
    }
	public function index(){
        $case_id = (int)$this->request->param('id');
		$result = Db::name('case')->order("`case_id` desc")
		->paginate(8);
		$page = $result->render();
		//$this->assign('page', $page);
		$this->assign('result', $result);
		//分页结束
		return $this->fetch();
	}
	//增加案例
	public function addlists(){
		if($this->request->isPost()){
			//上传图片 问问经理，有点不理解
			$path = ROOT_PATH.'public/static/Upload/header/'.date("Ymd");
            $file = request()->file('thumb');
			$thumb="";
			if ($file) {
			$info = $file->rule('uniqid')->move($path);
            if($info){
                $thumb =  $info->getFilename();
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
			}
			//到这结束
		  $lists=array(
		  'case_title'=>$this->request->param('case_title'),
		   'case_matter'=>$this->request->param('case_matter'),
		   'catid'=>$this->request->param('catid'),
		   //'thumb'=>$this->request->param('thumb'),
		   'thumb'=>$thumb,//还有这
		   'toptime'=>$this->request->param('toptime'),
		   'click'=>$this->request->param('click'),
		   'writer'=>$this->request->param('writer'),
		   'source'=>$this->request->param('source'),
		   'link_url'=>$this->request->param('link_url'),
		   'updater'=>$this->request->param('updater'),
		   //updatetime字段由date函数分配到前台了
		   'status'=>$this->request->param('status'),
		   'createtime'=>$this->request->param('createtime'),
		  );
		  $list = Db::name('case')->insert($lists);
		  $userId = Db::name('case')->getLastInsID();
		  $kaka=($this->request->param('case_body')!=null) ? $_POST['case_body'] : null;
		  $cc = stripslashes($kaka);
		  $content = array(
		  'case_id'=>$userId,
		  'case_body'=>$cc,
		  );
		  $biaoqian = array(
		  'case_id'=>$userId,
		  'flag_name'=>$this->request->param('flag_name'),
		  );
		Db::name('case_body')->insert($content);
		Db::name('case_flag')->insert($biaoqian);
	    
		$this->assign('list',$list);
		$this->redirect('cases2/index');
		}
		$time = time();
        $this->assign('time', $time);
		
		$catsList = Db::table('hr_case_category')->select();
        $catsList = genTree($catsList, $id='catid', $pid='parent_id', $son = 'children');
            //sprint($catsList);
        $options = getTreeSelect($catsList, $depth = 0, $id = 'catid', $name = 'cat_name', $children = 'children', $depthStyle = "-");
        $this->assign('options', $options);
		$this->assign('catsList', $catsList);
		return $this->fetch();
	}
		//单条删除
	public function delcase(){
		$id = $this->request->param('id');
		//sprint ($id);
		$result = Db::name('Case')->where('case_id',$id)->delete();
		$this->success("操作成功", url('cases2/index', array(), 'html'));
	}
	//批量删除
	public function delalllists(){
		$pkvs =  $_POST['items'];
		//sprint ($pkvs);//打印出数组，键为0，值为选中的case_id
		$ids = implode(",", $pkvs);
		//sprint ($ids);//打印出以，隔开的字符串case_id
		 $rs = Db::name('case')->where("`case_id` in ($ids)")->delete();
           // echo Db::name('case_category')->getLastSql();
	     $this->success("批量删除成功",url('cases2/index'));
	}
	public function editcase(){
		  if($this->request->isPost()){
			  //上传图片
			$thumb = '';
			//{php}echo date('Ymd', $v['createtime']);{/php}//图片路径以时间为文件夹
            $path = ROOT_PATH.'public/static/Upload/header/'.date("Ymd");
            $file = request()->file('thumb');
            if ($file) {
                $info = $file->rule('uniqid')->move($path);
            if($info){
                $thumb = $info->getFilename();
                }else{
            // 上传失败获取错误信息
                    echo $file->getError();
                }
            }
		  $id=$this->request->param('case_id');
		  $data=array(
		  'case_title'=>$this->request->param('case_title'),
		   'case_matter'=>$this->request->param('case_matter'),
		   'catid'=>$this->request->param('catid'),
		   //'thumb'=>$this->request->param('thumb'),
		   'thumb'=>$thumb,//还有这
		   'toptime'=>$this->request->param('toptime'),
		   'click'=>$this->request->param('click'),
		   'writer'=>$this->request->param('writer'),
		   'source'=>$this->request->param('source'),
		   'link_url'=>$this->request->param('link_url'),
		   'updater'=>$this->request->param('updater'),
		   //updatetime字段由date函数分配到前台了
		   'status'=>$this->request->param('status'),
		   'createtime'=>$this->request->param('createtime'),
		  );
		  $dd =['case_body'=>stripslashes($_POST['case_body'])];
		  $bb=['flag_name'=>$this->request->param('flag_name')];
		  Db::table('hr_case_body')->where(array('case_id'=>$id))->update($dd);
		  Db::table('hr_case_flag')->where(array('case_id'=>$id))->update($bb);
		  Db::name('case')->where(array('case_id'=>$id))->update($data);
		  $time = time();
		  $this->assign('time', $time);
		  $this->redirect('cases2/index');
		  }else{
			 $id=$this->request->param('id');
			 $data1=Db::table('hr_case')
			 ->alias('a')
			 ->field('a.*,b.case_body,c.flag_name')
			 ->join('hr_case_body b','b.case_id=a.case_id','left')
			 ->join('hr_case_flag c','c.case_id=a.case_id','left')
			 ->where("a.case_id=$id")
			 ->find();
			 $this->assign('data1',$data1); 
			 $ss=$data1['catid'];
			 $data3=Db::name('case_category')->where('catid',$ss)->find();
			 $this->assign('data3',$data3);
			  
			  //展示要修改的数据
			  //$id=$this->request->param('id');
			  //sprint ($id);
			  //$result=Db::name('case')->where(array('case_id' => $id))->select();
			  //$this->assign('result', $result);
			  $time = time(); 
              $this->assign('time', $time);
			  //树状
			  $catsList = Db::table('hr_case_category')->select();
              $catsList = genTree($catsList, $id='catid', $pid='parent_id', $son = 'children');
              //sprint($catsList);
              $options = getTreeSelect($catsList, $depth = 0, $id = 'catid', $name = 'cat_name', $children = 'children', $depthStyle = "-");
              $this->assign('options', $options);
		      $this->assign('catsList', $catsList);
			  return $this->fetch();
		  }
	}
	
	
}
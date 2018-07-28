<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use app\common\controller\Backend;
use think\Controller;

class News2 extends Backend
{
    public function _initialize() {
        parent::_initialize();
        $this->view->engine->layout('Layout/index');
    }
			//显示案例
	public function index(){
        $article_id = (int)$this->request->param('id');
		$result = Db::name('article')->order("`article_id` desc")
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
		  'article_title'=>$this->request->param('article_title'),
		   'article_matter'=>$this->request->param('article_matter'),
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
		   'createtime'=>time(),
		  );
		  $list = Db::name('article')->insert($lists);
		  //返回增加案例的那行新增的主键ID
		  $userId = Db::name('article')->getLastInsID();
		  //sprint ($userId);
		  //die;
		  $kaka = isset($_POST['article_body'])?$_POST['article_body']:'';
		  //$kaka=($this->request->param('article_body')!=null) ? $_POST['article_body'] : null;
		  //$cc = stripslashes($kaka);
		  $cc = strip_tags($kaka,'<p>');
		  $content = array(
		  'article_id'=>$userId,
		  'article_body'=>$cc,
		  );
		Db::name('article_body')->insert($content);
		$this->assign('list',$list);
		$this->redirect('news2/index');
		}
		$time = time();
        $this->assign('time', $time);
		$catsList = Db::name('article_category')->select();
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
		$result = Db::name('article')->where('article_id',$id)->delete();
		$this->success("操作成功", url('news2/index', array(), 'html'));
	}
		//批量删除
	public function delalllists(){
		$pkvs =  $_POST['items'];
		//sprint ($pkvs);//打印出数组，键为0，值为选中的article_id
		$ids = implode(",", $pkvs);
		//sprint ($ids);//打印出以，隔开的字符串case_id
		$rs = Db::name('article')->where("`article_id` in ($ids)")->delete();
           // echo Db::name('case_category')->getLastSql();
	    $this->success("批量删除成功",url('news2/index'));
	}
		public function editcase(){
		  if($this->request->isPost()){
			  //上传图片
			$thumb = '';
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
		  $id=$this->request->param('article_id');
		  $data=array(
		  'article_title'=>$this->request->param('article_title'),
		   'article_matter'=>$this->request->param('article_matter'),
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
		  $dd =['article_body'=>stripslashes($_POST['article_body'])];
		  Db::table('hr_article_body')->where(array('article_id'=>$id))->update($dd);
		  Db::name('article')->where(array('article_id'=>$id))->update($data);
		  $time = time();
		  $this->assign('time', $time);
		  $this->redirect('news2/index');
		  }else{
			 $id=$this->request->param('id');
			 $data1=Db::table('hr_article')
			 ->alias('a')
			 ->join('hr_article_body b','b.article_id=a.article_id','left')
			 ->where("a.article_id=$id")
			 ->find();
			 $this->assign('data1',$data1); 
			 $ss=$data1['catid'];
			 $data3=Db::name('article_category')->where('catid',$ss)->find();
			 $this->assign('data3',$data3);
			  //展示要修改的数据
			  //$id=$this->request->param('id');
			  //sprint ($id);
			  //$result=Db::name('case')->where(array('case_id' => $id))->select();
			  //$this->assign('result', $result);
			  $time = time();
              $this->assign('time', $time);
			  //树状
			  $catsList = Db::table('hr_article_category')->select();
              $catsList = genTree($catsList, $id='catid', $pid='parent_id', $son = 'children');
              //sprint($catsList);
              $options = getTreeSelect($catsList, $depth = 0, $id = 'catid', $name = 'cat_name', $children = 'children', $depthStyle = "-");
              $this->assign('options', $options);
		      $this->assign('catsList', $catsList);
			  return $this->fetch();
		  }
	}
}
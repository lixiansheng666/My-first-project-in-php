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
			//��ʾ����
	public function index(){
        $article_id = (int)$this->request->param('id');
		$result = Db::name('article')->order("`article_id` desc")
		->paginate(8);
		$page = $result->render();
		//$this->assign('page', $page);
		$this->assign('result', $result);
		//��ҳ����
		return $this->fetch();
	}
		//���Ӱ���
	public function addlists(){
		if($this->request->isPost()){
			//�ϴ�ͼƬ ���ʾ����е㲻���
			$path = ROOT_PATH.'public/static/Upload/header/'.date("Ymd");
            $file = request()->file('thumb');
			$thumb="";
			if ($file) {
			$info = $file->rule('uniqid')->move($path);
            if($info){
                $thumb =  $info->getFilename();
            }else{
                // �ϴ�ʧ�ܻ�ȡ������Ϣ
                echo $file->getError();
            }
			}
			//�������
		  $lists=array(
		  'article_title'=>$this->request->param('article_title'),
		   'article_matter'=>$this->request->param('article_matter'),
		   'catid'=>$this->request->param('catid'),
		   //'thumb'=>$this->request->param('thumb'),
		   'thumb'=>$thumb,//������
		   'toptime'=>$this->request->param('toptime'),
		   'click'=>$this->request->param('click'),
		   'writer'=>$this->request->param('writer'),
		   'source'=>$this->request->param('source'),
		   'link_url'=>$this->request->param('link_url'),
		   'updater'=>$this->request->param('updater'),
		   //updatetime�ֶ���date�������䵽ǰ̨��
		   'status'=>$this->request->param('status'),
		   'createtime'=>time(),
		  );
		  $list = Db::name('article')->insert($lists);
		  //�������Ӱ�������������������ID
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
	//����ɾ��
	public function delcase(){
		$id = $this->request->param('id');
		//sprint ($id);
		$result = Db::name('article')->where('article_id',$id)->delete();
		$this->success("�����ɹ�", url('news2/index', array(), 'html'));
	}
		//����ɾ��
	public function delalllists(){
		$pkvs =  $_POST['items'];
		//sprint ($pkvs);//��ӡ�����飬��Ϊ0��ֵΪѡ�е�article_id
		$ids = implode(",", $pkvs);
		//sprint ($ids);//��ӡ���ԣ��������ַ���case_id
		$rs = Db::name('article')->where("`article_id` in ($ids)")->delete();
           // echo Db::name('case_category')->getLastSql();
	    $this->success("����ɾ���ɹ�",url('news2/index'));
	}
		public function editcase(){
		  if($this->request->isPost()){
			  //�ϴ�ͼƬ
			$thumb = '';
            $path = ROOT_PATH.'public/static/Upload/header/'.date("Ymd");
            $file = request()->file('thumb');
            if ($file) {
                $info = $file->rule('uniqid')->move($path);
            if($info){
                $thumb = $info->getFilename();
                }else{
            // �ϴ�ʧ�ܻ�ȡ������Ϣ
                    echo $file->getError();
                }
            }
		  $id=$this->request->param('article_id');
		  $data=array(
		  'article_title'=>$this->request->param('article_title'),
		   'article_matter'=>$this->request->param('article_matter'),
		   'catid'=>$this->request->param('catid'),
		   //'thumb'=>$this->request->param('thumb'),
		   'thumb'=>$thumb,//������
		   'toptime'=>$this->request->param('toptime'),
		   'click'=>$this->request->param('click'),
		   'writer'=>$this->request->param('writer'),
		   'source'=>$this->request->param('source'),
		   'link_url'=>$this->request->param('link_url'),
		   'updater'=>$this->request->param('updater'),
		   //updatetime�ֶ���date�������䵽ǰ̨��
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
			  //չʾҪ�޸ĵ�����
			  //$id=$this->request->param('id');
			  //sprint ($id);
			  //$result=Db::name('case')->where(array('case_id' => $id))->select();
			  //$this->assign('result', $result);
			  $time = time();
              $this->assign('time', $time);
			  //��״
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
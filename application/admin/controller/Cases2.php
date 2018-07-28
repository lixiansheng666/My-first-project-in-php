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
		  'case_title'=>$this->request->param('case_title'),
		   'case_matter'=>$this->request->param('case_matter'),
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
		//����ɾ��
	public function delcase(){
		$id = $this->request->param('id');
		//sprint ($id);
		$result = Db::name('Case')->where('case_id',$id)->delete();
		$this->success("�����ɹ�", url('cases2/index', array(), 'html'));
	}
	//����ɾ��
	public function delalllists(){
		$pkvs =  $_POST['items'];
		//sprint ($pkvs);//��ӡ�����飬��Ϊ0��ֵΪѡ�е�case_id
		$ids = implode(",", $pkvs);
		//sprint ($ids);//��ӡ���ԣ��������ַ���case_id
		 $rs = Db::name('case')->where("`case_id` in ($ids)")->delete();
           // echo Db::name('case_category')->getLastSql();
	     $this->success("����ɾ���ɹ�",url('cases2/index'));
	}
	public function editcase(){
		  if($this->request->isPost()){
			  //�ϴ�ͼƬ
			$thumb = '';
			//{php}echo date('Ymd', $v['createtime']);{/php}//ͼƬ·����ʱ��Ϊ�ļ���
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
		  $id=$this->request->param('case_id');
		  $data=array(
		  'case_title'=>$this->request->param('case_title'),
		   'case_matter'=>$this->request->param('case_matter'),
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
			  
			  //չʾҪ�޸ĵ�����
			  //$id=$this->request->param('id');
			  //sprint ($id);
			  //$result=Db::name('case')->where(array('case_id' => $id))->select();
			  //$this->assign('result', $result);
			  $time = time(); 
              $this->assign('time', $time);
			  //��״
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
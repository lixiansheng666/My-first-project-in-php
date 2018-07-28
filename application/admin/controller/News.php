<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use app\common\controller\Backend;
use think\Controller;

class News extends Backend
{
    public function _initialize() {
        parent::_initialize();
        $this->view->engine->layout('Layout/index');
    }

    public function index(){
	    $catid = (int)$this->request->param('id');
		//sprint ($catid);//打印出当页在url上的id号，根目录为0
		//die;
		//查询数据
		//$list = Db::name('article_category')->order("`catid` desc")->paginate(10);
        //$this->assign('list', $list);
		
		//分页
		// 查询状态为1的用户数据 并且每页显示10条数据
        $result = Db::name('article_category')->order("`catid` desc")
		->alias('c')
        ->field('c.*, c2.cat_name as cat_name2')
        ->join('hr_case_category c2', 'c2.catid=c.parent_id', 'left')
        ->where("c.parent_id=$catid")
		->paginate(8);
		//echo Db::name('article_category')->getLastSql();
		//die;
		$page = $result->render();
		
		//所属分类栏，显示父分类的名称
		$catData = Db::name('article_category')->where('catid', $catid)->find();
		//$catData = Db::name('article_category')->where('catid',$catid)->select();//根据catid找到该行数据
		$this->assign('catData',$catData);//分配到前台
				
        // 把分页数据赋值给模板变量list
        $this->assign('result', $result);
        // 渲染模板输出
		$time = time();
        $this->assign('time', $time);
		$this->assign('catid', $catid);
        return $this->fetch();
    }
		//删除数据
	public function del(){
	if($this->request->param('id') != 'undifined'){
	 $id = $this->request->param('id');
	 $num = Db::name('article_category')->where('parent_id',$id)->count();
     $type = true;
	 if($num){
		 $type = false;
	     }
	 if(!$type){
		 $this->error("有子分类不能删除");
	 }
	 else{
      $result = Db::name('article_category')->where("`catid` = $id")->delete();
      $this->success("操作成功", url('news/index', array(), 'html'));
	 }
	}
	}
	
	//批量删除数据
    public function delcategory()
    {
        $pkvs =  $_POST['items'];
		//print_r($pkvs);
		//die;
    if ($pkvs) {
            $ids = implode(",", $pkvs);
			$type = true;
			foreach($pkvs as $value){
		     $num = Db::name('article_category')->where('parent_id',$value)->count();
	if($num){
		 $type = false;
	     }
	 if(!$type){
		 $this->error("有子分类不能删除");
	 }else{
            $rs = Db::name('article_category')->where("`catid` in ($ids)")->delete();
           // echo Db::name('article_category')->getLastSql();
			$this->success("批量删除成功",url('news/index', array(), 'html'));
        }
    }
}
}
    public function addcategory(){
		 if($this->request->isPost()){
			 
			// tp5上传
            // 移动到框架应用根目录/public/uploads/ 目录下
            $path = ROOT_PATH.'public/static/Upload/header/'.date("Ymd");
            $file = request()->file('thumb');
			$thumb="";
			if ($file) {
			$info = $file->rule('uniqid')->move($path);
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                //echo $info->getExtension();
                //echo "<br />";
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                //echo $info->getSaveName();
                //echo "<br />";
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                $thumb =  $info->getFilename();
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
			}
			//RETURN;
			 
          $data=array(
		   'cat_name'=>$this->request->param('cat_name'),
		   'parent_id'=>$this->request->param('parent_id'),
		   'type'=>$this->request->param('type'),
		   //'thumb'=>$this->request->param('thumb'),
		   'thumb'=>$thumb,
		  'status'=>$this->request->param('status'),
		  );
		  $mm=$data['parent_id'];
		  //echo $mm;
		  //die;
		  Db::name('article_category')->insert($data);
		  //$this->redirect('news/category');
		  $this->redirect('news/index',array('id' => $mm));
		 }
		  $catid = (int)$this->request->param('id');
		  //echo $catid;
		  $catData = Db::name('article_category')->where('catid', $catid)->find();
		 // $catData = Db::name('article_category')->where('catid',$catid)->select();//根据catid找到该行数据
		  $this->assign('catData',$catData);//分配到前台
		  return $this->fetch();
    }
	
	public function editcategory(){
		//修改分类项
        if($this->request->isPost()){
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
            $id=$this->request->param('catid');
            $data1=[
                'cat_name'=>$this->request->param('cat_name'),
                'parent_id'=>$this->request->param('parent_id'),
                'type'=>$this->request->param('type'),   
                'thumb'=>$thumb,     				
                'status'=>$this->request->param('status'),
            ];
			$mm=$data1['parent_id'];
            if ($thumb) {
                $data1['thumb'] = $thumb;
            }
            Db::name('article_category')->where(array('catid'=>$id))->update($data1);
            $this->redirect('news/index',array('id' => $mm));
           }else{   		
           //展示需要修改的数据
                    //$id=$this->request->param('id');
					//$id = input('param.id');
					$id = Request::instance()->param('id'); 
                    $result=Db::name('article_category')->where(array('catid' => $id))->select();
                    $this->assign('result',$result);
					$catid = (int)$this->request->param('id');
		            $catData = Db::name('article_category')->where('catid', $catid)->find();
		            $this->assign('catData',$catData);//分配到前台
                    return $this->fetch();
           } 
	}

}
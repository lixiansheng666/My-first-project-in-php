<?php
namespace app\index\controller;
use think\Db;
use think\Request;
use think\Paginator;
use think\Session;
class Shop extends \think\Controller
{
	public function index(){
		$result = Db::name('shop')->select();
		$this->assign('result', $result);
		// sprint ($result);
		// die();
		if(isset($_GET['aa'])){
		$aa = $_GET['aa'];
		// echo $aa;
		// return;	
		$result2 = Db::name('shop')->where("`shop_id`=$aa")->select();
		foreach ($result2 as &$value) {
			$value['goto_car_url'] = url('shop/checkout', ['id'=>$value['shop_id']]);
		}
		echo json_encode($result2);
		return;
		}
        return $this->fetch();
    }
    public function login(){
	  if ($this->request->isPost()) {
			$username = $this->request->post("username");
			$pwd      = $this->request->post("pwd");
			//  echo $username;
			//  echo $pwd;
			$ss=md5($pwd);
			$aaa=array(
				'username'=>$username,
				'password'=>$ss,
				'status'=>1
				);
	    $aa= Db::name('shop_user')->where($aaa)->find();
	    // echo Db::name('shop_user')->getLastSql();
		// die();
      if($aa){
      	    $uuuid=$aa['userid'];
      	    Session::set('uuuid', $uuuid);
      	    Session::set('shopusername', $username);
			$this->success('登录成功', url('index/shop/index', []));
      }else{
      	$this->error('账号密码不正确或权限不正确请重新登录',url('shop/login'),[]);
      }
		}   
        // 模板输出
        return $this->fetch();
	}
	public function registered(){
		if ($this->request->isPost()) {
            $username = $this->request->post("username");
			$email      = $this->request->post("email");
			$pwd      = $this->request->post("pwd");
			//  echo $username;
			//  echo $pwd;
			$ss=md5($pwd);
			$aaa=array(
				'username'=>$username,
				'email'=>$email,
				'password'=>$ss,
				'status'=>1
				);
	   $aa= Db::name('shop_user')->insert($aaa);
	    // echo Db::name('admin')->getLastSql();
		// die();
		// 	 dump($aa);
		// SELECT * FROM `hr_admin` WHERE `username` = 'root' AND `password` = '63a9f0ea7bb98050796b649e85481845' AND `status` = 1 LIMIT 1
        //die;
       $this->success('注册成功', url('shop/login', []));
		}   
        // 模板输出
        return $this->fetch();
    }
	public function out(){
		// 删除（当前作用域）
         session('uuuid', null);
		 $this->success("退出成功");
	}
	public function checkout(){
	    if(isset($_GET['bb'])){
		$bb = $_GET['bb'];
		// echo $bb;
		// return;	
		 $userid=Session::get('uuuid');
		 $data=array('shop_id'=>$bb,'uuid'=>$userid);
		 $result = Db::name('shop_car')->insert($data);
		// foreach ($result2 as &$value) {
			// $value['goto_car_url'] = url('shop/checkout', ['id'=>$value['shop_id']]);
		// }
		//echo json_encode($result);
		//return;
		}
	}
	public function shopcar(){
		if(session('?uuuid')){
		//查询两张表，商品表和购物车表：当购物车表的shop_id=商品表的shop_id时，查询商品表里的这个商品
		$result = Db::name('shop')->alias('s')->join('shop_car c','s.shop_id = c.shop_id')->group('c.shop_id')->select();
	    // echo Db::name('hr_shop')->getLastSql();
	    // die();
		$this->assign('result',$result);
		return $this->fetch();
	}else{
		$this->success('请先登录后购买','shop/login');
	}
	}
	public function shopcardel(){
		$id = $this->request->param('id');
        //Db::name('shop')->where("`shop_id`=$id")->delete();
		Db::name('shop_car')->where("`shop_id`=$id")->delete();
		$this->redirect('shop/shopcar');
		return $this->fetch();
	}
	public function confirm(){
		if(isset($_POST['items'])){
		$pkvs = $_POST['items'];
	    // sprint ($pkvs);
		// die();
		$ids = implode(",", $pkvs);
		$check_result = Db::name('shop')->where("`shop_id` in ($ids)")->select();
		// sprint ($check_result);
		// die();
		$this->assign('check_result',$check_result);
		foreach ($pkvs as $value) {
		   $array=array(
			  'num'=>$_POST["num_"."$value"],
			  );
		}
	    Db::name('shop_car')->where('shop_id',$value)->update($array);
		
		// echo Db::name('shop_car')->getLastSql();
		// die();
		$uuuid = session('uuuid');
		// sprint ($uuuid);
		// die();
		$result = Db::name('shop_car')->alias('a')->field('a.num,b.costprice')
		->join('shop b','a.shop_id=b.shop_id','left')->where("`uuid`=$uuuid")->select();
		//sprint ($result);
		//die();
		
        foreach ($result as $value) {
			  $array[]= $value['num']*$value['costprice'];                    
		   } 
		//echo array_sum($array);
		
		}else{
        //echo "<script>alert('没有选择商品');window.location.href='';</script>";
        //$this->redirect('shop/shopcar');
		$this->error('没有选择商品');
		}
		 return $this->fetch();
	}
	public function add_address(){	

      //省市县三级联动，并增加到数据库		
	   $rs = Db::name('region')->where("`parent_id`=1")->select();
	   $this->assign('rs',$rs);
	if(isset($_GET['province_id'])){
		$province_id = $_GET['province_id'];
		$rs2 = Db::name('region')->where("`parent_id`=$province_id")->select();		
		echo $rs2 ? json_encode($rs2) : '';
		die();
	   } 
	if(isset($_GET['city_id'])){
		$city_id = $_GET['city_id'];
		$rs3 = Db::name('region')->where("`parent_id`=$city_id")->select();		
		echo $rs3 ? json_encode($rs3) : '';
		die();
	   } 
		  if(!empty($_POST['isdefault'])){
            $a = 1;
		  }else{
			$a = 0;
		  }
		//获取登录用户的id
	   $uuuid = session('uuuid');
		// sprint ($uuuid);
		// die();	
       if($this->request->isPost()){	  
		$data = array(
		'memberid'=>$uuuid,
		'address'=>$this->request->param('address'),
		'postcode'=>$this->request->param('postcode'),
		'consignee'=>$this->request->param('consignee'),
		'isdefault'=>$a,
		'province_id'=>$this->request->param('province_id'),
		'city_id'=>$this->request->param('city_id'),
		'area_id'=>$this->request->param('area_id')
		);
		$result = Db::name('member_shipping')->insert($data);		
		// echo Db::name('member_shipping')->getLastSql();
		// die(); 
		$this->success('新增地址成功',url('shop/add_address', array(), 'html'));
	   }else{
		//地址列表多表查询，先根据登录用户的id找到他对应的省市县的id，然后再根据省市县的id找region表里的三个region_id，再分配到前台		   
		$showresult = Db::name('member_shipping')
			->alias('m')->field('m.*')
			->join('hr_region r','m.province_id = r.region_id','left')->field('r.region_name as province_name')
            ->join('hr_region y','m.city_id = y.region_id','left')->field('y.region_name as city_name')
			 ->join('hr_region z','m.area_id = z.region_id','left')->field('z.region_name as area_name')
			->where("`memberid`=$uuuid")->select();
			//echo Db::name('member_shipping')->getLastSql();
			// sprint ($showresult);
			// die();
		 $this->assign('showresult',$showresult);
	   }
	    return $this->fetch();
	}
     public function edit_address(){
	   //修改省、市、县
	   		$uuuid = session('uuuid');
			// sprint ($uuuid);
			// die();
	   $rs = Db::name('region')->where("`parent_id`=1")->select();
	   $this->assign('rs',$rs);
	   if($this->request->isPost()){
		if(!empty($_POST['isdefault'])){
			$a = 1;
			}else{
			$a = 0;
			}
			//获取登录用户的id	
	$data = array(
		'memberid'=>$uuuid,
		'address'=>$this->request->param('address'),
		'postcode'=>$this->request->param('postcode'),
		'consignee'=>$this->request->param('consignee'),
		'isdefault'=>$a,
		'province_id'=>$this->request->param('province_id'),
		'city_id'=>$this->request->param('city_id'),
		'area_id'=>$this->request->param('area_id')
		);
		  $updateresult = Db::name('member_shipping')->update($data);		
		// echo Db::name('member_shipping')->getLastSql();
		// die(); 
	   }else{
		   // $showresult = Db::name('member_shipping')->where("`memberid`=$uuuid")->select();
		    // $this->assign('showresult',$showresult);
		    // sprint ($showresult);
		   //die();	
           //地址列表多表查询，先根据登录用户的id找到他对应的省市县的id，然后再根据省市县的id找region表里的三个region_id，再分配到前台		   
		$showresult = Db::name('member_shipping')
			->alias('m')->field('m.*')
			->join('hr_region r','m.province_id = r.region_id','left')->field('r.region_name as province_name')
            ->join('hr_region y','m.city_id = y.region_id','left')->field('y.region_name as city_name')
			 ->join('hr_region z','m.area_id = z.region_id','left')->field('z.region_name as area_name')
			->where("`memberid`=$uuuid")->select();
			//echo Db::name('member_shipping')->getLastSql();
			// sprint ($showresult);
			// die();
		    $this->assign('showresult',$showresult);
			
			//修改显示的那条默认的地址
		$showresult2 = Db::name('member_shipping')
			->alias('m')->field('m.*')
			->join('hr_region r','m.province_id = r.region_id','left')->field('r.region_name as province_name')
            ->join('hr_region y','m.city_id = y.region_id','left')->field('y.region_name as city_name')
			 ->join('hr_region z','m.area_id = z.region_id','left')->field('z.region_name as area_name')
			//->where("`memberid`=$uuuid and `isdefault`=1")
			->where(array('memberid'=>$uuuid,'isdefault'=>1))
			->find();
			// echo Db::name('member_shipping')->getLastSql();
			// sprint ($showresult2);
			// die();
		    $this->assign('showresult2',$showresult2);
	   }
	    return $this->fetch();
	 }
		
	
	public function products(){
		 return $this->fetch();
	}
	public function products1(){
		 return $this->fetch();
	}
	public function codes(){
		 return $this->fetch();
	}
	public function mail(){
		 return $this->fetch();
	}
	public function single(){
		 return $this->fetch();
	}
}
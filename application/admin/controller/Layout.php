<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use app\common\controller\Backend;
use think\Controller;
class Layout extends \think\Controller
{
   public function layout(){
	   return $this->fetch('index');
   }
}
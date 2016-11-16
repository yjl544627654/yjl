<?php
namespace Component;
use Think\Controller;

class AdminController extends Controller
{
	
	function __construct(){
		parent::__construct();

		if(  strtolower(CONTROLLER_NAME) !='login' && strtolower(ACTION_NAME) != 'login' ){
			if( empty($_SESSION['username']) ){
			 	header("Location:".U('login/login')." ") ;
			}
		}

		$school_list = D('school')->select();
		$this->assign('SchoolList',$school_list);
		$this->assign('sid',I('get.sid'));
		//var_dump($_SESSION);
		//锁屏
		if( $_SESSION['lock'] > time() ){
			//有效期内刷新锁屏时间
			set_lock();
		}

		

	}
}





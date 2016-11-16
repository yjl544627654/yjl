<?php
namespace Home\Controller;
use Component\AdminController;
class LoginController extends AdminController
{
	
	public function login(){
		if( session('?username') && session('?id') ){
			$this->success('您已登录',U('index/index')) ;
		}else{
			$this->display();
		}
		
	}

	public function login_do()
	{
		
		$db = D('user');

		$username = I('post.username','',"strip_tags");
		$pwd = I('post.pwd','','strip_tags');
		$rem = I('post.rem');

		if( empty($username) )  $this->error('请输入用户名！');
		if( empty($pwd) )		$this->error('请输入密码！');

		if(  $userid = check_pwd( $username,$pwd ) ){

			$uinfo = $db->where(array('username'=>$username))->find();
			if( $uinfo['state'] > 0 )  $this->error('您的用户已被禁用！'); 

			$lock = M('setting')->where("name='lock'")->getField('settime');//查询设置的锁屏时间
			$lock = intval($lock);
			if( $lock>0 ){
				$locktime = time()+($lock*3600);
				session('setlock',$lock);  //设置保存lock总小时
				session('lock',$locktime); // 设置锁屏时间戳
			}
			session('username',$username);
			session('id',$userid);
			if( $rem ){
				cookie('username',$username,3600*24*30);
				cookie('id',$userid,3600*24*30);
				cookie('pwd',$uinfo['pwd'],3600*24*30);
			}
			$this->success('登录成功！',U('index/index'));
		}else{
			$this->error('登录失败！账号或者密码错误！');
		}

	}

	

	public function login_out(){

		session(null);
		cookie(null);
		$this->success('退出成功！');
	}
}





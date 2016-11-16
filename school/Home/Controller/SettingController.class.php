<?php
namespace Home\Controller;
use Component\AdminController;

class SettingController extends AdminController {

	public function index(){
		$this->display();
	}

	//我的账户
	public function account(){
		$db_user = D('user');
		$id = $_SESSION['id'];

		if( IS_POST ){
			$pwd = I('post.pwd','');
			
			if($pwd){
				if( check_pwd( $_SESSION['username'],$pwd ) ){
					$hash = $db_user->where('id='.$id)->getField('hash');
					$data['pwd']= !empty($_POST['new_pwd']) ? md5(I('post.new_pwd').$hash) : $this->error('新密码不能为空');
					$msg = '，您已修改账号密码';
				}else{
					$this->error('管理员密码输入错误');
				}
			}
			
			$data['emali'] = I('post.emali','');
			$data['truename'] =I('post.truename','');
			$data['mark'] = I('post.mark');

			$db_user->where(array('id'=>$id))->save($data);
			$this->success('修改成功！'.$msg);
			write_log('修改自账户资料');
			
		}
		else{
			
			$user = $db_user->where(array('id'=>$id))->find();
			$this->assign('user',$user);	
			$this->display();
		}
		
	}

	//用户列表
	public function user_list(){
		if( !check_power('setting-user_list') )  $this->error('您没有权限进入'); 
		$db_user = D('user');

		if(IS_GET){
			$username = "%".I('post.username','')."%";
			if( I('post.username') ) $map['username'] = array('LIKE',$username);
			if( I('post.state') ) $map['state'] = I('state',0);
			if( I('post.uid') ){
				 $map['uid'] = I('uid');
				 $this->assign('uid',$map['uid']);
			}
		}
		$list = $db_user->where($map)->select();
		//var_dump($db_user->getLastSql());
		foreach ($list as $key => $value) {
			$list[$key]['group'] = D('user_group')->where('id='.$value['uid'])->getField('groupname');
		}
		$group = D('user_group')->select();
		$this->assign('group',$group);
		$this->assign('list',$list);
		$this->display();
		
	}


	//添加用户
	public function add_user(){
		if(!check_power('setting-user_list') )  $this->error('您没有权限进入'); 
		$db_user = D('user');

		if( IS_POST ){
			$username = I('post.username');
			$pwd = I('post.pwd');
			$uid = I('post.uid');

			$data['username'] = !empty($username) ? $username : $this->error('请输入用户名');
			$check_name = $db_user->where(" username='".$data['username']."'" )->getField('username');
			if( !empty($check_name) ){
				$this->error('此用户名已被使用！');
			}  
			$data['pwd'] = (!empty( $pwd ) and strlen($pwd)>=5 ) ? $pwd : $this->error('未输入密码或者密码长度不足5位以上');
			if( I('reg_pwd') != $data['pwd'] )  $this->error('两次输入的密码不同');
			$data['uid'] = !empty($uid) ? $uid : $this->error('请选择用户组！');
			$data['mark'] = I('make','');
			$data['addtime'] = $data['updatetime'] = time();
			$data['hash'] = randstr();
			$data['pwd'] = md5($data['pwd'].$data['hash']);

			if( D('user')->add($data) )
			{
				$this->success('添加用户成功！');
				write_log('添加用户'.$data['username']);
			}

		}else{

			$grouplist = D('user_group')->field('groupname,id')->select();
			$this->assign('grouplist',$grouplist);
			$this->display();
		}
		
	}

	//修改用户
	public function edit_user(){
		if(!check_power('setting-user_list') )  $this->error('您没有权限进入'); 
		$db_user = D('user');

		$map['id'] = I('get.id',0);
		if( empty($map['id']) )  $this->error('没有选择用户');

		$info = $db_user->where($map)->find();

		if(IS_POST){

			$pwd = I('new_pwd','');
			if(!empty($pwd) ){
				$data['pwd'] = md5($pwd.$info['hash']);
			}
			$data['username'] = !I('username','') ? $this->error('用户名不能为空！') : I('username','');
			$data['emali'] = I('emali','');
			$data['truename'] =I('truename','');
			$data['mark'] = I('mark','','strip_tags');

			$db_user->where($map)->save($data);
			$this->success('修改成功！',U('setting/user_list'));
			write_log('修改用户'.$data['username']);

		}else{
			$this->assign('info',$info);
			$this->display();
		}
		
	}


	//删除用户
	public function del_user(){
		if(!check_power('setting-user_list') )  $this->error('您没有权限进入'); 

		$id = I('id',0);
		$map['id'] = $id ? $id : $this->error('操作有误！');
		if( $id == $_SESSION['id'] )  $this->error('你不能删除当前用户！');
		if(D('user')->where($map)->delete()){
			$this->success('删除用户成功！');
			write_log('删除用户');	
		}
	}

	//用户组
	public function user_group(){
		if(!check_power('setting-user_list') )  $this->error('您没有权限进入'); 
		$db_group = D('user_group');

		if(IS_POST){
			//添加组
			$group_name = I('group_name');
			$data['groupname'] = !empty($group_name) ? $group_name : $this->error('请输入新增的组名！');
			$data['addtime'] = $data['updatetime'] = time();
			if($db_group->add($data)) 
			{
				$this->success('新增用户组成功！');
				write_log('添加用户组：'.$group_name);
			}

		}else{
			$list = $db_group->select();
			$this->assign('list',$list);
			$this->display();
		}
		
	}

	//ajax 设置禁用/启用用户
	public function set_state(){
		$where['id'] = I('id');

		if($where['id'] ){
			
			$state = D('user')->where($where)->getField('state');
			if($state>0){
				$map['state'] = 0;
				D('user')->where($where)->save($map);
				echo 0;
				write_log('启用用户，id:'.$where['id']);
			}else{
				$map['state'] = 1;
				D('user')->where($where)->save($map);
				echo 1;
				write_log('禁用用户，id:'.$where['id']);
			}
		}

	}

	//用户组编辑
	public function ajax_editgroup(){
		if(!check_power('setting-user_list') )  $this->error('您没有权限进入'); 
		$map['id'] = I('id',0);
		$data['groupname'] = I('name','');

		D('user_group')->where($map)->save($data) ;
		echo $data['groupname'];

	}

	public function set_power(){
		if(!check_power('setting-user_list') )  $this->error('您没有权限进入'); 
		$id = I('get.id') ? I('get.id') : $this->error('获取参数错误');
		if(IS_POST){
			$arr_power = I('post.power') ; 
			$data['power'] = implode(',', $arr_power);

			D('user')->where('id='.$id)->save($data);
			write_log('修改id：'.$id.' 用户权限');
			$this->success('保存成功!');
			
		}else{
			$power = D('user')->where('id='.$id)->getField('power');
			if($power == 'all')  $this->error('你无权操作超级管理员');

			$power_arr = explode(',', $power) ;
			$this->assign('power',$power_arr);

			$this->display();
		}
		
	}

	//删除用户组
	public function del_group(){
		if(!check_power('setting-user_list') )  $this->error('您没有权限进入'); 
		$map['id'] = I('id') ? I('id') : $this->error('操作有误');

		if( D('user_group')->where($map)->delete() ){
			$this->success('删除成功！');
			write_log('删除用户组');
		}
	}
	
	//系统日志
	public function log(){ 
		if(!check_power('setting-log') )  $this->error('您没有权限进入'); 
		$time = gettime();
		if( !empty($time) ){
			$map = 'addtime>'.$time['start'].' AND addtime<'.$time['end'] ; 
		}
		$table = 'log';
		$order = 'addtime DESC';
		$row = getlistpage($table,$map,$order);
		
		$this->assign('list',$row['list']);
		$this->assign('page',$row['page']);
		$this->display();
	}


	//设置锁屏时间
	public function setting(){ 
		if(!check_power('setting-setting') )  $this->error('您没有权限进入'); 
		if(IS_POST){
			$map['name'] = 'lock' ;
			$data['settime'] = I('post.lock',0) ;
			$data['updatetime'] = time();
			$data['user_log'] = $_SESSION['username'];
			if(D('setting')->where($map)->save($data))
			{
				//$_SESSION['setlock'] = $data['settime'] ;
				write_log('设置锁屏时间为：'.$data['settime'].'小时');
				$this->success('修改成功！');
				
			}
			
		}else{
			$locktime = D('setting')->where('name="lock"')->getField('settime');
			$this->assign('locktime',$locktime);
			$this->display();
		}
		
	}

	public function ajax_locklogin(){ //解锁登录
		$pwd = I('pwd');
		if(!empty($pwd)){
			$db_user = D('user');
			$map['username'] = $_SESSION['username'] ; 
			$info = $db_user->where($map)->field('hash,pwd')->find();
			$pwd = md5($pwd.$info['hash']);
			if($pwd == $info['pwd'] ) {
				set_lock();
				echo 'ok';
				write_log('解锁登录');
			}
		}

	}

	
	


}
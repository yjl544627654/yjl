<?php
namespace Home\Controller;
use Component\AdminController;
class SchoolController extends AdminController {

   public function school_list(){

   		$list = D('school')->select();
   		$this->assign('list',$list);
   		$this->display('list');
   }

   public function school_add(){

   	if(IS_POST){
   		$db_school = D('school');

   		$data['name'] = I('post.name') ? I('post.name') :$this->error('请输入学校名称');
   		$data['school_id'] = I('post.school_id') ? I('post.school_id') :$this->error('请输入学校id');
         $sdk_adder = I('post.sdk_adder') ? I('post.sdk_adder') :$this->error('请输入接口地址');
   		$data['sdk_adder'] = ($sdk_adder); 
   		$data['admin_adder'] = I('post.admin_adder') ? I('post.admin_adder') :$this->error('请输入后台地址');
   		if( $db_school->add($data) ){
   			$this->success('添加成功！',U('school_list'));
   		}else{
   			$this->error('添加失败！');
   		}
   	}else{
   		$this->display();	
   	}
   	
   }

   public function school_edit(){

	   	$db_school = D('school');
	   	$map['id'] = I('get.id') ?  I('get.id') : $this->error('获取id失败！');

	   	if(IS_POST){

	   		$data['name'] = I('post.name') ? I('post.name') :$this->error('请输入学校名称');
	   		$data['school_id'] = I('post.school_id') ? I('post.school_id') :$this->error('请输入学校id');
	   		$sdk_adder = I('post.sdk_adder') ? I('post.sdk_adder') :$this->error('请输入接口地址');
            $data['sdk_adder'] = $sdk_adder;
	   		$data['admin_adder'] = I('admin_adder') ? I('admin_adder') :$this->error('请输入后台地址');
            
	   		if( $db_school->where($map)->save($data) ){
	   			$this->success('修改成功！',U('school_list'));
	   		}else{
	   			$this->error('未修改任何内容');
	   		}

	   	}else{

		   	$show = $db_school->where($map)->find();
		   	$this->assign('show',$show);
		   	$this->display();

	   	}
   
   }

   public function school_del(){

   		$id = I('get.id') ?  I('get.id') : $this->error('获取id失败！');
   		if( D('school')->delete($id) ){
	   			$this->success('删除成功！',U('school_list'));
   		}else{
   			$this->error('删除失败！');
   		}
   }

}
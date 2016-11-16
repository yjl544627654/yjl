<?php
namespace Home\Controller;
use Component\AdminController;
class SchoolController extends AdminController {

   function __construct(){
      parent::__construct();
      if(!check_power('school_list') ) $this->error('您没有访问权限');   
   }

   public function school_list(){

   		$list = D('school')->select();
   		$this->assign('list',$list);
   		$this->display('list');
   }

   public function school_list1(){

         $list = D('school')->select();
         $this->assign('list',$list);
         $this->display('list1');
   }
   public function school_list2(){

         $list = D('school')->select();
         $this->assign('list',$list);
         $this->display('list2');
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

            write_log('添加学校 {$data["name"]}');  //写入日志
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
            
	   		$db_school->where($map)->save($data);
            write_log('编辑id: '.$map['id'].' 学校('.$data['name'].')' );
   			$this->success('修改成功！',U('school_list'));

	   	}else{

		   	$show = $db_school->where($map)->find();
		   	$this->assign('show',$show);
		   	$this->display();

	   	}
   
   }

   public function school_del(){

   		$id = I('get.id') ?  I('get.id') : $this->error('获取id失败！');
   		if( D('school')->delete($id) ){
               write_log('删除id: '.$id.' 学校');
	   			$this->success('删除成功！',U('school_list'));
   		}else{
   			$this->error('删除失败！');
   		}
   }

}
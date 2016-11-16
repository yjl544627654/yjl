<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>校园饮水项目管理云平台</title>
        <meta name="keywords" content="校园饮水项目管理云平台" />
        <meta name="description" content="校园饮水项目管理云平台" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!-- basic styles -->
        <link href="/Public/Admin/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="/Public/Admin/css/font-awesome.min.css" />

        <!--[if IE 7]>
          <link rel="stylesheet" href="/Public/Admin/css/font-awesome-ie7.min.css" />
        <![endif]-->

        <!-- page specific plugin styles -->

        <!-- ace styles -->
        <link rel="stylesheet" href="/Public/Admin/css/jquery-ui-1.10.3.custom.min.css" />
        <link rel="stylesheet" href="/Public/Admin/css/chosen.css" />
        <link rel="stylesheet" href="/Public/Admin/css/datepicker.css" />
        <link rel="stylesheet" href="/Public/Admin/css/daterangepicker.css" />
        <link rel="stylesheet" href="/Public/Admin/css/colorpicker.css" />

        <link rel="stylesheet" href="/Public/Admin/css/bootstrap-timepicker.css" />
        <link rel="stylesheet" href="/Public/Admin/css/ace.min.css" />
        <link rel="stylesheet" href="/Public/Admin/css/ace-rtl.min.css" />
        <link rel="stylesheet" href="/Public/Admin/css/ace-skins.min.css" />

        <!--[if lte IE 8]>
          <link rel="stylesheet" href="/Public/Admin/css/ace-ie.min.css" />
        <![endif]-->

        <!-- inline styles related to this page -->

        <!-- ace settings handler -->

        <script src="/Public/Admin/js/ace-extra.min.js"></script>
        <script src="/Public/Admin/js/jquery.min.js"></script>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

        <!--[if lt IE 9]>
        <script src="/Public/Admin/js/html5shiv.js"></script>
        <script src="/Public/Admin/js/respond.min.js"></script>
        <![endif]-->
    </head>


	<body>

		<!-- 头部 -->
		
			<div class="navbar navbar-default" id="navbar">
				<script type="text/javascript">
					try{ace.settings.check('navbar' , 'fixed')}catch(e){}
				</script>
				<div class="navbar-container" id="navbar-container">
					<div class="navbar-header pull-left">
						<a href="<?php echo U('index/index');?>" class="navbar-brand">
							<small>
								<i class="icon-leaf"></i>
								校园饮水项目管理云平台
							</small>
						</a><!-- /.brand -->
					</div><!-- /.navbar-header -->

					<div class="navbar-header pull-right" role="navigation">
						<ul class="nav ace-nav">
							<li>
								<a data-toggle="dropdown" href="#" class="dropdown-toggle">
									<!-- <img class="nav-user-photo" src="/Public/Admin/img/avatars/avatar2.png" /> -->
									<span class="icon-user" ></span>
									<span class="user-info">
										<?php echo (session('username')); ?>
									</span>

									<i class="icon-caret-down"></i>
								</a>
								<ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
									<li>
										<a href="<?php echo U('Setting/index');?>" >
											<i class="icon-cog"></i>
											设置
										</a>
									</li>
									<li>
										<a href="<?php echo U('Setting/account');?>">
											<i class="icon-user"></i>
											个人资料
										</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="<?php echo U('login/login_out');?>">
											<i class="icon-off"></i>
											退出
										</a>
									</li>
								</ul>
							</li>
						</ul><!-- /.ace-nav -->
					</div><!-- /.navbar-header -->
					<div class="navbar-header pull-right guanli" style="display:none;">
						<a href="<?php echo U('setting/index');?>" target="_blank">
							<!-- <i class="icon-list"></i> -->
							<span class="menu-text"> 设置管理 </span>
						</a>				
					</div>>					
				</div><!-- /.container -->
			</div>
		
		<!-- 头部结束 -->

<style>
	.container-fluid{margin-top: 29px;}

</style>
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<div class="container-fluid">
			<!-- 	<a class="menu-toggler" id="menu-toggler11" href="#">    自设应不出现
					<span class="menu-text"></span>
				</a> -->

				<!-- 左边栏开始 -->
				<div class="col-xs-12 col-sm-3 col-lg-2 big-menur" id="sidebar">
					<script type="text/javascript">
						try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
					</script>

					<ul class="nav nav-list" style="margin-bottom:10px;">
						<li id="con_index">
							<a href="<?php echo U('index/index');?>" >
								<!-- <i class="icon-dashboard"></i> -->
								<span class="menu-text"> 控制台 </span>
							</a>
						</li>
						<li class="census" >
							<a href="" class="dropdown-toggle" >
								<!-- <i class="icon-desktop"></i> -->
								<span class="menu-text">学校数据信息统计 </span>
								<b class="arrow"><img class="jiantou" src="/Public/Admin/img/avatars/xiala.png"></b>

								<!-- <span class="glyphicon glyphicon-ok-sign"></span> -->
							</a>
							<ul class="submenu">
								<li class="index">
									<a href="<?php echo U('census/index');?>">
										<!-- <i class="icon-double-angle-right"></i> -->
										学校信息汇总
									</a>
								</li>
								<?php if(is_array($SchoolList)): $i = 0; $__LIST__ = $SchoolList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$schoollist): $mod = ($i % 2 );++$i;?><li class="count_<?php echo ($schoollist["id"]); ?>">
									<a href="<?php echo U('census/cate',array('sid'=>$schoollist['id']));?>">
										<!-- <i class="icon-double-angle-right"></i> -->
										<?php echo ($schoollist["name"]); ?>
									</a>
								</li><?php endforeach; endif; else: echo "" ;endif; ?>
								<li class="school">
									<a href="<?php echo U('school/school_list');?>">
										<!-- <i class="icon-double-angle-right"></i> -->
										学校列表
									</a>
								</li>										
							</ul>
						</li>
						<!--<li class="setting " >
							<a href="<?php echo U('setting/index');?>">
								<i class="icon-list"></i> 
								<span class="menu-text"> 设置管理 </span>
							</a>
						</li>-->
						</ul>
						</li>
					</ul><!-- /.nav-list -->

<!-- 					<div class="sidebar-collapse" id="sidebar-collapse">
						<i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
					</div>
 -->
					<script type="text/javascript">
						try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
					</script>
				</div>
				<!-- 左边栏结束 -->

				<!-- 内容开始 -->
				
<style>
	.shezhi_nav{
		margin:0px;
		border-bottom:1px solid #428bca;
	}
	.zdy-widget-body-table{
		border-top:none !important;
	}
	.zdy-widget-box{
		border:1px solid #ccc;
		border-radius:4px;
	}
</style>
		<div class="col-xs-12 col-sm-9 col-lg-10">
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li>
								<i class="icon-home home-icon"></i>
								学校列表
							</li>
						</ul><!-- .breadcrumb -->	
					</div>


					<ul class="nav nav-pills shezhi_nav">
						<li class="active">
							<a href="<?php echo U('school/school_list');?>">学校列表</a>
						</li>
						<li>
							<a href="<?php echo U('school/school_add');?>">添加学校</a>
						</li>
					</ul>
				
					<!-- 表格 -->
					<div class="row">
					<div class="col-xs-12" style="margin-top:10px;">

						<div class="widget-box zdy-widget-box">
							<div class="widget-body">
								<div class="widget-main padding-8">
									<table id="sample-table-2" class="table table-hover zdy-widget-body-table">
										<thead class="navbar-inner">
											<tr>
												<th>id</th>
												<th>学校名称</th>
												<th>学校id</th>
												<th>接口地址</th>
												<th>操作</th>
											</tr>
										</thead>
										<tbody>
											<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$school): $mod = ($i % 2 );++$i;?><tr>
 												<td><?php echo ($school["id"]); ?></td>
												<td><?php echo ($school["name"]); ?></td>
												<td><?php echo ($school["school_id"]); ?></td>
												<td><?php echo urldecode($school['sdk_adder']);?></td>
												<td> 
													<a href="<?php echo U('school/school_edit',array('id'=>$school['id']));?>">编辑</a> 
													<a href="<?php echo U('school/school_del',array('id'=>$school['id']));?>" onclick=" return confirm('确定删除吗？')">删除</a> 
												</td>
											</tr><?php endforeach; endif; else: echo "" ;endif; ?>
										</tbody>
									</table>
									<!-- 分页 -->
								
									</div>	
							</div>
						</div>


										
					</div>
					</div>
				</div>
		</div>
<script type="text/javascript">
$(document).ready(function(){
		$('input[name=date-range-picker]').daterangepicker().prev().on(ace.click_event, function(){
			$(this).next().focus();
		});

});


</script>

				<!-- 内容结束 -->
				

			</div><!-- /.main-container-inner -->

			
		</div><!-- /.main-container -->




<div class="modal fade in" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none; padding-right: 17px; background-color: rgba(50,50,50,.5);  ">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLabel">已到锁屏时间，请输入账户密码继续操作</h4>
          </div>
          <div class="modal-body">
              <!-- <div class="form-group">
                <label for="recipient-name" class="control-label">账号:</label>
                <input type="text" class="form-control" id="recipient-name" name="username">
              </div> -->
              <div class="form-group">
                <label for="message-text" class="control-label">密码:</label>
                <input type="password" class="form-control" id="lockpwd" name="pwd">
                <span style="color:red" id="msg"></span>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" style="float:left;" class="btn btn-default" data-dismiss="modal" onclick="window.location.href='<?php echo U('login/login_out');?>';">退出登录</button> 
            <button type="button" class="btn btn-primary" id="locklogin" >解锁</button>
          </div>
        </div>
      </div>
    </div>




		<!-- basic scripts -->

		<!--[if !IE]> -->
		
		<!-- <![endif]-->

		<!--[if IE]>

<![endif]-->

		<!--[if !IE]> -->

		<script type="text/javascript">
			window.jQuery || document.write("<script src='/Public/Admin/js/jquery-2.0.3.min.js'>"+"<"+"script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='/Public/Admin/js/jquery-1.10.2.min.js'>"+"<"+"script>");
</script>
<![endif]-->

		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='/Public/Admin/js/jquery.mobile.custom.min.js'>"+"<"+"script>");
		</script>
		<script src="/Public/Admin/js/bootstrap.min.js"></script>
		<script src="/Public/Admin/js/typeahead-bs2.min.js"></script>
		<script src="/Public/Admin/js/jquery.dataTables.min.js"></script>
		<script src="/Public/Admin/js/jquery.dataTables.bootstrap.js"></script>

		<!-- page specific plugin scripts -->

		<!--[if lte IE 8]>
		  <script src="/Public/Admin/js/excanvas.min.js"></script>
		<![endif]-->
		<script src="/Public/Admin/js/jquery-ui-1.10.3.custom.min.js"></script>
		<script src="/Public/Admin/js/jquery.ui.touch-punch.min.js"></script>
		<script src="/Public/Admin/js/jquery.slimscroll.min.js"></script>
		<script src="/Public/Admin/js/jquery.easy-pie-chart.min.js"></script>
		<script src="/Public/Admin/js/jquery.sparkline.min.js"></script>
		<script src="/Public/Admin/js/flot/jquery.flot.min.js"></script>
		<script src="/Public/Admin/js/flot/jquery.flot.pie.min.js"></script>
		<script src="/Public/Admin/js/flot/jquery.flot.resize.min.js"></script>
		<script src="/Public/Admin/js/date-time/bootstrap-datepicker.min.js"></script>
		<script src="/Public/Admin/js/date-time/bootstrap-timepicker.min.js"></script>
		<script src="/Public/Admin/js/date-time/moment.min.js"></script>
		<script src="/Public/Admin/js/date-time/daterangepicker.min.js"></script>
		<!-- ace scripts -->
		<script src="/Public/Admin/js/ace-elements.min.js"></script>
		<script src="/Public/Admin/js/ace.min.js"></script>
		<script type="text/javascript">
		//监听锁屏时间

			var lock = "<?php echo (session('lock')); ?>"; //过期时间
			var time = "<?php echo time();?>"; //当前时间
			var set_lock = '<?php echo (session('setlock')); ?>';  //保存时间*小时
			var locktime = 1000*3600*set_lock ; 

			if(set_lock>0){  // 设置为0时候不设锁屏
				if( time > lock  && lock !='' && time != '' ){
				locking();  //刷新页面后检测时间弹出锁屏
				}else{
					setTimeout('locking()',locktime) ; // 到检测时间弹出锁屏
				}
			}
			//var locktime = 5000;
			function locking(){
				$('#exampleModal').show();
			}
		</script>

		<script type="text/javascript">
			//锁屏框的验证和提交
		 	$('#locklogin').click(function (){
		 		if($('#lockpwd').val() == ''){
		 			$("#msg").html('请输入密码');
		 			$('#lockpwd').focus();
		 		}else{
		 			var url = "<?php echo U('setting/ajax_locklogin');?>";
		 			var pwd = $('#lockpwd').val();
		 			$.post(url,{pwd:pwd},function(data){
		 				if(data){
		 					$('#exampleModal').hide();
		 				}else{
		 					$("#msg").html('密码不正确');
		 					$('#lockpwd').focus();
		 				}
		 			});
		 		}
		 	});
		</script>

		

		<script type="text/javascript">
		//左边菜单栏的选中效果
				var con_name = "<?php echo (CONTROLLER_NAME); ?>";
		     	var act_name = "<?php echo (ACTION_NAME); ?>";
		     	var act_name = act_name.toLowerCase();
		     	var con_name = con_name.toLowerCase();
		     	var sid = "<?php echo ($_GET['sid']); ?>";

				$(document).ready(function() {
			     	
					if( con_name == 'index' && act_name =='index'){ // 首页
						
						$('#con_index').attr('class','active');

					}else if( con_name == 'census' && act_name =='index' ){ //学校汇总

						$('.census').attr('class','active ').find(".index").attr('class','active');
						$('.submenu').show();

					}else if( con_name == 'census' ){ // 大连工业大学

						$('.census').attr('class','active ').find(".count_"+sid).attr('class','active');
						$('.submenu').show();

					}else{  // 其余

						active( con_name );
						//$('#con_index').attr('class','active open');
					}
			    });

			    function active( name ){
			    	$('.submenu').show();
			    	$('.'+name).attr('class','active ').find("."+act_name).attr('class','active');
					
			    }
				
		</script>

</body>
</html>
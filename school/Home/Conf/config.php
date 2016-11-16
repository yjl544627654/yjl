<?php
return array(
	'URL_MODEL'          => '0', //URL模式
	'TMPL_PARSE_STRING' => array(
        '__PUBLIC__' => __ROOT__ . '/Public/',
        '__JS__' => __ROOT__ . '/Public/Admin/js/',
        '__CSS__' => __ROOT__ . '/Public/Admin/css/',
        '__IMG__' => __ROOT__ . '/Public/Admin/img/',
        '__DATA__' => __ROOT__ . '/Data/'

    ),

    'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => 'localhost', // 服务器地址
	'DB_NAME'   => 'school', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => 'fe3a4978a4', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PARAMS' =>  array(), // 数据库连接参数
	'DB_PREFIX' => 'tp_', // 数据库表前缀 
	'DB_CHARSET'=> 'utf8', // 字符集
	'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志

	'TMPL_ACTION_ERROR'     => 'Common:error', //设置 $this->error 的模板
	'TMPL_ACTION_SUCCESS'     => 'Common:success',  //设置$this->success 的模板


	//'SHOW_PAGE_TRACE'     =>  true ,  //开启右下角的图标调试

	'SESSION_AUTO_START' =>ture,   //开启SESSION
);
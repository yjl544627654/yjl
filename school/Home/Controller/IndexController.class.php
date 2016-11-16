<?php
namespace Home\Controller;
use Component\AdminController;

class IndexController extends AdminController {


    function __construct(){

        parent::__construct();
    }
    public function index(){

        $version = D()->query('select version() as ver');

        $upload_size = getRealSize(getDirSize('./Public/upload'));
        $this->assign('system',php_uname());
        $this->assign('php_edition',PHP_VERSION);
        $this->assign('mysql_edition',$version[0]['ver']);
        $this->assign('upload_size', ini_get('upload_max_filesize'));
        $this->assign('server', $_SERVER['SERVER_SOFTWARE']);
        $this->assign('http', $_SERVER["HTTP_HOST"] );
        $this->assign('file_size', $upload_size );
        $this->display();
       
    }
    
}
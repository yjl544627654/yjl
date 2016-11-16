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
    function test(){
        $filename = "http://localhost/yjl/game/123.log";//需要读取的文件
        $tag = "\n";//行分隔符 注意这里必须用双引号
        $count = 100;//读取行数
        $data = $this->readBigFile($filename,$count,$tag);
        echo $data;
    }



        function readBigFile($filename, $count = 20, $tag = "\r\n") {
            $content = "";//最终内容
            $current = "";//当前读取内容寄存
            $step= 1;//每次走多少字符
            $tagLen = strlen($tag);
            $start = 0;//起始位置
            $i = 0;//计数器
            $handle = fopen($filename,'r+');//读写模式打开文件，指针指向文件起始位置
            while($i < $count && !feof($handle)) {
            fseek($handle, $start, SEEK_SET);//指针设置在文件开头
            $current = fread($handle,$step);//读取文件
            $content .= $current;//组合字符串
            $start += $step;//依据步长向前移动
            //依据分隔符的长度截取字符串最后免得几个字符
            $substrTag = substr($content, -$tagLen);
            if ($substrTag == $tag) { //判断是否为判断是否是换行或其他分隔符
            $i++;
            $content .= "<br />";
            }
            }
            //关闭文件
            fclose($handle);
            //返回结果
            return $content;
        }
        
    
}
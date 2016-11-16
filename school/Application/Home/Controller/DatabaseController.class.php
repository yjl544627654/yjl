<?php
namespace Home\Controller;
use Component\AdminController;
class DatabaseController extends AdminController {

    
    public function database_copy(){
        
        $dbtables = M()->query('SHOW TABLE STATUS');
        $total = 0;
        foreach ($dbtables as $k => $v) {
            $dbtables[$k]['size'] = format_bytes($v['data_length'] + $v['index_length']);
            $total += $v['data_length'] + $v['index_length'];
        }
        $this->assign('total', format_bytes($total));
        $this->assign('tableNum', count($dbtables));
        $this->assign('db', $dbtables);
        $this->display();
        
    }
    

    public function database_upload(){

        if($_FILES['myfile']['name']){

            //上传文件
            $file = $_FILES['myfile'];
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     83886080 ;// 设置附件上传大小 80m
            $upload->exts      =     array('sql');// 设置附件上传类型
            $upload->rootPath  =     PUBLIC_PATH.'/sqldata/'; // 设置附件上传根目录
            //$upload->saveName  =     date("Ymd").get_rand_str(3,0); 
            $upload->autoSub   =     false;
            // 上传文件 
            $info   =   $upload->upload();
            if(!$info) {// 上传错误提示错误信息
                $this->error($upload->getError());
            }else{// 上传成功
                $this->success('上传成功！');
            }

        }else{
            //文件
            $size = 0;
            $pattern = "*.sql";
            $filelist = glob(PUBLIC_PATH."/sqldata/".$pattern);
            $fileArray = array();
            foreach ($filelist  as $i => $file) {
                //只读取文件
                if (is_file($file)) {
                    $_size = filesize($file);
                    $size += $_size;
                    $name = basename($file);
                    $pre = substr($name, 0, strrpos($name, '_'));
                    $fileArray[] = array(
                        'name' => $name,
                        'pre' => $pre,
                        'time' => filemtime($file),
                        'size' => format_bytes($_size),
                        'number' => $number,
                    );
                }
            }
            $this->assign('list',$fileArray);
            $this->display();           
        }

    }

    public function copy_ajax(){

        $M = M();
        $tables = I('table',array());
        if (empty($tables)) {
            json_encode(array('stat'=>'ok','msg'=>"操作有误"));
        }
        //防止备份数据过程超时
        function_exists('set_time_limit') && set_time_limit(0);
        send_http_status('310');

        $time = time();//开始时间
        if(!file_exists('./Public/sqldata')){
            mkdir(PUBLIC_PATH.'/sqldata');
        }
        $path = PUBLIC_PATH."/sqldata/tables_" . date("Ymd").get_rand_str(3,0);

        $pre = "# -----------------------------------------------------------\n";
        //取得表结构信息
        //1，表示表名和字段名会用``包着的,0 则不用``
     
        M()->execute("SET SQL_QUOTE_SHOW_CREATE = 1"); 
        $outstr = '';
        foreach ($tables as $table) {
            $outstr.="# 表的结构 {$table} \n";
            $outstr .= "DROP TABLE IF EXISTS `{$table}`;\n";
            $tmp = $M->query("SHOW CREATE TABLE {$table}");
            $outstr .= $tmp[0]['create table'] . " ;\n\n";
        }
        
        $sqlTable = $outstr;
        $outstr = "";
        $file_n = 1;
        $backedTable = array();
        //表中的结构
        foreach ($tables as $table) {
            $backedTable[] = $table;
            $tableInfo = $M->query("SHOW TABLE STATUS LIKE '{$table}'");
            $page = ceil($tableInfo[0]['rows'] / 10000) - 1;
            for ($i = 0; $i <= $page; $i++) {
                $query = $M->query("SELECT * FROM {$table} LIMIT " . ($i * 10000) . ", 10000");
                foreach ($query as $val) {
                    $tn = 0;
                    $temSql = "";
                    foreach ($val as $v) {
                        $temSql.=$tn == 0 ? "" : ",";
                        $str = str_replace('\'','\"',$v) ; 
                        $temSql.=$str == '' ? "''" : "'{$str}'"  ;
                        $tn++;
                    }
                    $temSql = "INSERT INTO `{$table}` VALUES ({$temSql});\n";

                    if ($file_n == 1) {
                        $sqlNo = "# Description:备份的数据表[结构]：" . implode(",", $tables) . "\n".
                                 "# Description:备份的数据表[数据]：" . implode(",", $backedTable) . $sqlNo;
                    }
                    
                    if (strlen($pre) + strlen($sqlNo) + strlen($sqlTable) + strlen($outstr) + strlen($temSql) > C("CFG_SQL_FILESIZE")) {
                        $file = $path . "_" .".sql";
                        $outstr = $file_n == 1 ? $pre . $sqlNo . $sqlTable . $outstr : '';
                        //echo $outstr ; exit;
                        if( !empty($outstr) ){
                            if (!file_put_contents($file, $outstr, FILE_APPEND)) {
                                $this->error("备份文件写入失败！", U('Setting/database_copy'));
                            }
                        }
                        
                        $sqlTable = $outstr = "";
                        $backedTable = array();
                        $backedTable[] = $table;
                        $file_n = 2;
                        //dump($file_n);
                    }
                    $insetsql.=$temSql;

                }
            }
        }

        //存勾选表的数据
        if (strlen($sqlTable . $insetsql) > 0) {
            $sqlNo = "\n# Time: " . date("Y-m-d H:i:s") . "\n" .
                    "# -----------------------------------------------------------\n" ;
            
            $file = $path . "_" .".sql";
            $insetsql = $file_n == 1 ? $pre . $sqlNo . $sqlTable . $insetsql : $pre . $sqlNo . $insetsql;
            //exit($insetsql);
            if (!file_put_contents($file, $insetsql, FILE_APPEND)) {
                json_encode(array('stat'=>'ok','msg'=>"备份失败！"));
            }
        }
        
        $time = time() - $time;
        $file_n = $file_n-1;
        exit(json_encode(array('stat'=>'ok','msg'=>"成功备份数据表，本次备份共生成了{$file_n}个SQL文件。耗时：{$time} 秒")));

        //end
    }

    public function download(){
        $filename = I('get.file');

        if( empty($filename) ){
            $this->error('下载地址不存在');
        }

        $filePath = PUBLIC_PATH.'/sqldata/'.$filename;

        if (!file_exists($filePath)) {
            $this->error("该文件不存在，可能是被删除");
        }
        $filename = basename($filePath);

        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header("Content-Length: " . filesize($filePath));
        readfile($filePath);
    }

    public function del(){

        $filename = I('get.file');
        $filePath = PUBLIC_PATH.'/sqldata/'.$filename;
        if (!file_exists($filePath)) {
            $this->error("该文件不存在");
        }
        if(unlink($filePath)){
            $this->success('删除文件成功！');
        } 

    }

    public function recovery(){

        $files = I('get.file');
        $filePath = PUBLIC_PATH.'/sqldata/'.$files;
        function_exists('set_time_limit') && set_time_limit(0); //防止备份数据过程超时

        if( empty($files) || !file_exists($filePath) ){
            $this->error('下载地址不存在');
        }else{
            
                    //取得上次文件导入到sql的句柄位置
                $position = isset($_SESSION['cacheRestore']['position']) ? $_SESSION['cacheRestore']['position'] : 0;
                $execute = 0;
            
                $file = fopen($filePath, "r");
                $sql = "";
                fseek($file, $position); //将文件指针指向上次位置

                while (!feof($file)) {
                    $tem = trim(fgets($file));
                    //过滤,去掉空行、注释行(#,--)
                    if (empty($tem) || $tem[0] == '#' || ($tem[0] == '-' && $tem[1] == '-'))
                        continue;
                    //统计一行字符串的长度
                    $end = (int) (strlen($tem) - 1);
                    //检测一行字符串最后有个字符是否是分号，是分号则一条sql语句结束，否则sql还有一部分在下一行中  
                   
                   if ($tem[$end] == ";") {
                   $sql .= $tem;

                   M()->execute($sql);
                   $sql = "";
                   $execute++;
                        if ($execute > 500) {
                            $_SESSION['cacheRestore']['position'] = ftell($file);
                            $imported = isset($_SESSION['cacheRestore']['imported']) ? $_SESSION['cacheRestore']['imported'] : 0;
                            $imported += $execute;
                            $_SESSION['cacheRestore']['imported'] = $imported;
                            //echo json_encode(array("status" => 1, "info" => '如果导入SQL文件卷较大(多)导入时间可能需要几分钟甚至更久，请耐心等待导入完成，导入期间请勿刷新本页，当前导入进度：<font color="red">已经导入' . $imported . '条Sql</font>', "url" => U('Database/restoreData', array(get_randomstr(5) => get_randomstr(5)))));
                            $this->success('如果SQL文件卷较大(多),则可能需要几分钟甚至更久,<br/>请耐心等待完成，<font color="red">请勿刷新本页</font>，<br/>当前导入进度：<font color="red">已经导入' . $imported . '条Sql</font>', U('Database/recovery', array('file'=>$files)));
                            exit();
                        }
                    } else {
                        $sql .= $tem;
                    }
                }
                //错误位置结束
                fclose($file);
                unset($_SESSION['cacheRestore']['files'][$fileKey]);
                $position = 0;
                unset($_SESSION['cacheRestore']);
                $this->success("导入成功", U('Database/database_upload'));


            }


    }
}
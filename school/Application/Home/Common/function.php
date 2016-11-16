<?php

// 获取文件夹大小
 function getDirSize($dir)
 { 
  $handle = opendir($dir);
  while (false!==($FolderOrFile = readdir($handle)))
  { 
   if($FolderOrFile != "." && $FolderOrFile != "..") 
   { 
    if(is_dir("$dir/$FolderOrFile"))
    { 
     $sizeResult += getDirSize("$dir/$FolderOrFile"); 
    }
    else
    { 
     $sizeResult += filesize("$dir/$FolderOrFile"); 
    }
   } 
  }
  closedir($handle);
  return $sizeResult;
 }
 // 单位自动转换函数
 function getRealSize($size)
 { 
  $kb = 1024;   // Kilobyte
  $mb = 1024 * $kb; // Megabyte
  $gb = 1024 * $mb; // Gigabyte
  $tb = 1024 * $gb; // Terabyte
  if($size < $kb)
  { 
   return $size." B";
  }
  else if($size < $mb)
  { 
   return round($size/$kb,2)." KB";
  }
  else if($size < $gb)
  { 
   return round($size/$mb,2)." MB";
  }
  else if($size < $tb)
  { 
   return round($size/$gb,2)." GB";
  }
  else
  { 
   return round($size/$tb,2)." TB";
  }
 }


 /**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '') {
  $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
  for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
  return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 获取随机字符串
 * @param int $randLength  长度
 * @param int $addtime  是否加入当前时间戳
 * @param int $includenumber   是否包含数字
 * @return string
 */
function get_rand_str($randLength=6,$addtime=1,$includenumber=0){
    if ($includenumber){
        $chars='abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQEST123456789';
    }else {
        $chars='abcdefghijklmnopqrstuvwxyz';
    }
    $len=strlen($chars);
    $randStr='';
    for ($i=0;$i<$randLength;$i++){
        $randStr.=$chars[rand(0,$len-1)];
    }
    $tokenvalue=$randStr;
    if ($addtime){
        $tokenvalue=$randStr.time();
    }
    return $tokenvalue;
}



function gettime(){
  $time = I('param.date-range-picker','');
  if( empty($time) )
  {
    return false;
  }
  else
  {
    $array = explode('-', $time);
    $time_arr['start'] = strtotime($array[0]);
    $time_arr['end'] = strtotime($array[1].' 23:59:59');

    return $time_arr; 
  } 
}

function get_date(){
    $time = I('param.date-range-picker','');
    if( empty($time) )
    {
      $time_arr['start'] = $time_arr['end'] = date('Y-m-d',time());
    }else{

      $array = explode('-', $time);
      $time_arr['start'] = date('Y-m-d',strtotime($array[0]));
      $time_arr['end'] = date('Y-m-d',strtotime($array[1]));

    }
   
    

    return $time_arr; 
}

function randstr($length=6)
{   
  $hash='';
  $chars= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz@#!~?:-=';   
  $max=strlen($chars)-1;   
  mt_srand((double)microtime()*1000000);   
  for($i=0;$i<$length;$i++){   
  $hash.=$chars[mt_rand(0,$max)];   
  }   
  return $hash;   
}


function set_lock( ){ //刷新锁屏的时间
  $_SESSION['lock'] = time()+3600*$_SESSION['setlock'];
}

function write_log($oper,$mark=null,$admin){ // 记录日志
    $db_log = D('log');
    $data['admin'] = !empty($admin) ? $admin : $_SESSION['username'];
    $data['operate'] = $oper;
    $data['addtime'] = time();

    if( !empty($mark) ){
      $data['mark'] = $mark; 
    }

    if( $res = $db_log->add($data) ){
      return true;
    }
}

function check_pwd( $user,$pwd ){
    $db = D('user');

    $hash = $db->where(array('username' => $user ))->getField('hash');

    $pwd = md5($pwd.$hash);
    $userid = $db->where(array('username'=>$user,'pwd'=>$pwd))->getField('id');
    if( !empty($userid) ){
      return $userid;
    }else{
      return false;
    }
  }

function getlistpage($table,$where,$order,$pagenum=20){
  $db = D($table);

  $db->count();  //总数
  $page = new \Think\Page($count,$pagenum);
  $row['list'] = $db->where($where)->order($order)->limit($page->firstRow,$page->listRows)->select();
  $row['page'] = $page->show();

  return $row ;

}

function post($url,$data){ // curl操作
    $header[] = "Content-type: text/xml";//定义content-type为xml
    $ch = curl_init(); //初始化curl
    curl_setopt($ch, CURLOPT_URL, $url);//设置链接
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//设置HTTP头
    curl_setopt($ch, CURLOPT_POST, 3);//设置为POST方式
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSLVERSION, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//POST数据
    $response = curl_exec($ch);//接收返回信息
    if(curl_errno($ch)){//出错则显示错误信息
       print curl_error($ch);
    }
    curl_close($ch); //关闭curl链接
  return $response;
}



function default_time(){

  $time = date('d/m/Y');
  $time = $time.' - '.$time;
  return $time;
}

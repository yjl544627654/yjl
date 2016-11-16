<?php
namespace Home\Controller;
use Component\AdminController;
class CensusController extends AdminController {
 
    function __construct(){
        parent::__construct();

        //检查权限
        if($_SESSION['power'] != 'all') {
            $power_arr = explode(',',$_SESSION['power']);
            foreach ($power_arr as $key => $v) {
                $arr =  explode('-',$v);

                if( $arr[0]== strtolower(CONTROLLER_NAME) && $arr[1]==strtolower(ACTION_NAME) ){
                    $is_power = true;
                    break; 
                }
            }
            if(!$is_power)   $this->error('您没有权限');
        }

        //预设sid 接口地址
		if(I('get.sid')){
			$_SESSION['sid']  = $_SESSION['sid']!=I('get.sid') ?  I('get.sid') : $_SESSION['sid'] ;
		}
        $this->sid = $_SESSION['sid'] ; //  I('get.sid') ? I('get.sid') : 1 ; // $this->error('错误页面！sid为空')
        $surl = D('school')->where('id='.$this->sid)->getField('sdk_adder');
        //$this->surl = 'http://'.$surl.'.guaziwangluo.com/app/index.php?i=1&c=entry&do=api&m=yinshuiji&';
		$this->surl = $surl.'/app/index.php?i=1&c=entry&do=api&m=yinshuiji&';
    }

    public function index(){ // 学校信息汇总 , 数据汇总

    	$school_list = D('school')->select();
    	foreach ($school_list as $key => $v) {

            //$url = 'http://'.$v['sdk_adder'].'.guaziwangluo.com/app/index.php?i=1&c=entry&do=api&m=yinshuiji&' ;
			$url = $v['sdk_adder'].'/app/index.php?i=1&c=entry&do=api&m=yinshuiji&' ;

    		$list[$key]['schoolname'] = $v['name'] ;
    		$list[$key]['xf'] = $this->collect($url.'op=TypeCspc'/*.$v['sdk_adder']*/); //消费金额 消费次数
    		$list[$key]['cz'] = $this->collect($url.'op=RechcsCspc'/*.$v['sdk_adder']*/);// 充值金额  充值次数
    		$list[$key]['jxgz'] = $this->collect($url.'op=JXGZCspc'/*.$v['sdk_adder']*/); // 机械故障次数
    		$list[$key]['jxwh'] = $this->collect($url.'op=JXYWCspc'/*.$v['sdk_adder']*/); // 机械维护次数

    		$list[$key]['xf_money'] = $list[$key]['xf']['CONMON2']+$list[$key]['xf']['CONMON1']+$list[$key]['xf']['CONMON3'] ; //消费金额
    		$list[$key]['xf_num'] = $list[$key]['xf']['CONNUM1']+$list[$key]['xf']['CONNUM2']+$list[$key]['xf']['CONNUM3'] ; // 消费次数

    		//计算总数
    		$count['xf_money'] = $count['xf_money']+$list[$key]['xf_money'];
    		$count['xf_num'] = $count['xf_num']+$list[$key]['xf_num'];
    		$count['cz_num'] = $count['cz_num']+$list[$key]['cz']['CONNUM'];
    		$count['cz_money'] = $count['cz_money']+$list[$key]['cz']['CONMON'];
    		$count['jxgz'] = $count['jxgz']+$list[$key]['jxgz'];
    		$count['jxwh'] = $count['jxwh']+$list[$key]['jxwh'];

            //后台地址
            $list[$key]['admin_adder'] = $v['admin_adder'] ; 
    	}
    	$this->assign('count',$count);
    	$this->assign('list',$list);
        $this->display();
    }

    private function collect($url ){
        $data = array(
            "TIME"=>"123456789",
            'CDTTIME'=>array(
                'start'=>"2016-01-01" ,
                'end'=> date('Y-m-d',time() )
            )
        );
        $res =  json_decode( post($url,json_encode($data) ) ,true );

        if( array_key_exists('PTOTAL', $res['DTALIST']) ){
            return $res['DTALIST']['PTOTAL'];
            die();
        }
        //var_dump($res);
        return $res['DTALIST'];
        
    }

    public function cate(){ //分类信息查询

        $time = get_date();
        
    	$data = array(
            "TIME"=>"123456789",
    		'CDTTIME'=>array(
    			'start'=>$time['start'] ,
    			'end'=>$time['end']
    		)
    	);
        $data['HBTKEY'] = $this->hbtkey();
		
        $url = $this->surl.'op=TypeCspc';
		
        $res = post($url,json_encode($data));  
		//var_dump($res);
    	$res = json_decode($res,true);
		
    	$this->assign('count',$res['DTALIST']);
      
        $this->display();
    	
    }

    public function machine_shend(){ //机械消费
    	$time = get_date();
    		
		$p =  !empty($_GET['p']) ? $_GET['p'] : 1;
		$data = array(
    		'HBTKEY'=> $this->hbtkey(),
    		'CDTTIME'=>array(
    			'start'=>$time['start'] ,
    			'end'=>$time['end']
    		),
    		'PINDEX'=>$p // 第几页
    	);
        
		$url = $this->surl.'op=MachCspc';
		$res = post($url,json_encode($data));
		$res = json_decode($res,true);

		//分页
		//$count = count($res['DTALIST']['CONLIST']);
		$page = new \Think\Page($res['DTALIST']['PTOTAL'],30);

		//平均值
		
		$ave['num'] =  $res['DTALIST']['AERNUM'] ; 
		$ave['money'] = $res['DTALIST']['AERMON'];
        //var_dump($res);
		$this->assign('ave',$ave);
		$this->assign('page',$page->show());
		$this->assign('datlist',$res['DTALIST']['CONLIST']);

    	
    	$this->display();
    }

   	public function group_shend(){ //组消费查询
   		$time = get_date();

		$data = array(
		'HBTKEY'=>$this->hbtkey(),
		'CDTTIME'=>array(
			'start'=>$time['start'] ,
			'end'=>$time['end']
    		)
    	);
		$url = $this->surl.'op=GroupCspc';
		$res = json_decode( post($url,json_encode($data)) ,ture);

		$this->assign('group',$res['DTALIST']);
    	$this->display();
    }

    public function icon_count(){ //图标信息查询

    	$time = get_date();

    	$data = array(
    		'HBTKEY'=>$this->hbtkey(),
    		'CDTTIME'=>array(
    			'start'=>$time['start'] ,
    			'end'=>$time['end']
    		)
    	);
		$url = $this->surl.'op=CHWCspc';
		$res = json_decode( post($url,json_encode($data)) ,ture);
		$this->assign('icon',$res['DTALIST']);

        $this->display();
    }
    public function recharge_num(){ // 充值次数

    	$time = get_date();

    	$data = array(
    		'HBTKEY'=>$this->hbtkey(),
    		'CDTTIME'=>array(
    			'start'=>$time['start'] ,
    			'end'=>$time['end']
    		)
    	);
		$url = $this->surl.'op=RechcsCspc';
		$res = json_decode( post($url,json_encode($data)) ,ture);
		
		$this->assign('recharge',$res['DTALIST']);
    	$this->display();
    }

	public function recharge_phase(){

    	$time = get_date();

		$data = array(
    		'HBTKEY'=>$this->hbtkey(),
    		'CDTTIME'=>array(
    			'start'=>$time['start'] ,
    			'end'=>$time['end']
    		)
    	);
		$url = $this->surl.'op=RechjdCspc';
		$res = json_decode( post($url,json_encode($data)) ,ture);

		$this->assign('phase',$res['DTALIST']);
    	$this->display();
    }

    public function count_recharge(){ // 总体充值信息
        $time = get_date();

        $data = array(
            'HBTKEY'=>$this->hbtkey(),
            'CDTTIME'=>array(
                'start'=>$time['start'] ,
                'end'=>$time['end']
            )
        );
        $url = $this->surl.'op=ZTRechCspc';
        $res = json_decode( post($url,json_encode($data)) ,ture);

        $this->assign('time',$time);        
        $this->assign('count',$res['DTALIST']);
    	$this->display();
    }

    public function program(){ // 活动信息

        $time = get_date();
        if(!empty($time) ){
            $p = I('get.p') ?　Ｉ('get.p') : 1;
            $data = array(
                'HBTKEY'=>$this->hbtkey(),
                'CDTTIME'=>array(
                    'start'=>$time['start'] ,
                    'end'=>$time['end']
                ),
                "PINDEX"=>$p // 页数
            );
            $url = $this->surl.'op=ActCspc';
            $res = json_decode( post($url,json_encode($data)) ,ture);
			
            //分页
            $page = new \Think\Page($res['DTALIST']['PTOTAL'],30);

            $this->assign('page',$page->show());
            $this->assign('porgram',$res['DTALIST']['CONLIST']);
        }
        
    	$this->display();
    }

    public function recharge(){ // 充值信息查询

       $time = get_date();
        if(!empty($time) ){

            $data = array(
                'HBTKEY'=>$this->hbtkey(),
                'CDTTIME'=>array(
                    'start'=>$time['start'] ,
                    'end'=>$time['end']
                )
            );
            $url = $this->surl.'op=RechCspc';
            $res = json_decode( post($url,json_encode($data)) ,ture);

            $this->assign('info',$res['DTALIST']);
        }

        
        $this->display('recharge_money');
        
    }

    public function machine_operation(){ //机械运维考勤

        $p = I('get.p') ? I('get.p') :1 ;

        $data['HBTKEY'] = $this->hbtkey();
        $data['PINDEX'] = $p;

    	if( I('get.repname')){
            $data['REPNAME'] = I('get.repname') ;
    	}
		if( I('get.date-range-picker')){
			$time = get_date();
			$data['CDTTIME'] = array(
					'start'=>$time['start'] ,
					'end'=>$time['end']
				);
		}
		
        $url = $this->surl.'op=JXYWCspc';
        $res = json_decode( post($url,json_encode($data)) ,ture);
		
        //分页
        $count = $res['DTALIST']['PTOTAL'];
        $page = new \Think\Page($count,30);
            
        if( $_GET['upload'] ){
            $title = array('序号','中控设备号','水机号','',' 备注','维修人姓名',' 备注','登记时间','位置') ;
            exportexcel($res['DTALIST']['LISTS'] ,$title  ); // 导出excl操作
        }
        $this->assign('count',$count);
        $this->assign('page',$page->show());
        $this->assign("oper",$res['DTALIST']['LISTS']);
    	$this->display();
    }

    public function machine_fault(){ // 机械故障信息
        $time = get_date();

        $p = I('get.p') ? I('get.p'): 1;
		$data = array(
            'HBTKEY'=>$this->hbtkey(),
            'CDTTIME'=>array(
                'start'=>$time['start'] ,
                'end'=>$time['end']
            ),
            'PINDEX'=>$p,
        );
        $url = $this->surl.'op=JXGZCspc';
        $res = json_decode( post($url,json_encode($data)) ,ture);
        //分页
        $count = $res['DTALIST']['PTOTAL'];
        $page = new \Think\Page($count,30);

        $this->assign('count',$count);
        $this->assign('page',$page->show());
        $this->assign('fault',$res['DTALIST']['LISTS']);
            		
    	$this->display();
    }

    public function overdraft_card(){  //透支卡信息查询
        $time = get_date();
		$stype = I('get.stype');
        $stype = I('get.stype');
        $p = I('get.p') ? I('get.p'): 1;
        $data = array(
            'HBTKEY'=>$this->hbtkey(),
            'CDTTIME'=>array(
                'start'=>$time['start'] ,
                'end'=>$time['end']
            ),
            'PINDEX'=>$p,
        );
		if($stype) $data['STYPE'] = $stype;
		
        $url = $this->surl.'op=QFKCspc';
        $res = json_decode( post($url,json_encode($data)) ,ture);

        //分页
        $listcount = count($res['DTALIST']['LISTS']);
        $count = $res['DTALIST']['PTOTAL'];
        $page = new \Think\Page($count,$listcount);
	
        $this->assign('page',$page->show());
        $this->assign('overdraft',$res['DTALIST']['LISTS']);
    	$this->display();
    }

    public function arrears_card(){ //欠费卡
        $time = get_date();

        $p = I('get.p') ? I('get.p'): 1;
        $data = array(
            'HBTKEY'=>$this->hbtkey(),
            'CDTTIME'=>array(
                'start'=>$time['start'] ,
                'end'=>$time['end']
            ),
            'PINDEX'=>$p
        );
        $url = $this->surl.'op=QFKCspc';
        $res = json_decode( post($url,json_encode($data)) ,ture);


        //分页
        $listcount = count($res['DTALIST']['LISTS']);
        $count = $res['DTALIST']['PTOTAL'];
        $page = new \Think\Page($count,30);

        $this->assign('page',$page->show());
        $this->assign('arrears',$res['DTALIST']['LISTS']);
    	$this->display();
    }
   


    function hbtkey($url=''){ // 心跳包密钥 验证

    	return '123456789';

    	// 密匙的获取 1 TIME 2 HBTKEY ？ 
    	/*$op = substr(strchr($url,'op'),3) ;
    	$key = base_convert($op,16, 10)*2-1;
    	$key.='zjyc98855'.time();
    	$key = substr(md5($key),9,8);*/

	  	
	}

}
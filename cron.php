<?php
/*
 * By Giuem.
 */
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
date_default_timezone_set('PRC');
include 'config.php';
require_once dirname(__FILE__).'/./function/core.php';
require_once dirname(__FILE__).'/./function/robot.php';
//判断BDUSS是否失效
if(!isLogin($bduss)){
	$sql = "TRUNCATE TABLE pid";
	$conn->query($sql);
	ShowMsg('BDUSS失效！');
	return ;
}

$type=array('reply','at');
$lastpid = array(getSQLPid($type[0]),getSQLPid($type[1])); 
//判断数据库中是否记录PID
if($lastpid[0] == '' or $lastpid[1] == ''){
    $nowpid = array(getLastPid($bduss,$type[0]),getLastPid($bduss,$type[1]));
	$sql="INSERT INTO pid(reply,at) values ($nowpid[0],$nowpid[1])";
	$conn->query($sql);
	return ;
}

for($i=0;$i<2;$i++){
	$data = getMessages($bduss,$type[$i]);
	for($a=0;$a<count($data);$a++){
		$d=$data[$a];
		//判断是否有新回复
		if($lastpid[$i] == $d['post_id']){
			break;
		}else {
			updateSQLPid($data[0]['post_id'],$type[$i]);
		}
        //判断是否为楼中楼
        if($d['is_floor'] == 1){
			$pid = getFloorPid($b,$d['thread_id'],$d['post_id']);
			$content = '回复 '.$d['replyer']['name_show'].' :'.talk($d['content']);
			$re = reply($bduss,$d['thread_id'],$pid,$content,$d['fname']);
            setLog('TID：'.$d['thread_id'].',状态：'.$re);
		}else {
            $content = talk($d['content']);
			$re = reply($bduss,$d['thread_id'],$d['post_id'],$content,$d['fname']);
			setLog('TID：'.$d['thread_id'].',状态：'.$re);	
		}
		sleep(8);
   	}
}
echo 'over';
?>
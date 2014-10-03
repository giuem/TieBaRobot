<?php
header("Content-type: text/html; charset=utf-8");
require dirname(__FILE__).'/init.php';
checkcookie();
if (!defined('IS_ADMIN')) exit();

if ($_GET['do']=='dellog'){
	if(del_log()===true)
		echo '删除成功';
	else 
		echo '删除失败';
	return ;
}
if ($_GET['do']=='addrobot'){
	if (empty($_GET['bduss'])) return ;
	$name = tieba::getun($_GET['bduss']);
	if (tieba::islogin($_GET['bduss'])===false){
		echo '无效的BDUSS';
	}elseif (empty($name)){
		echo '无法获取百度ID';
	}else {
		$robot_list = get_robot_list();
		if (empty($robot_list)){
			set_pid(tieba::getpid($_GET['bduss'], 'reply'), 'reply');
			set_pid(tieba::getpid($_GET['bduss'], 'at'), 'at');
		}
		set_new_robot($name, $_GET['bduss']);
		echo '添加成功';
	}
	return ;
}

if ($_GET['do']=='delrobot'){
	if ($_GET['id']==1){
		echo '主机器人不能删除，只能修改';
		return ;
	}else {
		if(del_robot($_GET['id'])===true)
			echo '删除成功';
		else 
			echo '删除失败';
		}
	return ;
}

if ($_GET['do']=='editrobot'){
	if (empty($_GET['bduss'])){
		echo '请输入BDUSS';
		return ;
	}elseif (empty($_GET['id'])){
		echo '请输入ID';
		return ;
	}elseif (tieba::islogin($_GET['bduss'])===false){
		echo '无效的BDUSS';
		return ;
	}else {
		$name = tieba::getun($_GET['bduss']);
		if (empty($name)){
			echo '无法获取百度ID';
			return ;
		}
		if($_GET['id']==1) {
			set_pid(tieba::getpid($_GET['bduss'], 'reply'), 'reply');
			set_pid(tieba::getpid($_GET['bduss'], 'at'), 'at');
		}
		if(set_robot($_GET['id'],$name, $_GET['bduss'])===true)
			echo '更新成功';
		else 
			echo '更新失败';
	}
}
/*PHP END*/
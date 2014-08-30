<?php
//设置BDUSS
$bduss='xx';       //不需要带BDUSS= 
//机器人的名字 如 TB小冰
$name = ' TB小冰';
/*
 * 关于api说明
 * xiaoji：就是原来的接口，可以调教的
 * tuling：图灵机器人的接口，主要是查询牛逼，需要填写key
 * xiaoi3：第三方小i接口
 * simsimi3：第三方小i接口，速度快一些
 * simsimi：官方接口，速度慢，可能失效
 * 想用什么就在下面写上
 */
$api='xiaoji';
if(defined('SAE_MYSQL_DB')) {
// ------------------ SAE数据库设定 ------------------
	$db_server = SAE_MYSQL_HOST_M;						// 已自动设置好，无需干预
	$db_port = SAE_MYSQL_PORT;
	$db_username = SAE_MYSQL_USER;
	$db_password = SAE_MYSQL_PASS;
	$db_name = SAE_MYSQL_DB;
// -------------- END SAE数据库设定 ------------------
}else {
// ------------------ 非SAE数据库设定 ------------------
	$db_server = '';			// 数据库服务器地址
	$db_port = '';				// 数据库端口
	$db_username = '';			// 数据库用户名
	$db_password = '';			// 数据库密码
	$db_name = '';				// 数据库名
// -------------- END 非SAE数据库设定 ------------------
}
?>
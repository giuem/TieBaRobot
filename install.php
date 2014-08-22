<?php
/* 
 * By Giuem.
 * 本页面请在第一次使用时打开
 */
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
include "./config.php";
require_once dirname(__FILE__).'/./function/core.php';
$sqlarr = array(
	"CREATE TABLE pid
(
  reply varchar(32),
  at varchar(32)	
)",
	"CREATE TABLE log
(
  time varchar(64),
  log varchar(64)
)"
);
foreach($sqlarr as $sql){
	if(!$conn->query($sql)){
		$errmsg = "创建数据库表失败：" . $conn->error;
		ShowMsg($errmsg, '');
		return;
	}
}
$errmsg = "创建数据库表成功";
ShowMsg($errmsg);

?>
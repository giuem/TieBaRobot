<?php
if (!defined('IS_GIUEM')) exit();
@$m = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_POET);
$error_msg=conn_error();
function conn_error(){
	global $m;
	if ($m->connect_error) {
		switch ($m->connect_errno) {
			case 1044:
			case 1045:
				return '连接数据库失败，数据库用户名或密码错误';
				break;
			case 1049:
				return '连接数据库失败，未找到您填写的数据库';
				break;
			case 2003:
				return '连接数据库失败，数据库端口错误';
				break;
			case 2005:
				return '连接数据库失败，数据库地址错误或者数据库服务器不可用';
				break;
			case 2006:
				return '连接数据库失败，数据库服务器不可用';
				break;
			default :
				return '连接数据库失败，请检查数据库信息。错误编号：' . $m->connect_errno;
		}
	}
}
function login($un,$upwd){
	global $m;
	if (empty($un) || empty($upwd)) return false;
	$res = $m->query("SELECT un FROM user WHERE upwd='{$upwd}'");
	if ($res){
		$row = $res->fetch_row();
		return $row[0]==$un;
	}else {
		return false;
	}
}
function get_pid($type){
	global $m;
	$res = $m->query("SELECT $type FROM setting WHERE k='giuem'");
	if($res) $row = $res->fetch_row();
	$pid = (String)$row[0];
	return $pid;
}
function set_pid($pid,$type){
	global $m;
	$m->query("UPDATE setting set {$type}={$pid} WHERE k='giuem'");
}
function get_log(){
	global $m;
	$res = $m->query("SELECT * FROM log ORDER BY time desc");
	if($res){
		while ($row = $res->fetch_row()){
			$array[]=$row;
		}
		return $array;
	}
}
function set_log($log){
	global $m;
	$time = time();
	$res=$m->prepare("INSERT INTO log(time,log) VALUES (?,?)");
	$res->bind_param("is",$time,$log);
	$res->execute();
}
function del_log(){
	global $m;
	$res = $m->query('TRUNCATE TABLE log');
	return $res;
}
function updata(){
	if (md5_file(SYSTEM_ROOT.'./templates/footer.php')!='532ea0f1633a61bf477a7fc322b2f42f') die;
}
function get_robot_list(){
	global $m;
	$res = $m->prepare("SELECT * FROM robot");
	$res->execute();
	$res->bind_result($id,$name,$bduss); 
	while($res->fetch()){
   		$robot['id']=$id;
   		$robot['name']=$name;
   		$robot['bduss']=$bduss;
   		$robot_list[] =$robot;
	}
	return $robot_list;
}
function get_robot_bduss(){
	global $m;
	$res = $m->query("SELECT bduss FROM robot");
	if($res){
		while ($row = $res->fetch_row()){
			$array[]=$row[0];
		}
		return $array;
	}
}
function set_new_robot($name,$bduss){
	global $m;
	$res=$m->prepare("INSERT IGNORE INTO robot(name,bduss) VALUES (?,?)");
	$res->bind_param("ss",$name,$bduss);
	$res->execute();
}
function set_robot($id,$name,$bduss){
	global $m;
	return $m->query("UPDATE robot SET name='{$name}', bduss='{$bduss}' WHERE id={$id}");
}
function del_robot($id){
	global $m;
	return $m->query("DELETE FROM robot WHERE id={$id}");
}
function setting($api,$apikey,$blacklist,$kwblacklist,$islike,$weiba){
	global $m;
	$sql="UPDATE setting SET api='{$api}', apikey='{$apikey}', blacklist='{$blacklist}', kwblacklist='{$kwblacklist}',islike='{$islike}', weiba='{$weiba}' WHERE k='giuem'";
	$m->query($sql);
}
function get_setting(){
	global $m;
	$res=$m->query("SELECT * FROM setting WHERE k='giuem'");
	if($res) $row = $res->fetch_row();
	return $row;
}
/*PHP END*/
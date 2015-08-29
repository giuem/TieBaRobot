<?php
if (!defined('IS_GIUEM')) exit();
if(defined('SAE_MYSQL_DB')) {
	define('DB_SERVER',SAE_MYSQL_HOST_M);
	define('DB_POET',SAE_MYSQL_PORT);
	define('DB_USERNAME',SAE_MYSQL_USER);
	define('DB_PASSWORD',SAE_MYSQL_PASS);
	define('DB_NAME',SAE_MYSQL_DB);
}else {
	include SYSTEM_ROOT.'./config.php';
}
function logout(){
	setcookie('un','',time()-315360000);
	setcookie('upwd','',time()-315360000);
}
function checkcookie(){
	if(login($_COOKIE['un'], $_COOKIE['upwd'])===true){
		define('IS_ADMIN', true);
	}
}
function fetch($url,$cookie=null,$postdata=null,$header=array()){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	if (!is_null($postdata)) curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
	if (!is_null($cookie)) curl_setopt($ch, CURLOPT_COOKIE,$cookie);
	if (!empty($header)) curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	//curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	$re = curl_exec($ch);
	curl_close($ch);
	return $re;
}
function random($length = 0){
	for($i=1;$i<=$length;$i++){
		$re .= rand(0,9);
	}
	return $re;
}
/*PHP END*/
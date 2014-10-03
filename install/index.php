<?php 
define('IS_GIUEM', true);
define('SYSTEM_ROOT', dirname(__FILE__).'/');
$config_file = SYSTEM_ROOT.'../config.php';
if(file_get_contents($config_file)){
	header('Location: ..');
	exit();
}elseif (defined('SAE_ACCESSKEY')){
	$m = new mysqli(SAE_MYSQL_HOST_M, SAE_MYSQL_USER, SAE_MYSQL_PASS, SAE_MYSQL_DB, SAE_MYSQL_PORT);
	$res = $m->query("SELECT * FROM user");
	if($res) $row = $res->fetch_row();
	if(!empty($row)){
		header('Location: ..');
		exit();
	}
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>贴吧机器人 - 安装向导</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="stylesheet" href="http://cdn.staticfile.org/amazeui/1.0.0-beta2/css/amazeui.basic.min.css">
  <script src=".././templates/js/zepto.min.js"></script>
  <script src="http://cdn.staticfile.org/amazeui/1.0.0-beta2/js/amazeui.min.js"></script>
</head>
<body>
<div class="am-g">
<div class="am-panel am-panel-default">
<div class="am-panel-bd">
<h1>贴吧机器人 - 安装向导</h1>
<?php 
switch($_GET['step']){
	default:
		if(defined('SAE_ACCESSKEY')){
			$content = '检测到为SAE环境已配置完成，请进入下一步<br /><a class="am-btn am-btn-success" href="./?step=2">下一步</a> ';
		}elseif (getenv('OPENSHIFT_APP_NAME')){
			$content = '检测到为OPENSHIFT环境，请进入下一步<br /><a class="am-btn am-btn-success" href="./?step=2">下一步</a> ';
		}else{
			$content .='<p>请保证每一个项目都支持，否则不能正常使用</p><table class="am-table am-table-striped am-table-hover"><thead><tr><th>项目</th><th>检测结果</th></tr></thead><tbody>';
			$content .='<tr><td>CURL</td><td>'.check(function_exists('curl_init')).'</td></tr>';
			$content .='<tr><td>MYSQLi</td><td>'.check(class_exists('mysqli')).'</td></tr>';
			$content .='<tr><td>config.php是否可编辑</td><td>'.check(is_writable($config_file)).'（如不可写请手动编辑）</td></tr>';
			if (function_exists('curl_init') && class_exists('mysqli')) $content .='</tbody></table><a class="am-btn am-btn-success" href="./?step=2">下一步</a>';
		}
		echo $content;
		break ;
	case 2:
		$content .='<form method="post" action="./?step=3" class="am-form am-form-horizontal">';
		if (!defined('SAE_ACCESSKEY') && !getenv('OPENSHIFT_APP_NAME')){
			$content .='<div class="am-form-group"><label class="col-sm-4 am-form-label">数据库服务器地址:</label><div class="col-sm-5 col-end"><input type="text" name="db_server" value="localhost"></div></div>';
			$content .='<div class="am-form-group"><label class="col-sm-4 am-form-label">数据库端口:</label><div class="col-sm-5 col-end"><input type="text" name="db_port" value="3306"></div></div>';
			$content .='<div class="am-form-group"><label class="col-sm-4 am-form-label">数据库用户名:</label><div class="col-sm-5 col-end"><input type="text" name="db_username" ></div></div>';
			$content .='<div class="am-form-group"><label class="col-sm-4 am-form-label">数据库密码:</label><div class="col-sm-5 col-end"><input type="password" name="db_password"></div></div>';
			$content .='<div class="am-form-group"><label class="col-sm-4 am-form-label">数据库名:</label><div class="col-sm-5 col-end"><input type="text" name="db_name" ></div></div>';
		}
		$content .='<div class="am-form-group"><label class="col-sm-4 am-form-label">管理员帐号:</label><div class="col-sm-5 col-end"><input type="text" name="un" ></div></div>';
		$content .='<div class="am-form-group"><label class="col-sm-4 am-form-label">管理员密码:</label><div class="col-sm-5 col-end"><input type="password" name="upwd" ></div></div>';
		$content .='<div class="am-form-group"><div class="col-sm-offset-4 col-sm-8"><button type="submit" class="am-btn am-btn-default">确认</button></div></div></form>';
		echo $content;
		break;
	case 3:
		if (defined('SAE_ACCESSKEY')){
			define('DB_SERVER',SAE_MYSQL_HOST_M);
			define('DB_POET',SAE_MYSQL_PORT);
			define('DB_USERNAME',SAE_MYSQL_USER);
			define('DB_PASSWORD',SAE_MYSQL_PASS);
			define('DB_NAME',SAE_MYSQL_DB);
		}elseif (getenv('OPENSHIFT_APP_NAME')){
			define('DB_SERVER',getenv('OPENSHIFT_MYSQL_DB_HOST'));
			define('DB_POET',intval(getenv('OPENSHIFT_MYSQL_DB_PORT')));
			define('DB_USERNAME',getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
			define('DB_PASSWORD',getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
			define('DB_NAME',getenv('OPENSHIFT_APP_NAME'));
		}else{
			define('DB_SERVER',$_POST['db_server']);
			define('DB_POET',$_POST['db_port']);
			define('DB_USERNAME',$_POST['db_username']);
			define('DB_PASSWORD',$_POST['db_password']);
			define('DB_NAME',$_POST['db_name']);
		}
		require SYSTEM_ROOT.'../lib/func.mysqli.php';
		if($error_msg){
			 showmsg($error_msg);
			 return;
		}
		$config_content="<?php
define('DB_SERVER','".DB_SERVER."');
define('DB_POET',".DB_POET.");
define('DB_USERNAME','".DB_USERNAME."');
define('DB_PASSWORD','".DB_PASSWORD."');
define('DB_NAME','".DB_NAME."');";
		if(file_put_contents($config_file,$config_content)<= 0){
			showmsg('哎呀，无法写入文件！请手动配置config.php（sae环境请忽略本条消息）',1);
		}
		$un = $_POST['un'];
		$upwd = $_POST['upwd'];
		if(!$un || !$upwd) {
			showmsg('您输入的信息不完整');
			return ;
		}
		$upwd = md5($upwd);
		$sql = file_get_contents(dirname(__FILE__).'/install.sql');
		$sql .= PHP_EOL."INSERT INTO user SET un='{$un}', upwd='{$upwd}';";
		$m->multi_query($sql);
		echo '<p>安装完成，请配置机器人信息，然后设置 cron.php 每分钟一次的计划任务</p>';
		echo '<a class="am-btn am-btn-success" href="../index.php">完成</a>';
}


function check($c){
	if($c)
		return '支持';
	else 
		return '不支持';
}
function showmsg($msg,$type=0){
	$content = "<div class=\"am-alert am-alert-danger\"><h4><i class=\"am-icon-warning\">Warning</h4></i>{$msg}</div>";
	if ($type = 0) $content .='<button type="button" class="am-btn am-btn-primary" onclick="history.back();">&laquo; 返回</button>';
	echo $content;
}

?>
</div>
</div>
</div>
</body>
</html>
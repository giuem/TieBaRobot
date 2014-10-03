<?php 
require 'init.php';
if ($_POST){
	if(login($_POST['un'], md5($_POST['upwd']))===true){
		define('IS_ADMIN', true);
		setcookie('un',$_POST['un'],time()+315360000);
		setcookie('upwd',md5($_POST['upwd']),time()+315360000);
	}else {
		$msg = '登陆失败';
	}
}else {
	checkcookie();
}
if (defined('IS_ADMIN')){
	header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title>贴吧机器人助手登陆</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="format-detection" content="telephone=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="stylesheet" href="http://cdn.staticfile.org/amazeui/1.0.0-beta2/css/amazeui.basic.min.css"/>
</head>
<body>
<br>
<div class="am-g">
  <div class="col-lg-4 col-md-8 col-sm-centered">
  <h1 class="am-text-center">贴吧机器人助手登陆</h1>
<form action="login.php" method="post" class="am-form am-form-horizontal">
  <div class="am-form-group">
    <label class="col-sm-3 am-form-label">帐号</label>
    <div class="col-sm-9">
      <input type="text" name="un" id="un">
    </div>
  </div>

  <div class="am-form-group">
    <label class="col-sm-3 am-form-label">密码</label>
    <div class="col-sm-9">
      <input type="password" name="upwd" id="upwd">
    </div>
  </div>
  <div class="am-form-group">
    <div class="col-sm-offset-3 col-sm-9">
      <button type="submit" class="am-btn am-btn-primary am-btn-block" >登陆</button>
    </div>
  </div>
</form>
  <?php if ($msg) echo '<div class="am-animation-shake am-animation-reverse am-text-danger am-text-center">'.$msg.'</div>';?>
  </div>
</div>
</body>
</html>
<?php
include 'config.php';
require_once dirname(__FILE__).'/./function/core.php';
?>
<!DOCTYPE html>
<html>
<head>
<title>贴吧机器人助手</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
</head>
<body>
<!-- 请不要乱改源码 ，影响正常使用！  -->
Designed by <a href="http://www.giuem.com">Giuem</a><br />
<form name="input" action="index.php" method="post">
<input type="hidden" name="action" value="delete" />
<input type="submit" value="清空记录"/>  
</form>
回帖记录：<br /> 
<?php
//删除记录
if($_POST['action'] =='delete'){
	$sql = "TRUNCATE TABLE log";
	$conn->query($sql);
	echo "<script type='text/javascript'>history.back();</script>";
}
//输出记录
$sql = "SELECT * FROM log";
$re = $conn->prepare($sql);
$re->execute();
$re->bind_result($time,$log); 
while($re->fetch()){
    echo $time.' '.$log.'<br />';
}
?>
</body>
</html>

<?php
if (!defined('IS_GIUEM')) exit();
class ui{
	public static function show(){
		if($_GET['action']=='logout'){
			logout();
			header('Location: login.php');
		}
		include SYSTEM_ROOT.'/templates/header.php';
		$file=SYSTEM_ROOT.'/templates/'.$_GET['action'].'.php';
		if(file_exists($file))
			include $file;
		else 
			include SYSTEM_ROOT.'/templates/index.php';
		include SYSTEM_ROOT.'/templates/footer.php';
	}
}
/*PHP END*/
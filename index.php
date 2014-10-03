<?php 
require dirname(__FILE__).'/init.php';
checkcookie();
if (!defined('IS_ADMIN')){
	header('Location: login.php');
	exit();
}
ui::show();
/*PHP END*/
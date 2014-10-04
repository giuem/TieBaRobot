<?php
error_reporting(NULL);
ini_set('display_errors','Off');
define('IS_GIUEM', true);
define('SYSTEM_ROOT', dirname(__FILE__).'/');
date_default_timezone_set('PRC');
require SYSTEM_ROOT.'/lib/class.tieba.php';
require SYSTEM_ROOT.'/lib/commom.php';
require SYSTEM_ROOT.'/lib/ui.php';
require SYSTEM_ROOT.'/lib/api.php';
require SYSTEM_ROOT.'/lib/func.mysqli.php';
/*PHP END*/
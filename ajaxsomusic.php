<?php
include_once('./common.php');

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

include_once(S_ROOT.'./source/function_music.php');

if($_GET["type"] && $_GET["q"])
{
	echo "new Array(".substr(getmusiclist(-1,-1,-1,-1,-1,-1,-1,-1,-1,$_GET["q"]),0,-1).");";
}
?>
<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: cleanfeed.php 12681 2009-07-15 05:24:47Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//执行
$sql = "SELECT lid FROM ".tname('app_laba')." ORDER BY dateline DESC LIMIT 10";
$query = $_SGLOBAL['db']->query($sql);
$ids = array();
foreach($value = $_SGLOBAL['db']->fetch_array($query)){
	$ids[] = $value['lid'];
}

if($ids){
	$_SGLOBAL['db']->query("DELETE FROM ".tname('app_laba')." WHERE lid NOT IN (".simplode($ids).")");
	$_SGLOBAL['db']->query("OPTIMIZE TABLE ".tname('app_laba'), 'SILENT');//优化表
}

?>
<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: cleandk.php 12681 2010-02-04 00:05:00Z Comver $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}


//ִ��
$_SGLOBAL['db']->query("DELETE FROM ".tname('usertask')." WHERE taskid=7");//���޸Ĵ˴���taskid


?>
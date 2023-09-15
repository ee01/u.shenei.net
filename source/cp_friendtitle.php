<?php
/*
	[UCenter friend] (C) 2007-2008 Comsenz Inc.
	$Id: cp_friendtitle.php 10586 2009-8-1 06:53:47Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}
if($_GET['op'] == 'send') {


	//判断是否发布太快
	$waittime = interval_check('post');
	if($waittime > 0) {
		showmessage('operating_too_fast','',1,array($waittime));
	}
	
	//新用户见习
	cknewuser();
	
	if(submitcheck('friendtitlesubmit')) {

		//发送消息
		$username = empty($_POST['username'])?'':$_POST['username'];

		$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET lastpost='$_SGLOBAL[timestamp]',friendtitle='$username' WHERE uid='$_SGLOBAL[supe_uid]'");
		
            header("location:viewspace.php?op=diy");
	}

} else {
	
	//新用户见习
	cknewuser();

}

include_once template("cp_friendtitle");

?>
<?php
/*
	[UCenter friend] (C) 2007-2008 Comsenz Inc.
	$Id: cp_friendtitle.php 10586 2009-8-1 06:53:47Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}
if($_GET['op'] == 'send') {


	//�ж��Ƿ񷢲�̫��
	$waittime = interval_check('post');
	if($waittime > 0) {
		showmessage('operating_too_fast','',1,array($waittime));
	}
	
	//���û���ϰ
	cknewuser();
	
	if(submitcheck('friendtitlesubmit')) {

		//������Ϣ
		$username = empty($_POST['username'])?'':$_POST['username'];

		$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET lastpost='$_SGLOBAL[timestamp]',friendtitle='$username' WHERE uid='$_SGLOBAL[supe_uid]'");
		
            header("location:viewspace.php?op=diy");
	}

} else {
	
	//���û���ϰ
	cknewuser();

}

include_once template("cp_friendtitle");

?>
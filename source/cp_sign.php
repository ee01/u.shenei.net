<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: cp_pm.php 10586 2008-12-10 06:53:47Z liguode $
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
	
	if(submitcheck('signsubmit')) {

		//������Ϣ
		$username = empty($_POST['username'])?'':$_POST['username'];

		$message = trim($_POST['message']);
                $message = empty($message)?'':$message;

		$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET lastpost='$_SGLOBAL[timestamp]',signn='$username',signm='$message' WHERE uid='$_SGLOBAL[supe_uid]'");
		
            header("location:viewspace.php");
	}

} else {
	
	//���û���ϰ
	cknewuser();

}

include_once template("cp_sign");

?>
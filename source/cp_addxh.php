<?php
/*
	[Www.ChinaGolf8.Cn] (C) 2009-2010 �и���.
	$Id: cp_addxh.php 11201931 2009-11-20 19:31 by amanda memorry $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

$pmid = empty($_GET['pmid'])?0:floatval($_GET['pmid']);
$uid = empty($_GET['uid'])?0:intval($_GET['uid']);
if($uid) {
	$touid = $uid;
} else {
	$touid = empty($_GET['touid'])?0:intval($_GET['touid']);
}
 if($_POST['xhsubmit_btn']) {
 //������Ϣ
	    $num = trim($_POST['xhnum']);
		if(empty($num)) {
			showmessage('����Ϊ��');
		}
		if($_SGLOBAL[supe_uid]==$touid) {
		showmessage('���ܸ��Լ����ʻ�,������ظ��˲���������Ϊ���״���!');
		}
		
 		$return = 0;
		if($touid) {
			//ֱ�Ӹ�һ���û���PM
			$myxh= $_SGLOBAL['db']->query("select uid,xianhua from ".tname('space')." WHERE uid='$_SGLOBAL[supe_uid]'");
			$mynum = $_SGLOBAL['db']->fetch_array($myxh);
			if($mynum['xianhua']>=$num&&$mynum['xianhua']>0) {
			$retmy = $_SGLOBAL['db']->query("update ".tname('space')." set xianhua=xianhua-'$num' WHERE uid ='$_SGLOBAL[supe_uid]'");
			if($retmy) {
				$return = $_SGLOBAL['db']->query("update ".tname('space')." set xianhua=xianhua+'$num' WHERE uid ='$touid'");
			  }
			}
			else {
			showmessage('����ʻ��������㣡');
			}
			//�����ʼ�֪ͨ
			if($return > 0) {
			notification_add($touid, 'blogtrace', cplang($_SN[$space[uid]]."�͸��� $num ���ʻ�",array($_SN[$space['uid']], getsiteurl().'space.php?uid=$_SGLOBAL[supe_uid]')));
				 $feed_title = "͵͵�ĸ�����&.& {actor} �� {username} �ͳ��� $num  ���ʻ�";
				 $ured= $_SGLOBAL['db']->query("select uid,username from ".tname('space')." WHERE uid='$touid'");
				 $uname = $_SGLOBAL['db']->fetch_array($ured);
				 $feed_data = array(
				'username' => "<a href=\"space.php?uid=$touid\">".$uname['username']."</a>",
 			);
				feed_add('xianhua', $feed_title, $feed_data);
			}
	
		} 
	  if($return > 0) {
			//������󷢲�ʱ��
 			showmessage('do_success', "space.php?uid=$touid");
			
		} 
 
		 
 }

include_once template("cp_addxh");

?>
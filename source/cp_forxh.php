<?php
/*
	[Www.ChinaGolf8.Cn] (C) 2009-2010 �и���.
	$Id: cp_addxh.php 11201931 2009-11-20 19:31 by amanda memorry $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}
 if($_POST['forsubmit_btn']) {
 //������Ϣ
	    $num = trim($_POST['xhnum']);
		if(empty($num)) {
			showmessage('����Ϊ��');
		}
	    $credit=$num*5;//С��ע���˴�Ϊ���ö��ٻ��ֶһ�1�仨�����ڵ�����Ϊ5�����ֻ�1���ʻ����漸��ҲҪ��
		
 		$return = 0;
		if($_SGLOBAL[supe_uid]) {
			//ֱ�Ӹ�һ���û���PM
			$myxh= $_SGLOBAL['db']->query("select uid,xianhua,credit from ".tname('space')." WHERE uid='$_SGLOBAL[supe_uid]'");
			$mynum = $_SGLOBAL['db']->fetch_array($myxh);
			if(($mynum['credit'])>=$num*5&&($mynum['credit'])>5) {
			$retmy = $_SGLOBAL['db']->query("update ".tname('space')." set credit=credit-'$credit' WHERE uid ='$_SGLOBAL[supe_uid]'");
				if($retmy) {
			$return = $_SGLOBAL['db']->query("update ".tname('space')." set xianhua=xianhua+'$num' WHERE uid ='$_SGLOBAL[supe_uid]'");				                }
			}
			else {
			showmessage('����и������ֲ���,���ܽ��д˴ζһ�,�뷵���޸Ķһ�������ȥ���н�����׬ȡ���֣�');
			}
		}
			
			//feed֪ͨ
			if($return > 0) {
				smail($touid, '', cplang($_SN[$space['uid']]." �͸��� $num ���ʻ�",array($_SN[$space['uid']], getsiteurl().'space.php?do=pm')));
				 $feed_title = "{actor}�� $credit ���и������ֻ��� $num ���ʻ�,�²�TA׼���͸�˭��^.^";
   	 			  feed_add('xianhua', $feed_title);
			}
	
		
	  if($return > 0) {
			//������󷢲�ʱ��
 			showmessage('do_success', "space.php?do=home");
			
		} 		 
 }
include_once template("cp_forxh");

?>
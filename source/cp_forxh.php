<?php
/*
	[Www.ChinaGolf8.Cn] (C) 2009-2010 中高网.
	$Id: cp_addxh.php 11201931 2009-11-20 19:31 by amanda memorry $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}
 if($_POST['forsubmit_btn']) {
 //发送消息
	    $num = trim($_POST['xhnum']);
		if(empty($num)) {
			showmessage('不能为空');
		}
	    $credit=$num*5;//小澳注：此处为设置多少积分兑换1朵花，现在的设置为5个积分换1朵鲜花下面几处也要改
		
 		$return = 0;
		if($_SGLOBAL[supe_uid]) {
			//直接给一个用户发PM
			$myxh= $_SGLOBAL['db']->query("select uid,xianhua,credit from ".tname('space')." WHERE uid='$_SGLOBAL[supe_uid]'");
			$mynum = $_SGLOBAL['db']->fetch_array($myxh);
			if(($mynum['credit'])>=$num*5&&($mynum['credit'])>5) {
			$retmy = $_SGLOBAL['db']->query("update ".tname('space')." set credit=credit-'$credit' WHERE uid ='$_SGLOBAL[supe_uid]'");
				if($retmy) {
			$return = $_SGLOBAL['db']->query("update ".tname('space')." set xianhua=xianhua+'$num' WHERE uid ='$_SGLOBAL[supe_uid]'");				                }
			}
			else {
			showmessage('你的中高网积分不足,不能进行此次兑换,请返回修改兑换数量或去做有奖任务赚取积分！');
			}
		}
			
			//feed通知
			if($return > 0) {
				smail($touid, '', cplang($_SN[$space['uid']]." 送给你 $num 朵鲜花",array($_SN[$space['uid']], getsiteurl().'space.php?do=pm')));
				 $feed_title = "{actor}用 $credit 个中高网积分换了 $num 朵鲜花,猜猜TA准备送给谁呢^.^";
   	 			  feed_add('xianhua', $feed_title);
			}
	
		
	  if($return > 0) {
			//更新最后发布时间
 			showmessage('do_success', "space.php?do=home");
			
		} 		 
 }
include_once template("cp_forxh");

?>
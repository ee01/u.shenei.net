<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: admincp_config.php 12998 2009-08-05 03:29:54Z liguode $
*/

if(!defined('IN_UCHOME') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

//权限
if(!checkperm('manageconfig')) {
	cpmessage('no_authority_management_operation');
}

if(submitcheck('thevaluesubmit')) {

	$filesize	=	intval($_POST['filesize']);
	$upjifen	=	intval($_POST['upjifen']);
	$downjifen	=	intval($_POST['downjifen']);
	$filetype	=	$_POST['filetype'];
	$isshenhe	=	$_POST['isshenhe'];
	$data = array(
		"filesize"				=> 	$filesize,
		"upjifen"				=>	$upjifen,
		"downjifen"				=>	$downjifen,
		"realuserup"			=> 	$_POST['realuserup'],
		"realuserdown"			=> 	$_POST['realuserdown'],
		"filesize"				=> 	$filesize,
		"filetype"				=> 	$filetype,
		"isshenhe"				=>	$isshenhe,
		"istwoup"				=>	$_POST['istwoup']
	);
	updatetable('nd_config',$data,'id =1',0);	
	cpmessage('更新成功！', 'admincp.php?ac=ND_config');
}
else
{
	$sql 		= 	"select * from  ".tname('nd_config');
	$query 		= 	$_SGLOBAL['db']->query($sql);
	$value		= 	$_SGLOBAL['db']->fetch_array($query);
}

?>
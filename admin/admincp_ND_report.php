<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: admincp_blog.php 9233 2008-10-28 06:21:44Z liguode $
*/

if(!defined('IN_UCHOME') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
//权限
if(!checkperm('manageusergroup')) {
	cpmessage('no_authority_management_operation');
}

$op			=	$_GET['op'];
if(empty($op))
{
	$perpage 	= 	10;
	$mpurl 		= 	'admincp.php?ac=ND_report';
	$page 		= 	empty($_GET['page'])?1:intval($_GET['page']);
	if($page<1) $page = 1;
	$start = ($page-1)*$perpage;
	ckstart($start, $perpage);
	$sql 		= 	"select * from ".tname('nd_report')." order by rid desc";
	$query 		= 	$_SGLOBAL['db']->query($sql);
	$count		=	mysql_num_rows($query);
	$sql 		.=	" limit $start,$perpage";
	$list		=	array();
	$query 		= 	$_SGLOBAL['db']->query($sql);
	while($value= $_SGLOBAL['db']->fetch_array($query)) {
		$list[]	=	$value;
	}
}
if($op=="del"&&isset($_POST['formhash']))
{
	if(!empty($_POST['del'])){
		$id			=	$_POST['del'];
		$num		=	count($id);
		for($i=0;$i<$num;$i++)
		{
			$ids	.=	$id[$i].',';
		}
		$ids		=	substr($ids,0,strlen($ids)-1);
		$sql		=	"delete from ".tname('nd_report')." where rid in (".$ids.")";
		$_SGLOBAL['db']->query($sql);
		
		cpmessage('删除成功!', 'admincp.php?ac=ND_report');
	}else{

        cpmessage('请选择要删除的对象.......!', 'admincp.php?ac=ND_report');
	}
}
?>
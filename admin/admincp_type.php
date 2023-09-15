<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: admincp_usergroup.php 10469 2008-12-04 08:41:47Z liguode $
*/

if(!defined('IN_UCHOME') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

//权限
if(!checkperm('manageusergroup')) {
	cpmessage('no_authority_management_operation');
}

$op	=	$_GET['op'];
if(empty($op))
{
	
	$list	=	array();
	$query 	= 	$_SGLOBAL['db']->query("SELECT * FROM ".tname('ND_type')." where parent=0 order by sort desc");
	while($value = $_SGLOBAL['db']->fetch_array($query)) {
		$query2 	= 	$_SGLOBAL['db']->query("SELECT * FROM ".tname('ND_type')." where parent=".$value['id']." order by sort desc");
		while($value2 = $_SGLOBAL['db']->fetch_array($query2)) {
			$value['child'][]	=	$value2;
		}
		$list[]	=	$value;
	}
	
}
if($op=="add"&&isset($_POST['addsubmit']))
{
	$data = array(
		"name"				=> 	$_POST['name'],
		"parent"			=>	$_POST['parent'],
		"sort"				=> 	$_POST['sort']
	);
	inserttable("ND_type",$data,1 );
	showmessage('添加成功!','admincp.php?ac=type',3);
}
if($op=="update"&&isset($_POST['formhash']))
{
	if(!empty($_POST['typeall'])){
		$id			=	$_POST['typeall'];
		$num		=	count($id);
		for($i=0;$i<$num;$i++)
		{
			$data = array(
				"name"				=> 	$_POST['name_'.$id[$i]],
				"parent"			=>	$_POST['parent_'.$id[$i]],
				"sort"				=> 	$_POST['sort_'.$id[$i]]
		  	);
			updatetable('ND_type',$data,'id ='.$id[$i],0);	
		}
		cpmessage('更新成功!', 'admincp.php?ac=type');
	}else{

        cpmessage('请选择要更新的对象.......!', 'admincp.php?ac=type');
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
		$sql		=	'delete FROM '.$_SC['tablepre'].'ND_type where id in ('.$ids.')';
		$query = $_SGLOBAL['db']->query( $sql );
		cpmessage('删除成功!', 'admincp.php?ac=type');
	}else{

        cpmessage('请选择要删除的对象.......!', 'admincp.php?ac=type');
	}
}


?>
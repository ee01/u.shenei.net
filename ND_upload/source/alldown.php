<?php
if ( !defined( "IN_UCHOME" ) )
{
	exit( "Access Denied" );
}
$id		=	intval($_GET['id']);
if(!empty($id))
{
	$perpage 	= 	10;
	$mpurl 		= 	'ND_upload.php?do=alldown&id='.$id;
	$page 		= 	empty($_GET['page'])?1:intval($_GET['page']);
	if($page<1) $page = 1;
	$start = ($page-1)*$perpage;
	ckstart($start, $perpage);
	$sql 		= 	"select * from ".tname('nd_down')." where nd_id=".$id." group by uid order by id desc ";
	$query 		= 	$_SGLOBAL['db']->query($sql);
	$count		=	mysql_num_rows($query);
	$sql 		.=	" limit $start,$perpage";
	$query 		= 	$_SGLOBAL['db']->query($sql);
	$list		=	array();
	while ($value= $_SGLOBAL['db']->fetch_array($query))
	{
		realname_set($value['uid'], $value['username']);//ÊµÃû
		$list[]				=	$value;
	}
	$multi 		= 	multi($count, $perpage, $page, $mpurl);
	
	$count1		=	0;
	$sql 		= 	"select * from ".tname('nd_data')." where state>0 and publicall<=2 order by id desc";
	$query 		= 	$_SGLOBAL['db']->query($sql);
	$count1		=	mysql_num_rows($query);
	
	$count2		=	0;
	$sql 		= 	"select friend from ".tname('spacefield')." where uid=".$_SGLOBAL['supe_uid'];
	$query 		= 	$_SGLOBAL['db']->query($sql);
	$value		= 	$_SGLOBAL['db']->fetch_array($query);
	$friends	=	$value['friend'];
	if(!empty($friends))
	{
		$sql 		= 	"select * from ".tname('nd_data')." where state>0 and uid in (".$friends.")  order by id desc";
		$query 		= 	$_SGLOBAL['db']->query($sql);
		$count2		=	mysql_num_rows($query);
	}
	
	$count3		=	0;
	$countdown	=	0;
	$huodefen	=	0;
	$mynd_id	=	"";
	$sql 		= 	"select * from ".tname('nd_data')."  where uid=".$_SGLOBAL['supe_uid']." order by id desc";
	$query 		= 	$_SGLOBAL['db']->query($sql);
	while($value= $_SGLOBAL['db']->fetch_array($query)) {
		$count3++;
		$countdown	=	$countdown+$value['downnum'];
		$mynd_id=	$mynd_id.$value['id'].',';	//Modify By 01
	}
	if(!empty($mynd_id))
	{
		$mynd_id	=	substr($mynd_id,0,strlen($mynd_id)-1);
	$sql 		= 	"select sum(jifen) as j from ".tname('nd_down')."  where nd_id in (".$mynd_id.") order by id desc";
	$query 		= 	$_SGLOBAL['db']->query($sql);
	$value		= 	$_SGLOBAL['db']->fetch_array($query);
		$huodefen	=	$value['j'];
	}
	
	$sql 		= 	"select sum(jifen) as d,count(id) as dn from ".tname('nd_down')."  where uid=".$_SGLOBAL['supe_uid']." group by uid order by id desc";
	$query 		= 	$_SGLOBAL['db']->query($sql);
	$value		= 	$_SGLOBAL['db']->fetch_array($query);
	$xiazai		=	$value['dn'];
	$diufen		=	$value['d'];
	
	$downnun	=	array();
	$query 		= 	$_SGLOBAL['db']->query("SELECT * FROM ".tname('nd_data')." where state>0 order by liulan desc limit 0,10");
	while($values= $_SGLOBAL['db']->fetch_array($query)) {
		$downnun[]	=	$values;
}

$commentnun	=	array();
$query 		= 	$_SGLOBAL['db']->query("SELECT * FROM ".tname('nd_data')." where state>0 order by comment desc limit 0,10");
while($values= $_SGLOBAL['db']->fetch_array($query)) {
	$commentnun[]	=	$values;
}

$numnn		=	array();
$query 		= 	$_SGLOBAL['db']->query("SELECT uid,count( uid ) AS num FROM ".tname('nd_data')." where state>0 GROUP BY uid order by num desc limit 0,10");
while($values= $_SGLOBAL['db']->fetch_array($query)) {
	$space 				= 	getspace($values['uid']);
	$values['username']	=	$space['username'];
	$numnn[]			=	$values;
}

$type		=	array();
$query 	= 	$_SGLOBAL['db']->query("SELECT * FROM ".tname('ND_type')." where parent=0 order by sort desc");
while($value= $_SGLOBAL['db']->fetch_array($query)) {
	$query2 	= 	$_SGLOBAL['db']->query("SELECT * FROM ".tname('ND_type')." where parent=".$value['id']." order by sort desc");
	while($value2 = $_SGLOBAL['db']->fetch_array($query2)) {
		$value['child'][]	=	$value2;
	}
	$type[]	=	$value;
	}
}
else
{
	showmessage('Â·¾¶´íÎó£¡','ND_upload.php',3);
}
include_once template("ND_upload/template/alldown");
?>

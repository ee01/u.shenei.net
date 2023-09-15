<?php
if ( !defined( "IN_UCHOME" ) )
{
	exit( "Access Denied" );
}
$id			=	intval($_GET['id']);
if(!empty($id))
{
	$allorders 	= 	array('title','geshi','size','jifen','downnum');
	$orders 	= 	(!empty($_GET['orders']) && in_array($_GET['orders'], $allorders))?$_GET['orders']:'id';
	$direct		=	$_GET['direct'];
	$dec		=	'desc';
	switch ($direct)
	{
		case 'up':
  			$dec		=	'desc';
			$direct		=	'down';
  			break;  
		case 'down':
  			$dec		=	'asc';
			$direct		=	'up';
  			break;
		default:
  			$direct		=	'up';
	}
	$perpage 	= 	10;
	$mpurl 		= 	'ND_upload.php';
	$page 		= 	empty($_GET['page'])?1:intval($_GET['page']);
	if($page<1) $page = 1;
	$start = ($page-1)*$perpage;
	ckstart($start, $perpage);
	$sql 		= 	"select nd.*,nt.name as ntt from ".tname('nd_data')." as nd left join ".tname('nd_type')." as nt on nt.id =nd.type where nd.stwo=".$id." and nd.state>0 order by nd.".$orders." ".$dec;
	$query 		= 	$_SGLOBAL['db']->query($sql);
	$count		=	mysql_num_rows($query);
	$sql 		.=	" limit $start,$perpage";
	$query 		= 	$_SGLOBAL['db']->query($sql);
	$list		=	array();
	while ($value= $_SGLOBAL['db']->fetch_array($query))
	{
		$size				=	$value['size'];
		$daxiao1			=	$size/1024;
		$daxiao1			=	sprintf("%.2f",$daxiao1);
		if($daxiao1>1)
		{
			if($daxiao1>=1024)
			{
				$daxiao1=	$daxiao1/1024;
				$daxiao1=	sprintf("%.2f",$daxiao1);
				$size	=	$daxiao1." "."MB";
			}
			else
			{
				$size	=	$daxiao1." "."KB";
			}
			
		}
		else
		{
			$size	=	$daxiao1." "."Byte";
		}
		$value['sizes']		=	$size;
		$list[]				=	$value;
	}
	$multi 		= 	multi($count, $perpage, $page, $mpurl);
	
	$count1		=	0;
	$sql 		= 	"select * from ".tname('nd_data')." where state>0 order by id desc";
	$query 		= 	$_SGLOBAL['db']->query($sql);
	$count1		=	mysql_num_rows($query);
	
	$count2		=	0;
	$sql 		= 	"select friend from ".tname('spacefield')." where uid=".$_SGLOBAL['supe_uid'];
	$query 		= 	$_SGLOBAL['db']->query($sql);
	$value		= 	$_SGLOBAL['db']->fetch_array($query);
	$friends	=	$value['friend'];
	if(!empty($friends))
	{
		$sql 		= 	"select * from ".tname('nd_data')." where state>0 and uid in (".$friends.") order by id desc";
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
	$mynd_id	=	substr($mynd_id,0,strlen($mynd_id)-1);
	if(!empty($mynd_id))
	{
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
	$query 		= 	$_SGLOBAL['db']->query("SELECT * FROM ".tname('nd_data')." where  state>0 order by liulan desc limit 0,10");
	while($values= $_SGLOBAL['db']->fetch_array($query)) {
		$downnun[]	=	$values;
	}

	$commentnun	=	array();
	$query 		= 	$_SGLOBAL['db']->query("SELECT * FROM ".tname('nd_data')." where  state>0 order by comment desc limit 0,10");
	while($values= $_SGLOBAL['db']->fetch_array($query)) {
		$commentnun[]	=	$values;
	}

	$numnn		=	array();
	$query 		= 	$_SGLOBAL['db']->query("SELECT uid,count( uid ) AS num FROM ".tname('nd_data')." where  state>0 GROUP BY uid order by num desc limit 0,10");
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
	showmessage('Â·¾¶´íÎó£¡','index.php',3);
}
include_once template("ND_upload/template/list");
?>

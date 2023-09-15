<?php
if ( !defined( "IN_UCHOME" ) )
{
	exit( "Access Denied" );
}
$type		=	array();
$query 		= 	$_SGLOBAL['db']->query("SELECT * FROM ".tname('ND_type')." order by id desc");
while($values= $_SGLOBAL['db']->fetch_array($query)) {
	$type[]	=	$values;
}
$id		=	intval($_GET['id']);
$valueND=	array();
if(!empty($id))
{
	$sql		=	"select nd.*,nt.name as ntt,ntts.name as ntt1 from ".tname('nd_data')." as nd left join ".tname('nd_type')." as nt on nt.id =nd.type  left join ".tname('nd_type')." as ntts on ntts.id =nd.stwo where  nd.state>0 and nd.id=".$id;
	$query 		= 	$_SGLOBAL['db']->query($sql);
	$valueND 	= 	$_SGLOBAL['db']->fetch_array($query);
	if($valueND)
	{
		if($valueND['publicall']==3&&$_SGLOBAL['supe_uid']!=$valueND['uid'])
		{
			showmessage('这个资源被作者隐藏！','ND_upload.php',3);
		}
		$spaces		=	getspace($valueND['uid']);
		$friends	=	explode(',',$spaces['friend']);
		if($valueND['publicall']==2&&!in_array($_SGLOBAL['supe_uid'],$friends)&&$_SGLOBAL['supe_uid']!=$valueND['uid'])
		{
			showmessage('这个资源被作者隐藏！','ND_upload.php',3);
		}
		$actives 	= 	array('nd_'.$valueND['type']=>' class="active"');
		$spaces		=	getspace($valueND['uid']);
		$dates		=	date('Y-m-d',$valueND['datetime']);
		$size		=	$valueND['size'];
		$daxiao1	=	$size/1024;
		$daxiao1	=	sprintf("%.2f",$daxiao1);
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
		$listND		=	explode(',',$valueND['datasrc']);
		$filenums	=	count($listND)-1;
		$listtitle	=	explode('htk200908251449',$valueND['namesrc']);
		for($i=0;$i<count($listND)-1;$i++)
		{
			$j		=	$i+1;
			$lists	.=	'<a href="ND_upload.php?do=down&id='.$id.'&n='.$j.'" target="_blank">'.$listtitle[$i].'</a>&nbsp;&nbsp;&nbsp;';
		}
		
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
	
	$downuser	=	array();
	$sql 		= 	"select * from ".tname('nd_down')."  where nd_id=".$id." group by uid order by id desc";
	$query 		= 	$_SGLOBAL['db']->query($sql);
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['uid'], $value['username']);//实名
		$downuser[] = $value;
	}
	
	$othernd	=	array();
	$sql 		= 	"select * from ".tname('nd_data')."  where uid=".$valueND['uid']." and id<>".$id." order by id desc";
	$query 		= 	$_SGLOBAL['db']->query($sql);
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		$othernd[] = $value;
	}
	
	$userdowns	=	array();
	$sql 		= 	"select nt.* from ".tname('nd_down')." as nd left join  ".tname('nd_data')." as nt on nt.id=nd.nd_id where nd.uid=".$valueND['uid']." and nd.nd_id<>".$id." order by nd.id desc";
	$query 		= 	$_SGLOBAL['db']->query($sql);
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		$userdowns[] = $value;
	}
	
	
	$sql 		= 	"select sum(jifen) as d,count(id) as dn from ".tname('nd_down')."  where uid=".$_SGLOBAL['supe_uid']." group by uid order by id desc";
	$query 		= 	$_SGLOBAL['db']->query($sql);
	$value		= 	$_SGLOBAL['db']->fetch_array($query);
	$xiazai		=	$value['dn'];
	$diufen		=	$value['d'];
	
	$page = empty($_GET['page'])?1:intval($_GET['page']);
	if($page<1) $page=1;
	//评论
	$perpage = 30;
	$perpage = mob_perpage($perpage);
	
	$start = ($page-1)*$perpage;

	//检查开始数
	ckstart($start, $perpage);

	$count = $valueND['comment'];

	$listcomment = array();
	if($count) {
		$cid = empty($_GET['cid'])?0:intval($_GET['cid']);
		$csql = $cid?"cid='$cid' AND":'';

		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('comment')." WHERE id='$id' AND idtype='ND_coment_id' ORDER BY dateline LIMIT $start,$perpage");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			realname_set($value['authorid'], $value['author']);//实名
			$listcomment[] = $value;
		}
	}

	//分页
	$multi = multi($count, $perpage, $page, "ND_upload.php?do=info&id=$id", '', 'content');
	
	
	
	
	
	
		$perpage 	= 	10;
		$mpurl 		= 	'ND_upload.php?do=info&id='.$id;
		$page 		= 	empty($_GET['page'])?1:intval($_GET['page']);
		if($page<1) $page = 1;
		$start = ($page-1)*$perpage;
		ckstart($start, $perpage);
		$sql 		= 	"SELECT * FROM ".tname('nd_comment')." WHERE nd_id=".$id." order by id desc";
		$query 		= 	$_SGLOBAL['db']->query($sql);
		$count		=	mysql_num_rows($query);
		$sql 		.=	" limit $start,$perpage";
		$query 		= 	$_SGLOBAL['db']->query($sql);
		$comment	=	array();
		while ($value = $_SGLOBAL['db']->fetch_array($query))
		{
			$value['riqi']		=	date('Y-m-d　H:i:s',$value['riqi']+3600*8);
			$space 				= 	getspace($value['uid']);
			$value['username']	=	$space['username'];
			$comment[]=	$value;
		}
		$multi 		= 	multi($count, $perpage, $page, $mpurl);
	
		$downnun	=	array();
		$query 		= 	$_SGLOBAL['db']->query("SELECT * FROM ".tname('nd_data')." where type=".$valueND['type']." and  state>0 order by liulan desc limit 0,10");
		while($values= $_SGLOBAL['db']->fetch_array($query)) {
			$downnun[]	=	$values;
		}
		$xiangguan	=	array();
		$tag		=	"";
		$query 		= 	$_SGLOBAL['db']->query("SELECT * FROM ".tname('nd_keywords')." where ND_id=$id");
		while($values= $_SGLOBAL['db']->fetch_array($query)) {
			$tag	.=	" and name like '%".$values['name']."%'";
		}
		if(!empty($tag))
		{
			$sql		=	"SELECT ND_id FROM ".tname('nd_keywords')." where ND_id<>$id".$tag." group by id order by id desc limit 0,10";
			$ids		=	"";
			$query 		= 	$_SGLOBAL['db']->query($sql);
			while($values= $_SGLOBAL['db']->fetch_array($query)) {
				$ids	.=	$values['ND_id'].",";
			}
			if(!empty($ids))
			{
				$ids	=	substr($ids,0,strlen($ids)-1);
				$sql	=	"SELECT * FROM ".tname('nd_data ')." where state>0 and  id in (".$ids.")";
				$query 	= 	$_SGLOBAL['db']->query($sql);
				while($values= $_SGLOBAL['db']->fetch_array($query)) {
					$xiangguan[]=	$values;
				}
			}
		}
	}
	else
	{
		showmessage('这个资源还没有通过审核！','ND_upload.php',3);
	}
}
else
{
	showmessage('路径错误！','ND_upload.php',3);
}

//Add By 01↓
function getFileSize($url) {
	$url = parse_url($url); 
	if($fp = @fsockopen($url['host'],empty($url['port'])?80:$url['port'],$error)) {
		fputs($fp,"GET ".(empty($url['path'])?'/':$url['path'])." HTTP/1.1\r\n");
		fputs($fp,"Host:$url[host]\r\n\r\n");
		while(!feof($fp)) {
			$tmp = fgets($fp);
			if(trim($tmp) == '') {
				break;
			}else if(preg_match('/Content-Length:(.*)/si',$tmp,$arr)) {
				return trim($arr[1]);
			}
		}
		return null;
	}else{
		return null;
	}
}
//Add By 01↑

include_once template("ND_upload/template/info");
?>

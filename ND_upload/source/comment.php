<?php
header('Content-Type:text/html;charset=gbk');
if ( !defined( "IN_UCHOME" ) )
{
	exit( "Access Denied" );
}

$id			=	intval($_POST['infoid']);
$comment	=	iconv( "utf-8","gbk",$_POST['comment']);
$comment	=	nl2br($comment);
$comment	=	addslashes($comment);
$uid 		= 	$_SGLOBAL['supe_uid'];
if(empty($uid))
{
	exit('0');
}
$data 		= 	array(
	"uid"			=>	$uid,
	"nd_id" 		=> 	$id,
	"comment" 		=>	$comment,
	"ip"			=>	getonlineip(),
	"riqi" 			=> 	time()
);
inserttable("nd_comment",$data,1 ); 
$sql		=	"update ".tname('nd_data')." set comment=comment+1 where id = ".$id;	
$_SGLOBAL['db']->query( $sql);

$perpage 	= 	10;
$mpurl 		= 	'ND_upload.php?do=info&id='.$id;
$page 		= 	empty($_GET['page'])?1:intval($_GET['page']);
if($page<1) $page = 1;
$start = ($page-1)*$perpage;
ckstart($start, $perpage);
$sql 		= 	"SELECT * FROM ".tname('nd_comment')." WHERE nd_id=".$id." order by id desc";
$query 		= 	$_SGLOBAL['db']->query($sql);
$count		=	mysql_num_rows($query);
$sql 		.=	" limit 0,10";
$query 		= 	$_SGLOBAL['db']->query($sql);
$info		=	'<table class="formclass">';
while ($value = $_SGLOBAL['db']->fetch_array($query))
{
	$riqi	=	date('Y-m-d¡¡H:i:s',$value['riqi']+3600*8);
	$space 	= 	getspace($value['uid']);
	$info	.=	'<tr><td>'.$space['username'].'Ëµ:'.$value['comment'].'('.$riqi.')</td></tr>'."\n";
}
$multi 		= 	multi($count, $perpage, $page, $mpurl);
$mess		=	$info.'</table>htk20090805'.$multi;
echo 	$mess;
?>
 

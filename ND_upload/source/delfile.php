<?php
if ( !defined( "IN_UCHOME" ) )
{
	exit( "Access Denied" );
}
if(!empty($_GET['id'])){
	$id			=	$_GET['id'];
	$sql		=	"select * from ".tname('nd_data')." where id =".$id;
	$query 		= 	$_SGLOBAL['db']->query($sql);
	$value		= 	$_SGLOBAL['db']->fetch_array($query);
	$filelist	=	$value['datasrc'];
	$namelist	=	$value['namesrc'];
	$filelist	=	explode(',',$filelist);
	$namelist	=	explode('htk200908251449',$namelist);
	if(file_exists($filelist[$_GET['n']]))
	{
		unlink($filelist[$_GET['n']]);
	}
	$str		=	str_replace($filelist[$_GET['n']].',',"",$value['datasrc']);
	$name		=	str_replace($namelist[$_GET['n']].'htk200908251449',"",$value['namesrc']);
	$sql		=	"update ".tname('nd_data')." set datasrc='".$str."',namesrc='".$name."' where id =".$id;
	$_SGLOBAL['db']->query($sql);
	showmessage('ɾ���ɹ�!', 'ND_upload.php?do=add&op=edit&id='.$id);
}else{
	showmessage('��ѡ��Ҫɾ���Ķ���.......!', 'ND_upload.php?do=add&op=edit&id='.$id);
}
?>

<?php
if ( !defined( "IN_UCHOME" ) )
{
	exit( "Access Denied" );
}
$id			=	intval($_GET['id']);
$sql		=	"update ".tname('nd_data ')." set liulan=liulan+1 where id = ".$id;	
$_SGLOBAL['db']->query( $sql);
?>
 

<?php

//Powered by Jiekii.com
//QQ：357754800

if ( !defined( "IN_UCHOME" ) )
{
    exit( "Access Denied" );
}

$id = (int)$_GET['id'];
if (empty($id)) {
	showmessage('参数错误', 'ana.php');
}
$ana_id = & $id;

//获取语录
$sql = "SELECT * FROM ".tname("ana")." WHERE id= $id ";
$query = $_SGLOBAL['db']->query( $sql );
$ana = $_SGLOBAL['db']->fetch_array( $query );
if (empty($ana)) {
	showmessage("语录不存在或者已经被删除", 'ana.php?do=ana');
}
//更新点击数
$sql = "UPDATE ".tname("ana")." SET view_count = view_count + 1, reply_count ={$ana['reply_count']} WHERE id = ".$id;
$_SGLOBAL['db']->query( $sql );

//评论
$theurl = "ana.php?do=ana&ac=view&id={$ana_id}";

$reply = array();
$sql = "SELECT * FROM ".tname('ana_reply')." WHERE ana_id = $ana_id ORDER BY id ";
$query = $_SGLOBAL['db']->query($sql);
$count = 0;
$arr_ids = array();
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	$value['content'] = nl2br(htmlspecialchars($value['content'])) ;
	$reply[] = $value;
	$arr_ids[] = $value['id'];
	$count++;
}

//$sql = "UPDATE ".tname("ana")." SET reply_count ={$count} WHERE id = ".$id;
//$_SGLOBAL['db']->query( $sql );

$str_ids = implode(",", $arr_ids);
include_once( template( "ana/view/ana_view" ) );
?>
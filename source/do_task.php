<?php
/*
    详见安装说明文件
   2010.1.4
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

$taskid = intval( $_GET['taskid'] );
$u = intval( $_GET['u'] );
if( empty($taskid) || $taskid<8 ){ exit(); }
if( empty($u) || $u<1 ){ exit(); }

$is_spider = 0;
if( preg_match("/(Bot|Crawl|Spider|slurp|sohu-search|lycos|robozilla|Sogou|yahoo)/i", $_SERVER['HTTP_USER_AGENT']) && !preg_match("/(MSIE|Netscape|Opera|Konqueror|Mozilla|Chrome|Maxthon|NetCaptor|Lynx)/i", $_SERVER['HTTP_USER_AGENT']) ) {
	$is_spider = 1;
}
$task_end = 0;
$query = $_SGLOBAL['db']->query('SELECT * FROM '.tname('task')." WHERE taskid='$taskid'");
$task = $_SGLOBAL['db']->fetch_array($query);
if( empty($task['taskid']) ) { exit(); }
if( $task['starttime'] > $_SGLOBAL['timestamp'] || ( $task['endtime']>0 && $task['endtime'] < $_SGLOBAL['timestamp']) ) {
	$task_end = 1;
}
$ip = getonlineip();
$has_done = 0;
$query = $_SGLOBAL['db']->query('SELECT * FROM '.tname('task_xinnian')." WHERE taskid='$taskid' AND uid='$u'");
$task_xinnian = $_SGLOBAL['db']->fetch_array($query);
if( ( !empty($ip) && strpos( ',,'.$task_xinnian['ips'].',' , ','.$ip.',' ) ) || ( !empty($_SGLOBAL['supe_uid']) && strpos( ',,'.$task_xinnian['uids'].',' , ','.$_SGLOBAL['supe_uid'].',' ) ) ) {
	$has_done = 1;
}
$refer = empty($_GET['refer'])?rawurldecode($_SCOOKIE['_refer']):$_GET['refer'];
if( !empty($_SERVER['HTTP_REFERER']) ) { $refer = $_SERVER['HTTP_REFERER']; }
if ( ( empty($_SGLOBAL['supe_uid']) || $_SGLOBAL['supe_uid']!=$u ) && empty($_SCOOKIE['_taskid8']) && ( empty($refer) || strpos($refer,'mail.') ) && $task_end!=1 && $has_done!=1 && $is_spider!=1 ){
	if ( $task_xinnian['uid']!='' ) {
		$addsql = '';
		if( !empty($ip) ){ $addsql .= ',ips="' . $task_xinnian['ips'] . ',' . $ip . '"'; }
		if( !empty($_SGLOBAL['supe_uid']) ){ $addsql .= ',uids="' . $task_xinnian['uids'] . ',' . $_SGLOBAL['supe_uid'] . '"'; }
		$_SGLOBAL['db']->query( "UPDATE ".tname( "task_xinnian" )." set view=view+1,count=count+1".$addsql." where taskid='$taskid' AND uid='$u'");
	} else {
		$_SGLOBAL['db']->query( "INSERT INTO ".tname( "task_xinnian" )." (uid,taskid,count,ips,uids,view) VALUES(".$u.",'".$taskid."','1','".$ip."','".$_SGLOBAL['supe_uid']."','1')" );
	}
} else {
	$_SGLOBAL['db']->query( "UPDATE ".tname( "task_xinnian" )." set view=view+1 where taskid='$taskid' AND uid='$u'");
}
ssetcookie('_taskid8', '1');

//获取实名
realname_set($u);
realname_get();

echo "Loading...<META HTTP-EQUIV=REFRESH CONTENT='0;URL=link.php?url=http://2010.shenei.net/?stra=".escape($_SN[$u])."'>";
exit();

//编码
function escape($str) { 
	preg_match_all("/[\x80-\xff].|[\x01-\x7f]+/",$str,$r); 
	$ar = $r[0]; 
	foreach($ar as $k=>$v) { 
		if(ord($v[0]) < 128) 
			$ar[$k] = rawurlencode($v); 
		else 
			$ar[$k] = "%u".bin2hex(iconv("GB2312","UCS-2",$v)); 
	} 
	return join("",$ar); 
} 
//解码
function unescape($str) { 
	$str = rawurldecode($str); 
	preg_match_all("/(?:%u.{4})|.+/",$str,$r); 
	$ar = $r[0]; 
	foreach($ar as $k=>$v) { 
		if(substr($v,0,2) == "%u" && strlen($v) == 6) 
			$ar[$k] = iconv("UCS-2","GB2312",pack("H4",substr($v,-4))); 
	} 
	return join("",$ar); 
}
?>
<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: space.php 10371 2008-12-02 05:33:28Z liguode $
*/

include_once('./common.php');



//是否关闭站点
checkclose();

//是否公开
if(empty($isinvite) && empty($_SCONFIG['networkpublic'])) {
	checklogin();//需要登录
}



$value = queryr("SELECT * FROM ".tname('space')." order by rand() limit 1");

echo "<script>location.href='viewspace.php?uid=$value[uid]'</script>";


function get_avatar($uid, $size = 'middle') {
$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'middle';
$uid = abs(intval($uid));
$uid = sprintf("%09d", $uid);
$dir1 = substr($uid, 0, 3);
$dir2 = substr($uid, 3, 2);
$dir3 = substr($uid, 5, 2);
return $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2)."_avatar_$size.jpg";
}

function queryr($sql)
{
global $_SGLOBAL;
$query = $_SGLOBAL['db']->query( $sql );
return $_SGLOBAL['db']->fetch_array( $query );
}

?>
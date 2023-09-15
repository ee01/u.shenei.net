<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: rss.php 12766 2009-07-20 04:26:21Z liguode $
*/

include_once('./common.php');

$uid = empty($_GET['uid'])?0:intval($_GET['uid']);

$url = "http://u.shenei.net/siconv.php?url=http://u.shenei.net/rss.php?uid=".$uid."&charset=utf-8";
$contents = file_get_contents($url);

echo $contents;
?>
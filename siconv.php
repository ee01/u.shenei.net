<?php
/*
	[F.S. Studio] (C) 2007-2008 E Inc.
	$Id: siconv.php 12766 2009-07-20 04:26:21Z E $
*/

include_once('./common.php');

$charset = $_GET['charset'];
$url = $_GET['url'].'&charset='.$charset;
$in = empty($_GET['in'])?'gbk':$_GET['in'];
$out = empty($_GET['out'])?($in=='gbk'?'utf8':'gbk'):$_GET['out'];

$contents = file_get_contents($url);
$contents = siconv($contents, "utf-8", "gbk");
echo $contents;
?>
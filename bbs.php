<?php

/*
	[Discuz!] (C)2001-2007 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: faq.php 9804 2007-08-15 05:56:19Z cnteacher $
*/

require_once './common.php';
require_once './source/function_common.php';

include_once(S_ROOT.'./source/function_cp.php');
$icon = 'bbs';
$title_template = '{actor} ÕýÔÚä¯ÀÀ<a href="bbs.php">ÂÛÌ³</a>£¡';
feed_add($icon, $title_template);


include template('bbs');

?>
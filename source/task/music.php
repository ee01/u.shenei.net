<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: profile.php 8401 2008-08-06 09:19:53Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//判断用户是否全部设置了个人资料
$nones = array();
$profile_lang = array(
	'field_2' => '背景音乐',

);
foreach (array('field_2') as $key) {
	$value = trim($space[$key]);
	if(empty($value)) {
		$nones[] = $profile_lang[$key];
	}
}


if(empty($nones)) {

	$task['done'] = 1;//活动完成
	
	

} else {

	//活动完成向导
	$task['guide'] = '
		<strong>您还没有补充完整：</strong><br>
		<span style="color:red;">'.implode('<br>', $nones).'</span><br><br>
		<strong>请按照以下的说明来完成本活动：</strong>
		<ul class="task">
		<li>1. <a href="cp.php?ac=profile" target="_blank">新窗口打开个人资料设置页面</a>；</li>
		<li>2. 在新打开的设置页面中，将上述背景音乐补充完整。</li>
		</ul>';

}

?>
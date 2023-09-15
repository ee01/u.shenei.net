<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: renzheng.php 8401 2008-08-12 15:12:55Z PopLong $
*/



if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//判断是否通过实名认证

if($space[namestatus] == 1) {

	$task['done'] = 1;//活动完成
	
	

} else {

	//活动完成向导
	$task['guide'] = '
		<strong>您还没有补充完整：</strong><br>
		<span style="color:red;">'.implode('<br>', $nones).'</span><br><br>
		<strong>请按照以下的说明来完成本活动：</strong>
		<ul class="task">
		<li>1. <a href="cp.php?ac=profile" target="_blank">新窗口打开设置页面</a>；</li>
		<li>2. 在新打开的设置页面中，输入你的真实姓名并上传真实照片为头像，等待管理员审核通过后完成。</li>
		</ul>';

}

?>
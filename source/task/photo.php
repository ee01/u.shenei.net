<?php
/*
 [UCenter Home] (C) 2007-2008 Comsenz Inc.
*/

if(!defined('IN_UCHOME')) {
 exit('Access Denied');
}

$albumcount = getcount('album', array('uid'=>$space['uid']));
if($albumcount) {

 $task['done'] = 1;//活动完成

} else {

 //活动完成向导
 $task['guide'] = '
 <strong>请按照以下的说明来参与本活动：</strong>
 <ul class="task">
 <li>1. <a href="cp.php?ac=upload" target="_blank">新窗口打开图片上传页面</a>；</li>
 <li>2. 在新打开的页面中，选择要上传的相片，并开始上传。</li>
 </ul>';

}

?>
<?php
/*
 [UCenter Home] (C) 2007-2008 Comsenz Inc.
*/

if(!defined('IN_UCHOME')) {
 exit('Access Denied');
}

$threadcount = getcount('thread', array('uid'=>$space['uid']));
if($threadcount) {

 $task['done'] = 1;//活动完成

} else {

 //活动完成向导
 $task['guide'] = '
 <strong>请按照以下的说明来参与本活动：</strong>
 <ul class="task">
 <li>1. <a href="cp.php?ac=thread" target="_blank">新窗口打开发表群组新话题页面</a>；</li>
 <li>2. 在新打开的页面中，书写自己的第一个话题，并进行发布。</li>
 </ul>';

}

?>
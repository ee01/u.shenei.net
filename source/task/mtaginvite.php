<?php
/*
 [UCenter Home] (C) 2007-2008 Comsenz Inc.
*/

if(!defined('IN_UCHOME')) {
 exit('Access Denied');
}
//$mtaginvitecount>=5中的5为邀请人发出的未被受邀人处理的邀请数, 可根据需要修改
$mtaginvitecount = getcount('mtaginvite', array('fromuid'=>$space['uid']));
if($mtaginvitecount>=1) {

 $task['done'] = 1;//活动完成

} else {

 //活动完成向导
 $task['guide'] = '
 <strong>请按照以下的说明来参与本活动：</strong>
 <ul class="task">
 <li>1. <a href="space.php?do=mtag" target="_blank">新窗口打开"我的群组"页面</a>；</li>
 <li>2. 在新打开的页面中，点击进入自己创建的群组，点击“好友邀请”链接，邀请好友加入。</li>
 <li>3. 返回有奖活动页面，点击“领取奖励”按钮。</li>
 <li>注意：领取奖励必须在好友接受或忽略邀请之前，邀请后请立即返回有奖活动页面领取奖励。</li>
 </ul>';

}

?>
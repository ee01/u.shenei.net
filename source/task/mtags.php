<?php
/*
        [UCenter Home] (C) 2007-2008 Comsenz Inc.
*/

if(!defined('IN_UCHOME')) {
        exit('Access Denied');
}

$groupcount = getcount('tagspace', array('uid'=>$space['uid']));
if($groupcount>=3) {

        $task['done'] = 1;//活动完成

} else {

        //向导
        $task['guide'] = '
                <strong>请按照以下的说明来参与本活动：</strong>
                <ul class="task">
                <li>1. <a href="cp.php?ac=mtag" target="_blank">新窗口打开"加入/创建群组"页面</a>；</li>
                <li>2. 在新打开的页面中，您可以加入现有的群组或创建新的群组并自动加入；</li>
                <li>3. 您必须加入指定数量的群组才能获得活动奖励积分。</li>
                </ul>';
}

?>
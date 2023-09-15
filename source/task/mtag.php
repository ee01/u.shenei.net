<?php
/*
        [UCenter Home] (C) 2007-2008 Comsenz Inc.
*/

if(!defined('IN_UCHOME')) {
        exit('Access Denied');
}
//下行b.fieldid = 1中的1为群组栏目ID, 可根据需要自行修改.
if($_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('tagspace')." AS a INNER JOIN ".tname('mtag')." AS b ON a.tagid = b.tagid WHERE a.uid = ".$_SGLOBAL['supe_uid']." AND b.fieldid = 3 LIMIT 1"), 0)) {

        $task['done'] = 1;//活动完成

} else {

        //向导
        $task['guide'] = '
                <strong>请按照以下的说明来参与本活动：</strong>
                <ul class="task">
                <li>1. <a href="cp.php?ac=mtag" target="_blank">新窗口打开"加入/创建群组"页面</a>；</li>
                <li>2. 在新打开的页面中，按提示加入指定类别的群组即可获得奖励积分。</li>
                </ul>';
}

?>
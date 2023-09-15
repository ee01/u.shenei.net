<?php
/*
        [UCenter Home] (C) 2007-2008 Comsenz Inc.
*/

if(!defined('IN_UCHOME')) {
        exit('Access Denied');
}

if(!empty($space['theme'])) {

        $task['done'] = 1;//活动完成

} else {

        //向导
        $task['guide'] = '
                <strong>请按照以下的说明来参与本活动：</strong>
                <ul class="task">
                <li>1. <a href="cp.php?ac=theme" target="_blank">新窗口打开"主页风格设置"页面</a>；</li>
                <li>2. 在新打开的设置页面中，选择满意的风格并启用。</li>
                </ul>';
}

?>
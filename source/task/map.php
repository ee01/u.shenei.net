<?php
/*
        [UCenter Home] (C) 2007-2008 Comsenz Inc.
*/

if(!defined('IN_UCHOME')) {
        exit('Access Denied');
}


$query = $_SGLOBAL['db']->query("SELECT lat,lng,zoom FROM ".tname('spacefield')." WHERE uid='$space[uid]'");
$value = $_SGLOBAL['db']->fetch_array($query);
if($value[lat] && $value[lng] && $value[zoom]) {

        $task['done'] = 1;//活动完成

} else {

        //活动完成向导
		        $task['guide'] = '
                <strong>请按照以下的说明来参与本活动：</strong>
                <ul class="task">
                <li>1. <a href="cp.php?ac=profile&op=contact" target="_blank">新窗口打开 联系方式 设置</a>；</li>
                <li>2. 点击 "快添加您的家乡在地图上的位置吧，认识更多的朋友" 长条按钮；</li>
                <li>3. 在地图上把气球拖动到你的家乡的地方；</li>
                <li>4. 再在新窗口打开【<a href="city.php" target="_blank">大学城那块地</a>】，就可以看见自己和好友的位置了!</li>
                </ul>';

}
mysql_close($link);
?>
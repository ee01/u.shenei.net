<?php
/*
        [UCenter Home] (C) 2007-2008 Comsenz Inc.
*/

if(!defined('IN_UCHOME')) {
        exit('Access Denied');
}


//$query = $_SGLOBAL['db']->query("SELECT itemid FROM sky.sky_items WHERE uid='$space[uid]' and dateline>(".time()."-86400)");
//$app_sky = $_SGLOBAL['db']->result($query, 0);
//if($app_sky>=1) {
$dbh = @mysql_connect($_SC['dbhost'].":3306",'shenei_sky','shenei01sky') or die ('数据库连接失败！');
@mysql_select_db("shenei_sky", $dbh) or die('数据库选定失败！');
mysql_query("set names 'utf8'");
$sql = "SELECT dateline FROM sky_items WHERE uid='$space[uid]' order by dateline desc";
$query = mysql_query($sql, $dbh) or die ('数据库读取失败！');
$value = mysql_fetch_array($query);

if(sgmdate("Y-m-d",$value[dateline])==sgmdate("Y-m-d",time())) {

        $task['done'] = 1;//活动完成

} else {

        //活动完成向导
		        $task['guide'] = '
                <strong>请按照以下的说明来参与本活动：</strong>
                <ul class="task">
                <li>1. <a href="http://sky.shenei.net" target="_blank">新窗口打开【大学城的天空】</a>；</li>
                <li>2. 点击左侧展开按钮，登录发布新星星后就可以来领取积分了。</li>
                </ul>';

}
mysql_close($link);
?>
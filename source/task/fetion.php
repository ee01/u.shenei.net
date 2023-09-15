<?php
/*
        [UCenter Home] (C) 2007-2008 Comsenz Inc.
*/

if(!defined('IN_UCHOME')) {
        exit('Access Denied');
}


$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('fetion')." WHERE uid='$space[uid]'");
$result = $_SGLOBAL['db']->fetch_array($query);
if($result['enable']) {

        $task['done'] = 1;//活动完成

} else {

        //活动完成向导
		        $task['guide'] = '
                <strong>请按照以下的说明来参与本活动：</strong>
                <ul class="task">
                <li>1. 如果没有设置手机的先<a href="cp.php?ac=profile&op=contact" target="_blank">新窗口打开 联系方式 设置</a>；</li>
                <li>2. <a href="cp.php?ac=fetion&op=setuser" target="_blank">新窗口打开 飞信账号设置</a>；</li>
                <li>3. 填写您的飞信登陆密码后点击“发送验证码”，将输入收到的验证码，点击“验证”；</li>
                <li>4. 接着就可以去<a href="cp.php?ac=fetion" target="_blank">站内飞信</a>里骚扰别人拉！也可以到<a href="cp.php?ac=fetion&op=friend" target="_blank">站内飞信</a>去免费发送手机短信给朋友。</li>
                </ul>';

}
mysql_close($link);
?>
<?php
/*
author:www.dmyuan.com
$Id: callbackmail.php 2009.11.13 diomen
*/
include_once('./common.php');

if(!defined('IN_UCHOME')) {
        exit('Access Denied');
}

$pernum = 4;//一次发送邮件个数，太多容易超时和服务器被封杀

$lockfile = S_ROOT.'./data/callbackmail.lock';
@$filemtime = filemtime($lockfile);
$timespan = $_SGLOBAL[timestamp]-7*24*3600;//7天未登录就发送邮件提醒

touch($lockfile);

//防止超时
set_time_limit(0);

include_once(S_ROOT.'./source/function_sendmail.php');
$message = 'Hi，您好，您已经很久没有登录点梦缘了吧，每天都有几百名新缘友加入，每天都会有上千名缘友在这里找寻幸福！也许缘分就在一瞬间，要把握住哦，快回家看看吧！<br/>如果您在使用中遇到问题，请联系网站底部的客服QQ！';
$query = $_SGLOBAL['db']->query("SELECT s.uid,s.username,sf.email FROM ".tname('space')." s , ".tname('spacefield')." sf WHERE  s.uid=sf.uid AND s.lastsend<='$timespan' AND s.lastlogin<='$timespan' ORDER BY s.lastlogin LIMIT 0,$pernum");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
        $subject = 'Hi，'.$value[username].'，您在点梦缘有新消息了！';
        if(!$flag = sendmail($value[email], $subject, $message)) {
                runlog('sendmail', "$value[email] sendmail failed.");
        }
        //更新用户最后发送时间
        $_SGLOBAL['db']->query("UPDATE ".tname('space')." SET lastsend='$_SGLOBAL[timestamp]' WHERE uid = '$value[uid]'");        
}

?>
<?php
/*
author:www.dmyuan.com
$Id: callbackmail.php 2009.11.13 diomen
*/
include_once('./common.php');

if(!defined('IN_UCHOME')) {
        exit('Access Denied');
}

$pernum = 4;//һ�η����ʼ�������̫�����׳�ʱ�ͷ���������ɱ

$lockfile = S_ROOT.'./data/callbackmail.lock';
@$filemtime = filemtime($lockfile);
$timespan = $_SGLOBAL[timestamp]-7*24*3600;//7��δ��¼�ͷ����ʼ�����

touch($lockfile);

//��ֹ��ʱ
set_time_limit(0);

include_once(S_ROOT.'./source/function_sendmail.php');
$message = 'Hi�����ã����Ѿ��ܾ�û�е�¼����Ե�˰ɣ�ÿ�춼�м�������Ե�Ѽ��룬ÿ�춼������ǧ��Ե����������Ѱ�Ҹ���Ҳ��Ե�־���һ˲�䣬Ҫ����סŶ����ؼҿ����ɣ�<br/>�������ʹ�����������⣬����ϵ��վ�ײ��Ŀͷ�QQ��';
$query = $_SGLOBAL['db']->query("SELECT s.uid,s.username,sf.email FROM ".tname('space')." s , ".tname('spacefield')." sf WHERE  s.uid=sf.uid AND s.lastsend<='$timespan' AND s.lastlogin<='$timespan' ORDER BY s.lastlogin LIMIT 0,$pernum");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
        $subject = 'Hi��'.$value[username].'�����ڵ���Ե������Ϣ�ˣ�';
        if(!$flag = sendmail($value[email], $subject, $message)) {
                runlog('sendmail', "$value[email] sendmail failed.");
        }
        //�����û������ʱ��
        $_SGLOBAL['db']->query("UPDATE ".tname('space')." SET lastsend='$_SGLOBAL[timestamp]' WHERE uid = '$value[uid]'");        
}

?>
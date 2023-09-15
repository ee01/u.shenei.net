<?php
/*
	插件名称：PicWall
	作者：54alin	http://hialin.com
	本插件免费使用，欢迎到处传播！
	尊重作者的劳动，请勿删除本信息！
*/
include_once(S_ROOT.'./source/function_cp.php');
$icon = 'album';
$title_template = '{actor}&nbsp;正在浏览&nbsp;<a href="space.php?do=picwall">照片墙</a>&nbsp;...';

feed_add($icon, $title_template);
include template('picwall');

?>
<?php
/*
	������ƣ�PicWall
	���ߣ�54alin	http://hialin.com
	��������ʹ�ã���ӭ����������
	�������ߵ��Ͷ�������ɾ������Ϣ��
*/
include_once(S_ROOT.'./source/function_cp.php');
$icon = 'album';
$title_template = '{actor}&nbsp;�������&nbsp;<a href="space.php?do=picwall">��Ƭǽ</a>&nbsp;...';

feed_add($icon, $title_template);
include template('picwall');

?>
<?php


require_once './common.php';
require_once './source/function_common.php';

include_once(S_ROOT.'./source/function_cp.php');
$icon = 'taobaojie';
$title_template = '{actor} ����ʹ��<a href="fetion.php">��ѷ���</a>�����ţ�';
feed_add($icon, $title_template);

include template('fetion');

?>
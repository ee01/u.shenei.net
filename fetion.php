<?php


require_once './common.php';
require_once './source/function_common.php';

include_once(S_ROOT.'./source/function_cp.php');
$icon = 'taobaojie';
$title_template = '{actor} 正在使用<a href="fetion.php">免费飞信</a>发短信！';
feed_add($icon, $title_template);

include template('fetion');

?>
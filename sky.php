<?php
$suijishu=rand(1,4);
require_once './common.php';
require_once './source/function_common.php';

include_once(S_ROOT.'./source/function_cp.php');
$icon = 'sky';

checklogin();

if($suijishu==1){
	$title_template = '{actor} 正朝着<a href="sky.php">大学城的天空</a>述说心愿！';
}
if($suijishu==2){
	$title_template = '{actor} 刚刚溜进了<a href="sky.php">大学城的天空</a>正在许愿！';
}
if($suijishu==3){
	$title_template = '{actor} 翱翔在<a href="sky.php">大学城的天空</a>里，写下了自己的愿望！';
}
if($suijishu==4){
	$title_template = '现在就去看看<a href="sky.php">大学城的天空</a>开始许愿！';
}
feed_add($icon, $title_template);


include template('sky');

?>
<?php


$suijishu=rand(1,9);
require_once './common.php';
require_once './source/function_common.php';

include_once(S_ROOT.'./source/function_cp.php');
$icon = 'qecn';
if($suijishu==1){
	$title_template = '{actor} 用了“<a href="askqecn.php">求医通</a>”感觉很不错，大家有空也可以试一试！';
}
if($suijishu==2){
	$title_template = '{actor} 正在“<a href="askqecn.php">求医通</a>”向在线医生咨询呢！';
}
if($suijishu==3){
	$title_template = '{actor} 自从使用“<a href="askqecn.php">求医通</a>”，很少生病了！';
}
if($suijishu==4){
	$title_template = '{actor} 正在用“<a href="askqecn.php">求医通</a>”呢；有健康需求的也可以用哦！';
}
if($suijishu==5){
	$title_template = '“<a href="askqecn.php">求医通</a>”很好，很实用！{actor} 正在咨询在线医生呢！';
}
if($suijishu==6){
	$title_template = '{actor} 找到了“<a href="askqecn.php">求医通</a>”，马上就拥有了一个免费的在线医生！';
}
if($suijishu==7){
	$title_template = '小道消息{actor} 正在“<a href="askqecn.php">求医通</a>”咨询健康问题，看来他很关注健康问题！';
}
if($suijishu==8){
	$title_template = '{actor} 在“<a href="askqecn.php">求医通</a>”咨询健康问题呢，有健康需求的都来吧！';
}
if($suijishu==9){
	$title_template = '{actor} 告诉大家：求医就上“<a href="http://www.qe.cn">求医网</a>”，问病就用“<a href="askqecn.php">求医通</a>”！';
}
feed_add($icon, $title_template);
include template('askqecn');

?>

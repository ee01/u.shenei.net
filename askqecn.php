<?php


$suijishu=rand(1,9);
require_once './common.php';
require_once './source/function_common.php';

include_once(S_ROOT.'./source/function_cp.php');
$icon = 'qecn';
if($suijishu==1){
	$title_template = '{actor} ���ˡ�<a href="askqecn.php">��ҽͨ</a>���о��ܲ�������п�Ҳ������һ�ԣ�';
}
if($suijishu==2){
	$title_template = '{actor} ���ڡ�<a href="askqecn.php">��ҽͨ</a>��������ҽ����ѯ�أ�';
}
if($suijishu==3){
	$title_template = '{actor} �Դ�ʹ�á�<a href="askqecn.php">��ҽͨ</a>�������������ˣ�';
}
if($suijishu==4){
	$title_template = '{actor} �����á�<a href="askqecn.php">��ҽͨ</a>���أ��н��������Ҳ������Ŷ��';
}
if($suijishu==5){
	$title_template = '��<a href="askqecn.php">��ҽͨ</a>���ܺã���ʵ�ã�{actor} ������ѯ����ҽ���أ�';
}
if($suijishu==6){
	$title_template = '{actor} �ҵ��ˡ�<a href="askqecn.php">��ҽͨ</a>�������Ͼ�ӵ����һ����ѵ�����ҽ����';
}
if($suijishu==7){
	$title_template = 'С����Ϣ{actor} ���ڡ�<a href="askqecn.php">��ҽͨ</a>����ѯ�������⣬�������ܹ�ע�������⣡';
}
if($suijishu==8){
	$title_template = '{actor} �ڡ�<a href="askqecn.php">��ҽͨ</a>����ѯ���������أ��н�������Ķ����ɣ�';
}
if($suijishu==9){
	$title_template = '{actor} ���ߴ�ң���ҽ���ϡ�<a href="http://www.qe.cn">��ҽ��</a>�����ʲ����á�<a href="askqecn.php">��ҽͨ</a>����';
}
feed_add($icon, $title_template);
include template('askqecn');

?>

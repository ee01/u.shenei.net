<?php
$suijishu=rand(1,4);
require_once './common.php';
require_once './source/function_common.php';

include_once(S_ROOT.'./source/function_cp.php');
$icon = 'sky';

checklogin();

if($suijishu==1){
	$title_template = '{actor} ������<a href="sky.php">��ѧ�ǵ����</a>��˵��Ը��';
}
if($suijishu==2){
	$title_template = '{actor} �ո������<a href="sky.php">��ѧ�ǵ����</a>������Ը��';
}
if($suijishu==3){
	$title_template = '{actor} ������<a href="sky.php">��ѧ�ǵ����</a>�д�����Լ���Ը����';
}
if($suijishu==4){
	$title_template = '���ھ�ȥ����<a href="sky.php">��ѧ�ǵ����</a>��ʼ��Ը��';
}
feed_add($icon, $title_template);


include template('sky');

?>
<?php
include_once('./common.php');
include_once(S_ROOT.'./source/function_cp.php');

$uid=$_POST['uid'];
$cardnum=$_POST['cardnum'];
$cardpsw=$_POST['cardpsw'];
$money=$_POST['money'];
$overtime=$_POST['overtime'];
$liyong=$_POST['liyong'];
if($cardnum!='') {
$setarrno = array(
		'ztinfo' => '��'
	);
updatetable('app_card', $setarrno, array('cardnum' => $cardnum));
$message= "<br>���ֳ�ֵ������֪ͨ��<br>".$liyong."<br>���ţ�".$cardnum."<br>���룺".$cardpsw."<br>���֣�".$money."��<br>��Ч�ڣ�".$overtime."<br>������Ч����ʹ�ã�������Ͻ��еġ����á�->><a href=cp.php?ac=card target=_blank>�����ֳ�ֵ����</a>ʹ�ã�";
notification_add($uid, "app", $message );

$subject="���ֳ�ֵ������֪ͨ";
$query = $_SGLOBAL['db']->query("SELECT email, emailcheck FROM ".tname('spacefield')." WHERE uid = $uid ");
$value = $_SGLOBAL['db']->fetch_array($query);
if($value['email']) {
smail(0, $value['email'], $subject, $message);
}
showmessage("���Ż��ֳ�ֵ���ɹ���", 'admincp.php?ac=card');
}

?>
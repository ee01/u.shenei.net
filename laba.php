<?php
//���õĶ���
$gacts = array(1=>'��',2=>'����',3=>'˽��',4=>'��Ц',5=>'����',6=>'�и�',7=>'��̾',8=>'YY',9=>'Ѱ��');

//ʱ���趨
$gtimes = array(3,4,5,6,7,8,9,10);

//����ʾһ�뻨�ѵĽ��
$gcost = 5;
//����ֵ
$gMaxScore = 200;
//ϵͳ����ԱID�����޸ĳɹ���ԱID��������ɾ�������˷������ʴ�
define("ADMIN_ID","1");

include_once('./common.php');
include_once(S_ROOT.'./source/function_cp.php');

//�Ƿ�ر�վ��
checkclose();
checklogin();
//��ȡ�ռ���Ϣ
$space = getspace($_SGLOBAL['supe_uid']);
if(empty($space)) {
	showmessage('space_does_not_exist');
}
//ʵ����֤
ckrealname('ask');
//��Ҫ�ϴ�ͷ��
if($_SCONFIG['need_avatar'] && empty($space['avatar'])) {
	if(empty($return)) showmessage('no_privilege_avatar');
	$result = false;
}
//��ȡ��ǰ�û��Ŀռ���Ϣ
$space = getspace($_SGLOBAL['supe_uid']);
//������
$dos = array('cp');

//���»session
if($_SGLOBAL['supe_uid']) {
        getmember(); //��ȡ��ǰ�û���Ϣ
        updatetable('session', array('lastactivity' => $_SGLOBAL['timestamp']), array('uid'=>$_SGLOBAL['supe_uid']));
}


//��ȡ����
$isinvite = 0;

$do = (!empty($_GET['do']) && in_array($_GET['do'], $dos))? $_GET['do'] : 'cp';
if (empty($do)) {
	showmessage('no_app_do' );
}
//�Ƿ񹫿�
checklogin();//��Ҫ��¼

//��ȡ�ռ�
if(empty($_SGLOBAL['supe_uid'])) {
	ssetcookie('_refer', rawurlencode($_SERVER['REQUEST_URI']));
	showmessage('to_login', 'do.php?ac=login', 0);
}
$uid = $_SGLOBAL['supe_uid'];
 

function updatecredit($uid, $credit, $method='+'){
	global $_SGLOBAL;
	$credit = intval($credit);
	if (empty($credit)) {
		return;
	}
	$sqlcredit = ($credit > 0) ? " {$method} {$credit} " : " {$method} {$credit} ";
	$newcredit = $num * $credit;
	$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET credit=credit {$sqlcredit} WHERE uid = $uid ");
}

//����
include_once(S_ROOT."./laba/{$do}.php");
?>
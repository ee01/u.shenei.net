<?php

//Powered by Jiekii.com
//QQ��357754800

//���
$gEumsType = array(1 => '�����¼�', 2 => '�������', 3 => '��������', 4 => 'Ӱ��̨��', 5 => '��������', 6 => '���ں�', 7 => '�������', 8 => '��Ĭ��Ц', 9 => '������¼', 10 => 'ʫ�ʸ踳' );

//����ֵ
$gMaxScore = 0;
//ϵͳ����ԱID�����޸ĳɹ���ԱID��������ɾ�������˷�������¼
define("ADMIN_ID","1");	

include_once('./common.php');

//�Ƿ�ر�վ��
checkclose();
//��ȡ��ǰ�û��Ŀռ���Ϣ
$space = getspace($_SGLOBAL['supe_uid']);
//��Ӳ˵�
window_set1('������Ϣ', "ana.php"); 

//������
$dos = array('ana', 'cp');
//����ķ���
$acs = array(
'ana' => array('index', 'post', 'view', 'cp'),
'cp' => array('post', 'update', 'del')
);


$gEumsStatus = array(1 => 'δ����', 2 => '�Ѽ���' );


//���»session
if($_SGLOBAL['supe_uid']) {
        getmember(); //��ȡ��ǰ�û���Ϣ
        updatetable('session', array('lastactivity' => $_SGLOBAL['timestamp']), array('uid'=>$_SGLOBAL['supe_uid']));
}

//��Ӵ���
window_set1("������¼", 'ana.php');


//��ȡ����
$isinvite = 0;

$do = (!empty($_GET['do']) && in_array($_GET['do'], $dos))? $_GET['do'] : 'ana';
$ac = (!empty($_GET['ac']) && in_array($_GET['ac'], $acs[$do])) ? $_GET['ac'] : 'index';
if (empty($do)) {
	showmessage('no_app_do' );
}

if( @file_exists(S_ROOT."./ana/ana_install.php") ) {
	$ac = "install";
}

//�Ƿ񹫿�
checklogin();//��Ҫ��¼

//��ȡ�ռ�
if(empty($_SGLOBAL['supe_uid'])) {
	ssetcookie('_refer', rawurlencode($_SERVER['REQUEST_URI']));
	showmessage('to_login', 'do.php?ac=login', 0);
}
$uid = $_SGLOBAL['supe_uid'];
 

function updatecredit($uid, $credit, $method='+')
{
	global $_SGLOBAL;
	$credit = intval($credit);
	if (empty($credit)) {
		return;
	}
	$sqlcredit = ($credit > 0) ? " {$method} {$credit} " : " {$method} {$credit} ";
	$newcredit = $num * $credit;
	$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET credit=credit {$sqlcredit} WHERE uid = $uid ");
	//$_SGLOBAL['db']->query("UPDATE ".tname('session')." SET credit=credit {$sqlcredit} WHERE uid = $uid ");
}

//����
include_once(S_ROOT."./ana/{$do}_{$ac}.php");
function window_set1($a,$b='',$c='')
{
	return ;
}
?>
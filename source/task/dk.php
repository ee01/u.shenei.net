<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: sample.php 11056 2009-02-09 01:59:47Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//���ñ�����$task['done'] (��ɱ�ʶ����) $task['result'] (�������) $task['guide'] (������)

//�ж��û��Ƿ����������

if(sgmdate('H')<9 ||(sgmdate('H')==9 && sgmdate('i')<30))
{
	$task['credit'] = $task['credit']*2;
}
$done = 1;

if($done) {

	$task['done'] = 1;//�������
	//$task['result'] = '21������ô��Ǯ�����ף������ڴ��칤�ʰ���ÿ��09��30֮ǰ�򿨣����ʷ���Ŷ����';//�û��������񿴵�������˵����֧��html����
	$task['result'] = '<p>��������һ�� ��������־������ �����ɣ�</p>';
	$task['result'] .= '<br><ul class="line_list">';

	$bloglist = array();
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('blog')." WHERE hot>='3' AND friend='0' ORDER BY dateline DESC LIMIT 0,20");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['uid'], $value['username']);
		$bloglist[] = $value;
	}
	realname_get();
	
	foreach ($bloglist as $value) {
		$task['result'] .= "<li><a href=\"space.php?uid=$value[uid]\" target=\"_blank\"><strong>".$_SN[$value['uid']]."</strong></a>��<a href=\"space.php?uid=$value[uid]&do=blog&id=$value[blogid]\" target=\"_blank\">$value[subject]</a> <span class=\"gray\">($value[hot]���Ƽ�)</span></li>";
	}
	$task['result'] .= '</ul>';
} else {

	//���������
	$task['guide'] = '21������ô��Ǯ�����ף������ڴ��칤�ʰ���ÿ��09��30֮ǰ�򿨣����ʷ���Ŷ����'; //ָ���û���β������������˵����֧��html����

}

?>
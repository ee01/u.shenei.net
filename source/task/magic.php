<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: magic.php 11056 2009-02-09 01:59:47Z Micwolfzhou $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

$done = 0;


//�������
$mid = rand(0,9);
$magic=Array(
	0 => Array(
			'mid' => 'visit',
			'name' => '���ÿ�'),
	1 => Array(
			'mid' => 'gift',
			'name' => '�����'),
	2 => Array(
			'mid' => 'color',
			'name' => '��ɫ��'),
	3 => Array(
			'mid' => 'hot',
			'name' => '�ȵ��'),
	4 => Array(
			'mid' => 'icon',
			'name' => '�ʺ絰'),
	5 => Array(
			'mid' => 'flicker',
			'name' => '�ʺ���'),
	6 => Array(
			'mid' => 'call',
			'name' => '������'),
	7 => Array(
			'mid' => 'frame',
			'name' => '���'),
	8 => Array(
			'mid' => 'bgimage',
			'name' => '��ֽ'),
	9 => Array(
			'mid' => 'doodle',
			'name' => 'Ϳѻ��')
	);

$querysql = "select * from ".tname("blog")." where uid =".$_SGLOBAL['supe_uid']." order by dateline desc limit 1";
	$query = $_SGLOBAL['db']->query($querysql);
//�ж��Ƿ�д����־
      if($data = $_SGLOBAL['db']->fetch_array($query)) {
 //�ж��Ƿ����д����־
         if (sgmdate("Y-m-d",$_SGLOBAL['timestamp'])==sgmdate("Y-m-d",$data['dateline'])) {
  	
 //�ж�������Ϊ����ֹˢ��Ʒ

$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname("magicinlog")." WHERE uid='$_SGLOBAL[supe_uid]' AND (mid='visit' or mid='gift' or mid='color' or mid='hot' or mid='icon' or mid='flicker' or mid='call' or mid='frame' or mid='bgimage' or mid='doodle') AND type=2 and fromid=0 order by dateline desc limit 0,1");
if ($data = $_SGLOBAL['db']->fetch_array($query)) {
 if (sgmdate("Y-m-d",$_SGLOBAL['timestamp'])!=sgmdate("Y-m-d",$data['dateline'])) {
     
//���͵���
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname("usermagic")." WHERE uid='$_SGLOBAL[supe_uid]' AND mid='".$magic[$mid][mid]."'");
	if($value = $_SGLOBAL['db']->fetch_array($query)) {
		$count = $value['count'] + 1;
	} else {
		$count = 1;
	}
	$_SGLOBAL['db']->query("REPLACE ".tname('usermagic')."(uid, username, mid, count) VALUES ('$_SGLOBAL[supe_uid]', '$_SGLOBAL[username]', '".$magic[$mid][mid]."', '$count')");
//������־
	inserttable('magicinlog',
		array(
			'uid'=>$_SGLOBAL['supe_uid'],
			'username'=>$_SGLOBAL['supe_username'],
			'mid'=>$magic[$mid][mid],
			'count'=>1,
			'type'=>2,
			'credit'=>0,
			'dateline'=>$_SGLOBAL['timestamp']));
}else{
//����
	$got = 1;
}
}
 else {

//���͵���
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname("usermagic")." WHERE uid='$_SGLOBAL[supe_uid]' AND mid='".$magic[$mid][mid]."'");
	if($value = $_SGLOBAL['db']->fetch_array($query)) {
		$count = $value['count'] + 1;
	} else {
		$count = 1;
	}
	$_SGLOBAL['db']->query("REPLACE ".tname('usermagic')."(uid, username, mid, count) VALUES ('$_SGLOBAL[supe_uid]', '$_SGLOBAL[username]', '".$magic[$mid][mid]."', '$count')");
//������־
	inserttable('magicinlog',
		array(
			'uid'=>$_SGLOBAL['supe_uid'],
			'username'=>$_SGLOBAL['supe_username'],
			'mid'=>$magic[$mid][mid],
			'count'=>1,
			'type'=>2,
			'credit'=>0,
			'dateline'=>$_SGLOBAL['timestamp']));
}


			$task['done'] = 1;//����
			if(!$got){
				$task['result'] = '<div style="padding:10px 0 5px 0;color:red;font-weight:bold;">���ѳɹ������һ��������ߣ�<a href="cp.php?ac=magic&view=me&mid='.$magic[$mid][mid].'" target="_blank">'.$magic[$mid][name].'<br><img src="image/magic/'.$magic[$mid][mid].'.gif" alt="'.$magic[$mid][name].'" /><br>�������򿪿�����ʲô�ã�</a></div>';
			}else{
				$task['result'] = '<div style="padding:10px 0 5px 0;color:red;font-weight:bold;">��⵽�����������ɣ��Ѿ����������ߣ����������ɡ�</div>';
			}

} else {

	//���������
	$task['guide'] = '
		<strong>�밴�����µ�˵�������뱾����</strong>
		<ul>
		<li>1. <a href="cp.php?ac=blog" target="_blank">�´��ڴ򿪷�����־ҳ��</a>��</li>
		<li>2. ���´򿪵�ҳ���У���д�Լ�����־�������з�����</li>
		</ul>';

}
}
else {

	//���������
	$task['guide'] = '
		<strong>�밴�����µ�˵�������뱾����</strong>
		<ul>
		<li>1. <a href="cp.php?ac=blog" target="_blank">�´��ڴ򿪷�����־ҳ��</a>��</li>
		<li>2. ���´򿪵�ҳ���У���д�Լ�����־�������з�����</li>
		</ul>';

}

?>
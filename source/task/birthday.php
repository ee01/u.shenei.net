<?php
/*
 [UCenter Home] (C) 2007-2008 Comsenz Inc.

*/

if(!defined('IN_UCHOME')) {
 exit('Access Denied');
}

//Ҫ���͵�ף����
$maxbless = 2;
$currTime = $_SGLOBAL['timestamp'];
$currMonth = intval(date("m",$currTime));
$currDay = intval(date("d",$currTime));
$currYear = intval(date("Y",$currTime));
$today = mktime(0,0,0,$currMonth,$currDay,$currYear);

//��������յĻ�Ա��
$birthdaysql = "SELECT count(sf.uid) FROM ".tname('spacefield')." sf
 WHERE sf.birthmonth = $currMonth AND sf.birthday = $currDay
 LIMIT 0,1";
$birthdayquery = $_SGLOBAL['db']->query($birthdaysql);
$birthdaycount = $_SGLOBAL['db']->result($birthdayquery);

//�������ŵ����ջ�Ա��
/*
$pmsql = "SELECT count(sf.uid) FROM ".tname('spacefield')." sf
 WHERE sf.birthmonth = $currMonth AND sf.birthday = $currDay
 AND sf.uid IN (SELECT p.msgtoid FROM ".UC_DBTABLEPRE."pms p WHERE p.msgfromid = ".$_SGLOBAL['supe_uid']." AND (p.dateline BETWEEN $today AND $today+86400))
 LIMIT 0,1";
$pmquery = $_SGLOBAL['db']->query($pmsql);
$pmcount = $_SGLOBAL['db']->result($pmquery);
*/
$dbh = @mysql_connect(UC_DBHOST.":3306",UC_DBUSER,UC_DBPW) or die ('���ݿ�����ʧ�ܣ�');
@mysql_select_db("shenei_uc", $dbh) or die('���ݿ�ѡ��ʧ�ܣ�');
mysql_query("set names '".UC_DBCHARSET."'");
$sql = "SELECT p.msgtoid FROM ".UC_DBTABLEPRE."pms p WHERE p.msgfromid = ".$_SGLOBAL['supe_uid']." AND (p.dateline BETWEEN $today AND $today+86400)";
$query = mysql_query($sql, $dbh) or die ('���ݿ��ȡʧ�ܣ�');
$result = mysql_fetch_array($query);
foreach ($result as $item) {
	$instr .= $item.',';
}
$instr = substr($instr,0,strlen($instr)-1);
$pmsql = "SELECT count(sf.uid) FROM ".tname('spacefield')." sf WHERE sf.birthmonth = $currMonth AND sf.birthday = $currDay AND sf.uid IN ('$instr') LIMIT 0,1";
$pmquery = $_SGLOBAL['db']->query($pmsql);
$pmcount = $_SGLOBAL['db']->result($pmquery);

if($birthdaycount && ($pmcount==$birthdaycount || $pmcount>=$maxbless)) {

 $task['done'] = 1;//����
 if($space['birthmonth']==0 || $space['birthday']==0) {
 $task['result'] ='<p><a href="cp.php?ac=task&taskid=2">�����������ϲ����������Ա�������Ա�ܹ����ҷ�������ף����</a></p>';
 }

} else {

 if($birthdaycount==0) {
 //����û�л�Ա������
 $task['guide'] = '<p>����û�л�Ա�����ա�</p>';

  } else {
   
   $birthdaylist = array();
   $query = $_SGLOBAL['db']->query("SELECT s.uid,s.username,s.name,s.namestatus FROM ".tname('spacefield')." sf
   LEFT JOIN ".tname('space')." s ON s.uid=sf.uid
   WHERE sf.birthmonth = $currMonth AND sf.birthday = $currDay
   ORDER BY rand()
   LIMIT 0,$maxbless");
   while ($value = $_SGLOBAL['db']->fetch_array($query)) {
   realname_set($value['uid'], $value['username'], $value['name'], $value['namestatus']);
   $birthdaylist[] = $value;
   }
   
   
   if($birthdaylist) {
   $task['guide'] .= '<p>���»�Ա��������գ���Ϊ������������ף���ɣ�</p>';
   $task['guide'] .= '<ul class="avatar_list s_clear">';
   foreach ($birthdaylist as $key => $value) {
   $task['guide'] .= '<li>
   <a href="space.php?uid='.$value['uid'].'" target="_blank">'.avatar($value['uid'], 'small').'</a>
   <p><a href="space.php?uid='.$value['uid'].'" target="_blank">'.$_SN[$value['uid']].'</a></p>
   <p><a href="cp.php?ac=pm&uid='.$value['uid'].'" id="a_pm_'.$key.'"  target="_blank">����ף��</a></p>
   </li>';
   }
   $task['guide'] .= '</ul>';
   }
  }

  $birthdaylist = array();
 $query = $_SGLOBAL['db']->query("SELECT s.uid,s.username,s.name,s.namestatus,sf.birthmonth,sf.birthday FROM ".tname('spacefield')." sf
 LEFT JOIN ".tname('space')." s ON s.uid=sf.uid
 WHERE sf.birthmonth = $currMonth AND sf.birthday >= $currDay+1
 ORDER BY sf.birthday ASC
 LIMIT 0,100");
 while ($value = $_SGLOBAL['db']->fetch_array($query)) {
 realname_set($value['uid'], $value['username'], $value['name'], $value['namestatus']);
 $birthdaylist[] = $value;
 }
 
 if($birthdaylist) {
 $task['guide'] .= '<p>���»������»�Ա�����գ���ӭ��ʱΪ������������ף����</p>';
 $task['guide'] .= '<ul class="avatar_list s_clear">';
 foreach ($birthdaylist as $key => $value) {
 $task['guide'] .= '<li>
 <a href="space.php?uid='.$value['uid'].'" target="_blank">'.avatar($value['uid'], 'small').'</a>
 <p><a href="space.php?uid='.$value['uid'].'" target="_blank">'.$_SN[$value['uid']].'</a></p>
 <p>'.$value['birthmonth'].'��'.$value['birthday'].'��'.'</p>
 </li>';
 }
 $task['guide'] .= '</ul>';
 } else {
 $task['guide'] .= '<p>���������µ�ǰ��û�л�Ա�����ա���ӭ���¼����μӱ����</p>';
 }
  if($space['birthmonth']==0 || $space['birthday']==0) {
 $task['guide'] .='<p><a href="cp.php?ac=task&taskid=2">�����������ϲ����������Ա�������Ա�ܹ����ҷ�������ף����</a></p>';
 }

}

?>
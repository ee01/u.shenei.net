<?php
/*
	��װ���� ִ������ɾ��
	author:�볬��
	website:http://xekee.com
	description:�����ҵ��ʱ����� ���ܻ����ڲ����BUG ϣ�����һ��������
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}
		$arr = array
		      (
			  '0' => '��Ա������',
			  '1' => 'VIPͼ��',
			  '2' => '����������ʾ',
			  '3' => '�鿴��ϵ��ʽ',
			  '4' => '���˿ռ��С<br>(Mb)',
			  '5' => '��������<br>(��)',
			  '6' => '��������ʱ��<br>(��)',
			  '7' => '��������ʱ����<br>(��)'
			   );
				  if ($_SCONFIG[allowdomain] == 1) $arr[] = '������������<br>(�ַ�)';
		       $carr = array();
          $sql = $_SGLOBAL['db']->query("select * from ".tname('usergroup').
		  " where system>='0' or gid='".intval($_SCONFIG[vipapp])."'");
         while ($value = $_SGLOBAL['db']->fetch_array($sql)) 
		{
				      if ($value[maxattachsize] == 0) $value[maxattachsize] = "<span class=\"STYLE1\">������</span>";
				      if ($value[maxfriendnum] == 0) $value[maxfriendnum] = "<span class=\"STYLE1\">������</span>";
				      if ($value[searchinterval] == 0) $value[searchinterval] = "<span class=\"STYLE1\">������</span>";
				      if ($value[postinterval] == 0) $value[postinterval] = "<span class=\"STYLE1\">������</span>";
				      if ($value[domainlength] == 0) $value[domainlength] = "<font color='red'>��ֹʹ��</font>";
			      if ($value[gid] == intval($_SCONFIG[vipapp]))
				  {
				          $value[vus] = intval($_SCONFIG[vipyx]) <= 0 ? "<span class=\"STYLE1\">��</span>" : "<b>X</b>";
				          $value[vip] = "<img src=\"image/pay/vip.gif\" border=\"0\" />";
			              $varr = $value;
				    } else {
				          $value[vus] = "<b>X</b>";
				          $value[vip] = "<img src=\"image/pay/vip2.gif\" border=\"0\" />";
			              $carr[] = $value;
				    }
		     }
			  $carr[] = $varr;
   $buyurl = ($space[credit] > 0 and $_SCONFIG[ctvip] == 1) ? "pay.php?pay=ctvip" : "pay.php?pay=buyvip";
include template('pay_vip');

?>

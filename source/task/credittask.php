<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: renzheng.php 8401 2008-08-12 15:12:55Z PopLong $
*/



if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//�ж��Ƿ�ͨ��ʵ����֤

$space['showcredit'] = getcount('show', array('uid'=>$space['uid']), 'credit');
$space['showcredit'] = intval($space['showcredit']);

if($space[credit] <= 500 && $task[dateline] <= time()-3600 && $space['showcredit']<=1000) {
	$money = rand(1,rand(1,13));
	if		($money==11){$money=rand(20,50);}
	elseif	($money==12){$money=rand(50,100);}
	elseif	($money==13){$money=rand(100,200);}
	updatecredit($_SGLOBAL['supe_uid'], $money, $method='+');
	$moneynow = $space[credit]+$money;

	$task['done'] = 1;//����
	$task['result'] = '
	<font color=red size=3><strong>��������һ�¾��� <font size=5>'.$money.'</font> �����ˡ�<font size=2>�������Ѿ��� <font size=3>'.$moneynow.'</font> ����������</font></strong></font><br><br>
	<font color=red size=2><b>��������<font size=3>һСʱ</font>�������ޡ�</b></font><br><br>
	<font size=2><b>���С�ؼ���<br>1-10�ָ��ʽϴ�һ�����ʻ��20-50�֣�Ҳ�п��������50-100�����RP����õĻ����Ի��100-200���֣�</b></font>
			';
	
	

} else {

	//������
	$reason = $space['showcredit']<=1000?'���Ļ��ֳ����˱���Ļ������ƣ����ڻ��ֵ���500ʱ���뱾���ȡ���ֲ�����':'���ĲƲ�(�������л���)�����˱���ĲƲ�����(1000��)<br>���������ú��ѷ�����Ĳ����Ի��Ѿ��ۻ��֣����Ӳ��͵ķ��ʴ�����~<br>�����������뱾���ȡ���ֲ�����~~';
	$task['guide'] = '
		<span style="color:red;">'.$reason.'</span><br><br>
		<strong>������ʲô���أ�</strong>
		<ul class="task">
		<li>1. �����ܷḻ�ĵ��ߣ�</li>
		<li>2. ���Ӿ��ۻ����ô�Ҷ������㣻</li>
		<li>3. ʹ�����־װ��ĸ�Ư����</li>
		<li>4. ��������ԭ����Դ��</li>
		<li>5. ��������ͶƱ�ô�Ҹ�����𰸣�</li>
		<li>6. �ڸ�����ҳ���º������������������Ĳ��ͣ�</li>
		<li>7. ���ӿռ�������������������</li>
		<li>8. ����VIP��Ȩ���������ܿ���ing...����</li>
		<li>9. �һ�һЩ�����ʵ�ｱƷ���������ܿ���ing...����</li>
		<li>10. ��������;���Լ����ֹ�����</li>
		<br><br><span style="color:orangered;"><b>�벻Ҫ�����ˮˢ���֣��緢����ˢ����������һ�ɷ��˺ţ�лл������</b></span>
		</ul>';
/*
		<strong>��ȡ���ֵķ�ʽ</strong>
		<ul class="task">
		<li>1. ������־��</li>
		<li>2. ��������/���ԣ�</li>
		<li>3. �����⣻</li>
		<li>4. ����������</li>
		<li>5. �������ע��ɹ���</li>
		<li>6. �����н����</li>
		<br><br><span style="color:red;"><b>�벻Ҫ�����ˮˢ���֣��緢����ˢ����������һ�ɷ��˺ţ�лл������</b></span>
		</ul>';
*/

}

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
?>
<?php

/*
	[Discuz!] (C)2001-2007 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: faq.php 9804 2007-08-15 05:56:19Z cnteacher $
*/

require_once './common.php';
require_once './source/function_common.php';
//���ų�������
$citytop10 = array();
$query = $_SGLOBAL['db']->query("SELECT l.*,g.ucounts,g.resideprovince,g.residecity FROM ".tname('city'). " as l left join 
		(select residecity,resideprovince,count(residecity) As ucounts from " .tname('spacefield') . " GROUP by residecity) as g On g.residecity=l.city ORDER BY ucounts DESC LIMIT 0 , 15");
$i=1;
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	$citytop10[$i] = $value;
	$i++;
}


include_once(S_ROOT.'./source/function_cp.php');
if($ac != 'invite') checkuserlogin();
$icon = 'xiehou';

$title_template = '{actor} ��<a href="city.php">��ѧ���ǿ��</a>�ϣ������Ѱ��ͬ�ڴ�ѧ�ǵ�����!';

feed_add($icon, $title_template);

include template('city');
function checkuserlogin() {
	global $_SGLOBAL, $_SC;
	if(empty($_SGLOBAL['supe_uid'])) {
		if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
			header('Content-type: text/xml; charset=' . $_SC['charset']);
			die('<?xml version="1.0" encoding="'.$_SC['charset'].'"?><root><![CDATA[\'{\'status\':404,\'message\':\'ֻ�е�½����ܽ��д˲�����\'}\']]></root>');
		}else{
			ssetcookie('_refer', rawurlencode($_SERVER['REQUEST_URI']));
			showmessage('to_login', 'do.php?ac=login');
		}
	}
}


?>
<?php
	include_once('./common.php');
	@header("Expires: -1");
	@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
	@header("Pragma: no-cache");
	@header("Content-type: application/xml; charset=GBK");
	echo '<'."?xml version=\"1.0\" encoding=\"$_SC[charset]\"?>\n";
	echo "<root><![CDATA[";
	
	$count = 0;
	$arr = array();$fuids=array();$memberlist = array();
	$space = $_SGLOBAL['supe_uid']?getspace($_SGLOBAL['supe_uid']):array();
	if ( $_GET['city'] ) {
		$wheresql = " WHERE field.residecity='$_GET[city]'";
	}
	
	$query = $_SGLOBAL['db']->query("SELECT main.*, field.* FROM ".tname('space')." main FORCE INDEX (updatetime)
		LEFT JOIN ".tname('spacefield')." field ON field.uid=main.uid 
		LEFT JOIN ".tname('city')." c ON c.city=field.residecity 
		$wheresql
		ORDER BY main.updatetime DESC LIMIT 0,25");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['uid'], $value['username']);
		$value['isfriend'] = ($value['uid']==$space['uid'] || ($space['friends'] && in_array($value['uid'], $space['friends'])))?1:0;
		$value['pic'] = avatar($value['uid'], 'small');
		if ( $value['sex'] == 1 ) {
			$value['sex'] = '´¿Ò¯ÃÇ';
		} elseif ( $value['sex'] == 2 ) {
			$value['sex'] = 'ö¦ÃÃ';
		} else {
			$value['sex'] = 'ÈËÑý';
		}
             
		$memberlist[] = $value;
	}
	
	realname_get();
	
	foreach ($memberlist as $value) {
		if ( $value['lat'] ) {
			$arr[] = "{posn:[".$value['lat'].",".$value['lng']."],uid:'".$value['uid']."',username:'".addslashes($_SN[$value['uid']])."',pic:'".addslashes($value['pic'])."',node:'".$value['node']."',sex:'".$value['sex']."',qq:'".$value['qq']."',isfriend:'".$value['isfriend']."'}";
			$count++;
		}
	}
	
	$str = ' [['.$count.'],[';
	$strlog = join(',', $arr);
	$str .= $strlog;
	$str .= ']] ';
	
	echo $str;
	echo "]]></root>";  
?>
<?php
include_once('./common.php');
include_once(S_ROOT.'./source/function_cp.php');
include_once(S_ROOT.'./source/function_common.php');
checkclose();

if($_SCONFIG['allowrewrite'] && isset($_GET['rewrite'])) {
	$rws = explode('-', $_GET['rewrite']);
	if($rw_uid = intval($rws[0])) {
		$_GET['uid'] = $rw_uid;
	} else {
		$_GET['do'] = $rws[0];
	}
	if(isset($rws[1])) {
		$rw_count = count($rws);
		for ($rw_i=1; $rw_i<$rw_count; $rw_i=$rw_i+2) {
			$_GET[$rws[$rw_i]] = empty($rws[$rw_i+1])?'':$rws[$rw_i+1];
		}
	}
	unset($_GET['rewrite']);
}

//checklogin();	Modify By 01

if($uid) {
	$space = getspace($uid, 'uid', 0);
} elseif ($username) {
	$space = getspace($username, 'username', 0);
} elseif ($domain) {
	$space = getspace($domain, 'domain', 0);
} else {
	if(empty($_SGLOBAL['supe_uid'])) {
		if ($do != 'mtag') {
			ssetcookie('_refer', rawurlencode($_SERVER['REQUEST_URI']));
			$logindown = 1;	//Add By 01
//			showmessage('to_login', 'do.php?ac='.$_SCONFIG['login_action']);	Modify By 01
		}
	} else {
		$space = getspace($_SGLOBAL['supe_uid'], 'uid', 0);
	}
}


$dos 		= 	array('list','info','liulan','add','comment','my','search','del','down','friend','share','report','alldown','delfile');
$do 		= 	(!empty($_GET['do']) && in_array($_GET['do'], $dos))?$_GET['do']:'index';
$actives 	= 	array($do=>' class="active"');

	
include_once(S_ROOT."./ND_upload/source/{$do}.php");
?>
<?php
/**
 * import blog api
 * @author JunboBao
 * baojunbo@hotmail.com
 * create date: 2009-09-25
 */

/**
 * md5 str
 * @params string $str
 * @return string
 */
function md5Str($str) {
	if (0 == strlen($str)) return '';
	return strtoupper(md5('bjbPhp' . $str));
}

/**
 * add blog
 */
function addBlog($uchId, $uName, $title, $content, $dateTime) {
	global $_SGLOBAL;
	if ($title && $content && $dateTime) {
		$_SGLOBAL['supe_uid']      = $uchId;
		$_SGLOBAL['supe_username'] = addslashes($uName);
		$blogTitle   = siconv($title, UC_CHARSET, 'utf-8');
		$blogContent = siconv($content, UC_CHARSET, 'utf-8');
		$post = array(
			'subject' => str_replace('*AND*', '&', $blogTitle),
			'message' => str_replace('*AND*', '&', $blogContent),
			'dateline'=> $dateTime
		);
		blog_post($post);
		return 'ok';
	}
}

/**
 * add tBlog
 */
function addTBlog($uchId, $uName, $content) {
	global $_SGLOBAL;
	$message = siconv($content, UC_CHARSET, 'utf-8');
	$data = array(
		'uid' => $uchId,
		'username' => $uName,
		'message' => $message,
		'dateline' => $_SGLOBAL['timestamp'],
		'ip' => getonlineip()
	);
	$id = inserttable('doing', $data);

	// feed
	$fs = array();
	$fs['icon'] = 'doing';
	$fs['title_template'] = cplang('feed_doing_title');
	$fs['title_data'] = array('message'=>$message);
	$fs['body_template'] = '';
	$fs['body_data'] = array('doid'=>$id);
	$fs['body_general'] = '';
	if(ckprivacy('doing', 1)) {
		feed_add($fs['icon'], $fs['title_template'], $fs['title_data'], $fs['body_template'], $fs['body_data'], $fs['body_general']);
	}
	return 'ok';
}

define('IN_UCHOME', true);
define('S_ROOT', substr(dirname(__FILE__), 0, -3));

require_once S_ROOT . './config.php';
require_once S_ROOT . './data/data_config.php';
require_once S_ROOT . './source/function_common.php';
require_once S_ROOT . './source/function_blog.php';
require_once S_ROOT . './source/function_cp.php';

$md5Str = md5Str(md5Str($_POST['key'] . $_POST['mysid'] . $_POST['myuid'] . $_POST['s_url']) . $_POST['time']);
if ($md5Str == $_POST['sing'] && $md5Str && $_POST['sing']) {
	// db connect
	dbconnect();
	if ($_POST['importType'] == 'blog') {
		$rs = addBlog($_POST['uch_id'], $_POST['u_name'], $_POST['blogTitle'], $_POST['blogContent'], $_POST['blogTime']);
	} else if ($_POST['importType'] == 'tBlog') {
		$rs = addTBlog($_POST['uch_id'], $_POST['u_name'], $_POST['connect']);
	}
	echo $rs;
} else {
	echo 'ERROR:01';
}

?>

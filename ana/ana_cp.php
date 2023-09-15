<?php
$op = $_GET['op'];
if (empty($op)) {
	showmessage('未定义的方法');
}

if ('post' == $op) {	
	$title = trim($_POST['title']);
	if (empty($title) || empty($_POST['typeid']) || empty($_POST['content'])) {
		showmessage("语录信息未完整输入，请检查...");
	}
	$post_ana_id = intval($_POST['ana_id']);

	//修改
	if ($post_ana_id > 0) {
		$data = array(
			'title' => shtmlspecialchars($title),
			'content' => trim($_POST['content']),
			'typeid' =>  intval($_POST['typeid'])
		 );
		 updatetable("ana", $data, "id = {$post_ana_id}");
		 showmessage("修改成功！", "ana.php?do=ana&ac=view&id={$post_ana_id}");
	}
	
	$score = 0;
	getmember();
	if ($_SGLOBAL['member']['credit'] < $score) {
		showmessage("对不起，您的积分不够。");
	}

	$data = array(
		'uid' => $_SGLOBAL['supe_uid'],
		'username' => $_SGLOBAL['supe_username'],
		'title' => shtmlspecialchars($title),
		'content' => trim($_POST['content']),
		'typeid' =>  intval($_POST['typeid']),
		'score' => $score,
		'dateline' => $_SGLOBAL['timestamp'],
		'status' => 1
	 );

	$ana_id = inserttable('ana', $data, 1);

	//事件feed
	$fs = array();
	$fs['icon'] = 'ana';
    $fs['title_template'] = "{actor} 发布了新的语录 <strong>{subject}</strong>";
	
	$fs['title_data'] = array(
		'subject' => "<a href=\"ana.php?do=ana&ac=view&id={$ana_id}\">{$title}</a>",
		'score' => $score
	);
	$fs['body_template'] = '';
	$fs['body_data'] = array();
	
	include_once(S_ROOT.'./source/function_cp.php');
	feed_add($fs['icon'], $fs['title_template'], $fs['title_data'], $fs['body_template'], $fs['body_data'], $fs['body_general'],$fs['images'], $fs['image_links'], $fs['target_ids'], $fs['friend']);

	updatecredit($_SGLOBAL['supe_uid'], $score, '-');
	showmessage("您已经成功发布一个条语录。已经从您的积分中扣除{$score}分。也许在2099年的某一天，这条语录还在影响着人们的生活…", "ana.php?do=ana&ac=view&id={$ana_id}");

}
elseif ('reply' == $op )
{
	$ana_id = intval($_POST['ana_id']);
	$ana_title = trim($_POST['ana_title']);
	$ana_uid = intval($_POST['ana_uid']);
	if (empty($ana_id)) {
		showmessage("参数错误");
	}
	$content = trim($_POST['content']);
	if (empty($content) ) {
		showmessage("回复内容不能为空");
	}

	$data = array(
		'uid' => $_SGLOBAL['supe_uid'],
		'username' => $_SGLOBAL['supe_username'],
		'content' => trim($_POST['content']),
		'ana_id' => $ana_id,
		'dateline' => $_SGLOBAL['timestamp']
	 );
	$reply_id = inserttable('ana_reply', $data, 1);
	
	//更新回复数
	$sql = "UPDATE ".tname("ana")." SET reply_count = reply_count + 1 WHERE id = ".$ana_id;
	$_SGLOBAL['db']->query( $sql );
	
	if ($ana_uid !=  $_SGLOBAL['supe_uid']) {
		//通知
		include_once(S_ROOT.'./source/function_cp.php');
		$message = "回复了您发布的语录 <a href=\"ana.php?do=ana&ac=view&id={$ana_id}\">{$ana_title}</a>";
	    notification_add($ana_uid, "app", $message );
	}
	
    
    //事件feed
	$fs = array();
	$fs['icon'] = 'ana';
    $fs['title_template'] = "{actor} 回复了语录 <strong>{subject}</strong>";
	$fs['title_data'] = array(
		'subject' => "<a href=\"ana.php?do=ana&ac=view&id={$ana_id}\">{$ana_title}</a>"
	);
	$fs['body_template'] = '';
	$fs['body_data'] = array();
	include_once(S_ROOT.'./source/function_cp.php');
	feed_add($fs['icon'], $fs['title_template'], $fs['title_data'], $fs['body_template'], $fs['body_data'], $fs['body_general'],$fs['images'], $fs['image_links'], $fs['target_ids'], $fs['friend']);
	
    
	showmessage("回复成功", $theurl, 1);
}
elseif ('finish' == $op)
{
	$ana_id = intval($_POST['ana_id']);
	$theurl = trim($_POST['theurl']);
	
	//获取语录
	$sql = "SELECT * FROM ".tname("ana")." WHERE id= $ana_id AND uid = ".$_SGLOBAL['supe_uid'] ;
	$query = $_SGLOBAL['db']->query( $sql );
	$ana = $_SGLOBAL['db']->fetch_array( $query );
	if (empty($ana)) {
		showmessage("语录不存在或者已经被删除", 'ana.php?do=ana');
	}
	if (2 == $ana['status']) {
		showmessage("此语录已经鉴定，请不要重复提交。");
	}
	
	foreach ($pscore as $key => $val) {
		$sql = "UPDATE ".tname('ana_reply')." SET score = ".intval($val)." WHERE id =".$key;
		$_SGLOBAL['db']->query($sql);
	}

	$sql = "SELECT * FROM ".tname('ana_reply')." WHERE ana_id = $ana_id ";
	$query = $_SGLOBAL['db']->query($sql);
	$list = array( );
	while ( $value = $_SGLOBAL['db']->fetch_array( $query ) )
	{
		$list[$value['uid']]['fen'] = intval($list[$value['uid']]['fen']) +  $value['score'];
		$list[$value['uid']]['username'] = $value['username'];
	}
	
	$arr_fedd_str = "";
	include_once(S_ROOT.'./source/function_cp.php');
	foreach ($list as $key => $val) {
		if (empty($val['fen'])) {
			continue;
		}
		updatecredit($key, $val['fen'], '+');
		
		$arr_fedd_str[] =  "<a href='space.php?uid={$key}'>{$val['username']}</a>: {$val['fen']}分 ";
		
		$message = "对 <a href=\"ana.php?do=ana&ac=view&id={$ana_id}\">{$ana['content']}</a> 做了鉴定，您获得了 {$val['fen']} 分。";
    	notification_add($key, "app", $message );
	}
	
	$sql = "UPDATE ".tname("ana")." SET status='2', msg='". addslashes(implode("，", $arr_fedd_str))."' WHERE id = $ana_id ";
	$_SGLOBAL['db']->query($sql);
	
	
	//事件feed
	$fs = array();
	$fs['icon'] = 'ana';
    $fs['title_template'] = "{actor} 鉴定了语录 <strong>{subject}</strong> ，给分情况：". implode("，", $arr_fedd_str);
	
	$fs['title_data'] = array(
		'subject' => "<a href=\"ana.php?do=ana&ac=view&id={$ana_id}\">{$ana['title']}</a>"
	);
	$fs['body_template'] = '';
	$fs['body_data'] = array();
	include_once(S_ROOT.'./source/function_cp.php');
	feed_add($fs['icon'], $fs['title_template'], $fs['title_data'], $fs['body_template'], $fs['body_data'], $fs['body_general'],$fs['images'], $fs['image_links'], $fs['target_ids'], $fs['friend']);
	
	
	showmessage("恭喜您，鉴定语录成功！", $theurl);
}
elseif ('delete' == $op)
{
	$ana_id = intval($_GET['id']);
	if (empty($ana_id)) {
		showmessage("参数错误");
	}
	$sql = " select * from ".tname('ana')." where id = ".$ana_id." ";
	$query = $_SGLOBAL['db']->query( $sql );
	$ana = $_SGLOBAL['db']->fetch_array( $query );
	if (empty($ana))
	{
		showmessage("语录不存在或者已经被删除", 'ana.php?do=ana');
	}
	if ( $_SGLOBAL['supe_uid'] != $ana['uid'] &&  $_SGLOBAL['supe_uid'] != ADMIN_ID ) {
		showmessage("您没有删除此语录的权限", 'ana.php?do=ana');
	}

	$sql = "DELETE FROM ".tname('ana')." WHERE id = {$ana_id} ";
	$query = $_SGLOBAL['db']->query($sql);
	$sql = "DELETE FROM ".tname('ana_reply')." WHERE ana_id = {$ana_id} ";
	$query = $_SGLOBAL['db']->query($sql);
	showmessage("删除成功！", "ana.php?do=ana", 0);
}
elseif ('replydelete' == $op)
{
	$ana_id = intval($_GET['ana_id']);
	$id = intval($_GET['id']);
	if (empty($ana_id) || empty($id)) {
		showmessage("参数错误");
	}

	$sql = " select * from ".tname('ana_reply')." where id = ".$id." ";
	$query = $_SGLOBAL['db']->query( $sql );
	$ana = $_SGLOBAL['db']->fetch_array( $query );
	if (empty($ana))
	{
		showmessage("语录不存在或者已经被删除", 'ana.php?do=ana');
	}
	if ( $_SGLOBAL['supe_uid'] != $ana['uid'] &&  $_SGLOBAL['supe_uid'] != ADMIN_ID ) {
		showmessage("您没有删除此语录的权限", 'ana.php?do=ana');
	}

	$sql = "DELETE FROM ".tname('ana_reply')." WHERE id = {$id} ";
	$query = $_SGLOBAL['db']->query($sql);
	showmessage("删除成功！", "ana.php?do=ana&ac=view&id={$ana_id}");
}
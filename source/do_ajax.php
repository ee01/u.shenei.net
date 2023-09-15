<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: do_ajax.php 12535 2009-07-06 06:22:34Z zhengqingpeng $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

$op = empty($_GET['op'])?'':$_GET['op'];

if($op == 'comment') {

	$cid = empty($_GET['cid'])?0:intval($_GET['cid']);
	
	if($cid) {
		$cidsql = "cid='$cid' AND";
		$ajax_edit = 1;
	} else {
		$cidsql = '';
		$ajax_edit = 0;
	}

	//����
	$list = array();
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('comment')." WHERE $cidsql authorid='$_SGLOBAL[supe_uid]' ORDER BY dateline DESC LIMIT 0,1");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['authorid'], $value['author']);
		$list[] = $value;
	}
	
	realname_get();
	
} elseif($op == 'getfriendgroup') {
	
	$uid = intval($_GET['uid']);
	if($_SGLOBAL['supe_uid'] && $uid) {
		$space = getspace($_SGLOBAL['supe_uid']);
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('friend')." WHERE uid='$_SGLOBAL[supe_uid]' AND fuid='$uid'");
		$value = $_SGLOBAL['db']->fetch_array($query);
	}
	
	//��ȡ�û�
	$groups = getfriendgroup();
	
	if(empty($value['gid'])) $value['gid'] = 0;
	$group =$groups[$value['gid']];
	
} elseif($op == 'getfriendname') {
	
	//��ȡ�û��ĺ��ѷ�����
	$groupname = '';
	$group = intval($_GET['group']);
	
	if($_SGLOBAL['supe_uid'] && $group) {
		$space = getspace($_SGLOBAL['supe_uid']);
		$groups = getfriendgroup();
		$groupname = $groups[$group];
	}
	
} elseif($op == 'getmtagmember') {
	
	//��ȡ�û��ĺ��ѷ�����
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('tagspace')." WHERE tagid='$tagid' AND uid='$uid'");
	$tagspace = $_SGLOBAL['db']->fetch_array($query);
	
} elseif($op == 'share') {

	//����
	$list = array();
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('share')." WHERE uid='$_SGLOBAL[supe_uid]' ORDER BY dateline DESC LIMIT 0,1");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['uid'], $value['username']);
		$value = mkshare($value);
		$list[] = $value;
	}
	
	realname_get();
	
} elseif($op == 'post') {

	$pid = empty($_GET['pid'])?0:intval($_GET['pid']);

	if($pid) {
		$pidsql = " WHERE pid='$pid'";
		$ajax_edit = 1;
	} else {
		$pidsql = '';
		$ajax_edit = 0;
	}
	
	//����
	$list = array();
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('post')." $pidsql ORDER BY dateline DESC LIMIT 0,1");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['uid'], $value['username']);
		$list[] = $value;
	}
	
	realname_get();
	
} elseif($op == 'album') {
	
	$id = empty($_GET['id'])?0:intval($_GET['id']);
	$start = empty($_GET['start'])?0:intval($_GET['start']);

	if(empty($_SGLOBAL['supe_uid'])) {
		showmessage('to_login', 'do.php?ac='.$_SCONFIG['login_action']);
	}
	
	$perpage = 10;
	//��鿪ʼ��
	ckstart($start, $perpage);

	$count = 0;
	
	$piclist = array();
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('pic')." WHERE albumid='$id' AND uid='$_SGLOBAL[supe_uid]' ORDER BY dateline DESC LIMIT $start,$perpage");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		$value['bigpic'] = pic_get($value['filepath'], $value['thumb'], $value['remote'], 0);
		$value['pic'] = pic_get($value['filepath'], $value['thumb'], $value['remote']);
		$piclist[] = $value;
		$count++;
	}
	$multi = smulti($start, $perpage, $count, "do.php?ac=ajax&op=album&id=$id", $_GET['ajaxdiv']);

} elseif($op == 'docomment') {
	
	$doid = intval($_GET['doid']);
	$clist = $do = array();
	$icon = $_GET['icon'] == 'plus' ? 'minus' : 'plus';
	if($doid) {
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('doing')." WHERE doid='$doid'");
		if ($value = $_SGLOBAL['db']->fetch_array($query)) {
			realname_set($value['uid'], $value['username']);
			$value['icon'] = 'plus';
			//�Զ�չ�����20������
			if($value['replynum'] > 0 && ($value['replynum'] < 20 || $doid == $value['doid'])) {
				$doids[] = $value['doid'];
				$value['icon'] = 'minus';
			} elseif($value['replynum']<1) {
				$value['icon'] = 'minus';
			}
			$value['id'] = 0;
			$value['layer'] = 0;
			$clist[] = $value;
		}
	}
		
	if($_GET['icon'] == 'plus' && $value['replynum']) {

		include_once(S_ROOT.'./source/class_tree.php');
		$tree = new tree();
		
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('docomment')." WHERE doid='$doid' ORDER BY dateline");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			realname_set($value['uid'], $value['username']);
			if(empty($value['upid'])) {
				$value['upid'] = "do";
			}
			$tree->setNode($value['id'], $value['upid'], $value);
		}

		$values = $tree->getChilds("do");
		foreach ($values as $key => $id) {
			$one = $tree->getValue($id);
			$one['layer'] = $tree->getLayer($id) * 2;
			$clist[] = $one;
		}
	}
	
	realname_get();
	
} elseif($op == 'deluserapp') {
	
	if(empty($_SGLOBAL['supe_uid'])) {
		showmessage('no_privilege');
	}
	$hash = trim($_GET['hash']);
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('myinvite')." WHERE hash='$hash' AND touid='$_SGLOBAL[supe_uid]'");
	if($value = $_SGLOBAL['db']->fetch_array($query)) {
		$_SGLOBAL['db']->query("DELETE FROM ".tname('myinvite')." WHERE hash='$hash' AND touid='$_SGLOBAL[supe_uid]'");
		
		//ͳ�Ƹ���
		$myinvitenum = getcount('myinvite', array('touid'=>$_SGLOBAL['supe_uid']));
		updatetable('space', array('myinvitenum'=>$myinvitenum), array('uid'=>$_SGLOBAL['supe_uid']));
		
		showmessage('do_success');
	} else {
		showmessage('no_privilege');
	}
} elseif($op == 'getreward') {
	$reward = '';
	if($_SCOOKIE['reward_log']) {
		$log = explode(',', $_SCOOKIE['reward_log']);
		if(count($log) == 2 && $log[1]) {
			@include_once(S_ROOT.'./data/data_creditrule.php');
			$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('creditlog')." WHERE clid='$log[1]'");
			$creditlog = $_SGLOBAL['db']->fetch_array($query);
			$rule = $_SGLOBAL['creditrule'][$log[0]];
			$rule['cyclenum'] = $rule['rewardnum']? $rule['rewardnum'] - $creditlog['cyclenum'] : 0;
		}
		ssetcookie('reward_log', '');
	}
	
//���칤�� Add By 01��
} elseif($op == 'getpunchclock') {

	$tid = intval($_GET['tid']);
	$query = $_SGLOBAL['db']->query('SELECT * FROM '.tname('task')." WHERE taskid='$tid'");
	if($task = $_SGLOBAL['db']->fetch_array($query))
	{
		//ִ������ű�
		include_once(S_ROOT.'./source/task/'.$task['filename']); 

		//�û�ִ�����
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('usertask')." WHERE uid='$_SGLOBAL[supe_uid]' AND taskid='$tid'");
		if($usertask = $_SGLOBAL['db']->fetch_array($query)) 
		{
			$allownext = 0;
			$lasttime = $usertask['dateline'];
			if($task['nexttype'] == 'day') 
			{
				if(sgmdate('Ymd', $_SGLOBAL['timestamp']) == sgmdate('Ymd', $lasttime))
				{
					$task['done'] = 0;
				}
			}
		}

		if($task['done'])
		{

			$task['dateline'] = $_SGLOBAL['timestamp'];

			$setarr = array(
				'uid' => $_SGLOBAL['supe_uid'],
				'username' => $_SGLOBAL['supe_username'],
				'taskid' => $tid,
				'credit' => $task['credit'],
				'dateline' => $_SGLOBAL['timestamp'],
				'isignore' => 0
			);

			inserttable('usertask', $setarr, 0, true);	//Modify By 01

			//�������������
			$_SGLOBAL['db']->query("UPDATE ".tname('task')." SET num=num+1 WHERE taskid='$task[taskid]'");

			//���ӻ���
			if($task['credit'])
			{
				$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET credit=credit+$task[credit] WHERE uid='$_SGLOBAL[supe_uid]'");
				$query = $_SGLOBAL['db']->query("SELECT credit FROM ".tname('space')." WHERE uid='$_SGLOBAL[supe_uid]'");
				$rule = $_SGLOBAL['db']->fetch_array($query);
			}

			//����feed
			if(ckprivacy('task', 1)) {
				$fs = array(
					'title_template' => $task['credit']?cplang('feed_task_credit'):cplang('feed_task'),
					'title_data' => array(
						'task'=>'<a href="cp.php?ac=task&taskid='.$task['taskid'].'">'.$task['name'].'</a>',
						'credit' => $task['credit']
					),
				);
				feed_add('task', $fs['title_template'], $fs['title_data']);
			}
		}
	}
//���칤�� Add By 01��

//��ܰ����
}elseif($op=='attention'){
	$id = intval($_GET['id']);

	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('attention')." WHERE id='$id' ");
	if(!$attention = $_SGLOBAL['db']->fetch_array($query)) {
		showmessage('posting_does_not_exist');
	}

	$nextid = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT id  FROM ".tname('attention')." WHERE id>'$id' AND startime<=".$_SGLOBAL['timestamp']." AND endtime>=".$_SGLOBAL['timestamp']." AND isactive=1 LIMIT 1"),0);
//	$nextid = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT id  FROM ".tname('attention')." WHERE aorder<".$attention['aorder']." AND startime<=".$_SGLOBAL['timestamp']." AND endtime>=".$_SGLOBAL['timestamp']." AND isactive=1 ORDER BY id desc LIMIT 1"),0);
	if(!$nextid){
		$nextid = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT id  FROM ".tname('attention')."  WHERE startime<=".$_SGLOBAL['timestamp']." AND endtime>=".$_SGLOBAL['timestamp']." AND isactive=1 LIMIT 1"),0);
//		$nextid = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT id  FROM ".tname('attention')."  WHERE startime<=".$_SGLOBAL['timestamp']." AND endtime>=".$_SGLOBAL['timestamp']." AND isactive=1 ORDER BY aorder DESC LIMIT 1"),0);
	}
}

include template('do_ajax');

?>
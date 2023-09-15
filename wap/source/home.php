<?php   if(!defined('IN_UCHOME') || !defined('IN_WAP')) { exit('Access Denied'); } $_SGLOBAL['waplogo'] ='1'; include_once(S_ROOT.'./data/data_network.php');  
$cachefile = S_ROOT.'./data/wap_cache_network_blog.txt'; if(check_network_cache('blog')) { $bloglist = unserialize(sreadfile($cachefile)); } else { $sqlarr = mk_network_sql('blog', array('blogid', 'uid'), array('hot','viewnum','replynum'), array('dateline'), array('dateline','viewnum','replynum','hot') ); extract($sqlarr);  
$wherearr[] = "\155\141\x69\x6e.friend='0'";  
$shownum = 8; $query = $_SGLOBAL['db']->query("\x53E\114E\103\x54 m\141\151\156.*, \146\x69\145l\x64.* 
		\x46\122\117M ".tname('blog')." \x6da\x69n
		LEFT JOIN ".tname('blogfield')." \146\151e\154d \x4f\116 \146\151\145ld.blogid=ma\x69\156.blogid
		WHERE ".implode(' AND ', $wherearr)."
		ORDER BY m\141\151\156.{$order} $sc LIMIT 0,$shownum"); $bloglist = array(); while ($value = $_SGLOBAL['db']->fetch_array($query)) { $value['message'] = getstr($value['message'], 86, 0, 0, 0, 0, -1); $value['subject'] = getstr($value['subject'], 50, 0, 0, 0, 0, -1); $bloglist[] = $value; } if($_SGLOBAL['network']['blog']['cache']) { swritefile($cachefile, serialize($bloglist)); } } foreach($bloglist as $key => $value) { realname_set($value['uid'], $value['username']); $bloglist[$key] = $value; }  
  @include_once(S_ROOT.'./data/data_profield.php');  
$cachefile = S_ROOT.'./data/wap_cache_network_thread.txt'; if(check_network_cache('thread')) { $threadlist = unserialize(sreadfile($cachefile)); } else { $sqlarr = mk_network_sql('thread', array('tid', 'uid'), array('hot','viewnum','replynum'), array('dateline','lastpost'), array('dateline','viewnum','replynum','hot') ); extract($sqlarr);  
$shownum = 8; $threadlist = array(); $query = $_SGLOBAL['db']->query("S\105\x4cE\x43T \x6d\141\x69\x6e.*, m.tagname
		\106\x52O\115 ".tname('thread')." \155ai\x6e
		LEFT JOIN ".tname('mtag')." m ON m.tagid=\x6da\x69\156.tagid
		WHERE ".implode(' AND ', $wherearr)."
		ORDER BY \155a\151n.{$order} $sc LIMIT 0,$shownum"); while ($value = $_SGLOBAL['db']->fetch_array($query)) { $value['tagname'] = getstr($value['tagname'], 20); $value['subject'] = getstr($value['subject'], 50); $threadlist[] = $value; } if($_SGLOBAL['network']['thread']['cache']) { swritefile($cachefile, serialize($threadlist)); } } foreach($threadlist as $key => $value) { realname_set($value['uid'], $value['username']); $threadlist[$key] = $value; }  
include_once(S_ROOT.'./data/data_eventclass.php'); $cachefile = S_ROOT.'./data/wap_cache_network_event.txt'; if(check_network_cache('event')) { $eventlist = unserialize(sreadfile($cachefile)); } else { $sqlarr = mk_network_sql('event', array('eventid', 'uid'), array('hot','membernum','follownum'), array('dateline'), array('dateline','membernum','follownum','hot') ); extract($sqlarr);  
$shownum = 4; $eventlist = array(); $query = $_SGLOBAL['db']->query("\123\x45\114EC\x54 ma\x69\156.*
		\106\122\x4f\x4d ".tname('event')." \155a\x69\156
		WHERE ".implode(' AND ', $wherearr)."
		ORDER BY m\141i\x6e.{$order} $sc LIMIT 0,$shownum"); while ($value = $_SGLOBAL['db']->fetch_array($query)) { $value['title'] = getstr($value['title'], 45); if($value['poster']){ $value['pic'] = pic_get($value['poster'], $value['thumb'], $value['remote']); } else { $value['pic'] = $_SGLOBAL['eventclass'][$value['classid']]['poster']; } $eventlist[] = $value; } if($_SGLOBAL['network']['event']['cache']) { swritefile($cachefile, serialize($eventlist)); } } foreach($eventlist as $key => $value) { realname_set($value['uid'], $value['username']); $eventlist[$key] = $value; }  
$cachefile = S_ROOT.'./data/wap_cache_network_poll.txt'; if(check_network_cache('poll')) { $polllist = unserialize(sreadfile($cachefile)); } else { $sqlarr = mk_network_sql('poll', array('pid', 'uid'), array('hot','voternum','replynum'), array('dateline'), array('dateline','voternum','replynum','hot') ); extract($sqlarr);  
$shownum = 9; $polllist = array(); $query = $_SGLOBAL['db']->query("S\105\114E\x43\x54 mai\156.*
		\x46R\x4f\115 ".tname('poll')." m\x61\151n
		WHERE ".implode(' AND ', $wherearr)."
		ORDER BY \x6da\151\x6e.{$order} $sc LIMIT 0,$shownum"); while ($value = $_SGLOBAL['db']->fetch_array($query)) { realname_set($value['uid'], $value['username']); $polllist[] = $value; } if($_SGLOBAL['network']['poll']['cache']) { swritefile($cachefile, serialize($polllist)); } } foreach($polllist as $key => $value) { realname_set($value['uid'], $value['username']); $polllist[$key] = $value; }  
$dolist = array(); $query = $_SGLOBAL['db']->query("SE\114\105\103\124 *
	FROM ".tname('doing')."
	ORDER BY dateline DESC LIMIT 0,5"); while ($value = $_SGLOBAL['db']->fetch_array($query)) { realname_set($value['uid'], $value['username']); $value['title'] = getstr($value['message'], 0, 0, 0, 0, 0, -1); $dolist[] = $value; }  
$star = array(); $starlist = array(); if($_SCONFIG['spacebarusername']) { $query = $_SGLOBAL['db']->query("\x53\105\114\x45\x43T * \x46ROM ".tname('space')." WHERE username IN (".simplode(explode(',', $_SCONFIG['spacebarusername'])).") ORDER BY credit DESC LIMIT 0,1"); while ($value = $_SGLOBAL['db']->fetch_array($query)) { realname_set($value['uid'], $value['username'], $value['name'], $value['namestatus']); $starlist[] = $value; } } if($starlist) { $star = sarray_rand($starlist, 1); }  
$showlist = array(); $OSt7g0dZY=0; $query = $_SGLOBAL['db']->query("\123E\114E\103\124 sh.note, s.* F\x52\117\115 ".tname('show')." sh
	LEFT JOIN ".tname('space')." s \117N s.uid=sh.uid
	ORDER BY sh.credit DESC LIMIT 0,8"); while ($value = $_SGLOBAL['db']->fetch_array($query)) { realname_set($value['uid'], $value['username'], $value['name'], $value['namestatus']); $value['note'] = addslashes(getstr($value['note'], 80, 0, 0, 0, 0, -1)); $value['index'] = $OSt7g0dZY++; $showlist[$value['uid']] = $value; } if(empty($star) && $showlist) { $star = sarray_rand($showlist, 1); unset($showlist[0]); }  
$onlinelist = array(); $query = $_SGLOBAL['db']->query("S\105\x4cECT s.*, sf.note F\122\117\115 ".tname('session')." s
	LEFT JOIN ".tname('spacefield')." sf \117\x4e sf.uid=s.uid
	ORDER BY s.\x6c\x61\163\164\x61ct\151vit\171 DESC LIMIT 0,12"); while ($value = $_SGLOBAL['db']->fetch_array($query)) { if(!$value['magichidden']) { $value['note'] = shtmlspecialchars(strip_tags($value['note'])); realname_set($value['uid'], $value['username']); $onlinelist[$value['uid']] = $value; } } if(empty($star) && $onlinelist) { $star = sarray_rand($onlinelist, 1); }  
$olcount = getcount('session', array());  
$sharelist = array(); $query = $_SGLOBAL['db']->query("\123E\x4cE\x43\x54 *
	\x46\122\x4f\115 ".tname('share')."
	ORDER BY dateline DESC LIMIT 0,11"); while ($value = $_SGLOBAL['db']->fetch_array($query)) { realname_set($value['uid'], $value['username']); $sharelist[] = $value; } realname_get();  
$membername = empty($_SCOOKIE['loginuser'])?'':sstripslashes($_SCOOKIE['loginuser']); $wheretime = $_SGLOBAL['timestamp']-3600*24*30; $_TPL['css'] = 'network'; include_once template("index");  
function check_network_cache($type) { global $_SGLOBAL; if($_SGLOBAL['network'][$type]['cache']) { $cachefile = S_ROOT.'./data/wap_cache_network_'.$type.'.txt'; if (!file_exists($cachefile)) { return false; } $ftime = filemtime($cachefile); if($_SGLOBAL['timestamp'] - $ftime < $_SGLOBAL['network'][$type]['cache']) { return true; } } return false; }  
function mk_network_sql($type, $ids, $crops, $days, $orders) { global $_SGLOBAL; $nt = $_SGLOBAL['network'][$type]; $wherearr = array('1');  
foreach ($ids as $value) { if($nt[$value]) { $wherearr[] = "\155\x61\x69\x6e.{$value} IN (".$nt[$value].")"; } }  
foreach ($crops as $value) { $value1 = $value.'1'; $value2 = $value.'2'; if($nt[$value1]) { $wherearr[] = "\x6d\x61i\156.{$value} >= '".$nt[$value1]."'"; } if($nt[$value2]) { $wherearr[] = "\x6da\151\156.{$value} <= '".$nt[$value2]."'"; } }  
foreach ($days as $value) { if($nt[$value]) { $daytime = $_SGLOBAL['timestamp'] - $nt[$value]*3600*24; $wherearr[] = "ma\x69\156.{$value}>='$daytime'"; } }  
$order = in_array($nt['order'], $orders)?$nt['order']:array_shift($orders); $sc = in_array($nt['sc'], array('desc','asc'))?$nt['sc']:'desc'; return array('wherearr'=>$wherearr, 'order'=>$order, 'sc'=>$sc); } ; ?>

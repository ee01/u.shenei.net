<?php   if(!defined('IN_UCHOME')) { exit('Access Denied'); }  
$perpage = 24; $perpage = mob_perpage($perpage); $list = $ols = $fuids = array(); $count = 0; $page = empty($_GET['page'])?0:intval($_GET['page']); if($page<1) $page = 1; $start = ($page-1)*$perpage;  
ckstart($start, $perpage); if($_GET['view'] == 'online') { $theurl = "space.\x70hp?uid=$space[uid]&\144o=friend&view=online"; $actives = array('me'=>' class="active"'); $wheresql = ''; if($_GET['type']=='near') { $theurl = "space.p\x68\x70?uid=$space[uid]&\x64\x6f=friend&view=online&type=near"; $wheresql = " WHERE \x6d\x61i\156.i\x70='".getonlineip(1)."'"; } elseif($_GET['type']=='friend' && $space['feedfriend']) { $theurl = "space.p\150\160?uid=$space[uid]&\144o=friend&view=online&type=friend"; $wheresql = " WHERE m\x61\x69\156.uid IN ($space[feedfriend])"; } else { $_GET['type']=='all'; $theurl = "space.p\x68p?uid=$space[uid]&\144o=friend&view=online&type=all"; $wheresql = ' WHERE 1'; } $count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("\123ELEC\x54 COUNT(*) \x46RO\x4d ".tname('session')." \155\141\x69\x6e $wheresql"), 0); if($count) { $query = $_SGLOBAL['db']->query("\x53\x45LE\x43\x54 f.resideprovince, f.residecity, f.sex, f.note, f.spacenote, ma\x69\156.*
			F\122\117\x4d ".tname('session')." main
			LEFT JOIN ".tname('spacefield')." f O\x4e f.uid=\155a\151\x6e.uid
			$wheresql
			ORDER BY \155a\151n.\x6c\141s\164\141c\x74\x69\x76\x69\164\x79 DESC
			LIMIT $start,$perpage"); while ($value = $_SGLOBAL['db']->fetch_array($query)) { if($value['magichidden']) { $count = $count - 1; continue; } if($_GET['type']=='near') { if($value['uid'] == $space['uid']) { $count = $count-1; continue; } } realname_set($value['uid'], $value['username']); $value['p'] = rawurlencode($value['resideprovince']); $value['c'] = rawurlencode($value['residecity']); $value['isfriend'] = ($value['uid']==$space['uid'] || ($space['friends'] && in_array($value['uid'], $space['friends'])))?1:0; $ols[$value['uid']] = $value['lastactivity']; $value['note'] = getstr($value['note'], 35, 0, 0, 0, 0, -1); $list[$value['uid']] = $value; } } $multi = multi($count, $perpage, $page, $theurl); } elseif($_GET['view'] == 'visitor' || $_GET['view'] == 'trace') { $theurl = "space.ph\160?uid=$space[uid]&d\157=friend&view=$_GET[view]"; $actives = array('me'=>' class="active"'); if($_GET['view'] == 'visitor') { 
$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("S\105L\x45\103T COUNT(*) \106\122O\115 ".tname('visitor')." m\141i\156 WHERE \x6da\151\156.uid='$space[uid]'"), 0); $query = $_SGLOBAL['db']->query("S\x45L\x45\103T f.resideprovince, f.residecity, f.note, f.spacenote, f.sex, main.vuid AS uid, m\141\151\x6e.vusername AS username, \x6d\x61in.dateline
			\x46\122\117M ".tname('visitor')." \x6d\141\151n
			LEFT JOIN ".tname('spacefield')." f \x4f\x4e f.uid=\155a\151\x6e.vuid
			WHERE m\141\151n.uid='$space[uid]'
			ORDER BY \155\x61i\x6e.dateline DESC
			LIMIT $start,$perpage"); } else { 
$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SE\x4c\x45CT COUNT(*) FR\x4f\115 ".tname('visitor')." \155ai\x6e WHERE m\141in.vuid='$space[uid]'"), 0); $query = $_SGLOBAL['db']->query("\123\x45\114\x45\103\124 s.username, s.name, s.namestatus, s.groupid, f.resideprovince, f.residecity, f.note, f.spacenote, f.sex, m\141i\156.uid AS uid, \155\141\x69n.dateline
			\x46\122O\115 ".tname('visitor')." m\141\x69n
			LEFT JOIN ".tname('space')." s O\x4e s.uid=\155\x61\151n.uid
			LEFT JOIN ".tname('spacefield')." f O\116 f.uid=\155\141i\x6e.uid
			WHERE mai\156.vuid='$space[uid]'
			ORDER BY \155\141\151n.dateline DESC
			LIMIT $start,$perpage"); } if($count) { while ($value = $_SGLOBAL['db']->fetch_array($query)) { realname_set($value['uid'], $value['username'], $value['name'], $value['namestatus']); $value['p'] = rawurlencode($value['resideprovince']); $value['c'] = rawurlencode($value['residecity']); $value['isfriend'] = ($value['uid']==$space['uid'] || ($space['friends'] && in_array($value['uid'], $space['friends'])))?1:0; $fuids[] = $value['uid']; $value['note'] = getstr($value['note'], 28, 0, 0, 0, 0, -1); $list[$value['uid']] = $value; } } $multi = multi($count, $perpage, $page, $theurl); } elseif($_GET['view'] == 'blacklist') { $theurl = "space.\x70\150\160?uid=$space[uid]&\144\157=friend&view=$_GET[view]"; $actives = array('me'=>' class="active"'); $count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SEL\x45\103\x54 COUNT(*) F\122\x4fM ".tname('blacklist')." \155\x61\x69\156 WHERE m\x61in.uid='$space[uid]'"), 0); if($count) { $query = $_SGLOBAL['db']->query("\123\105L\105\x43\124 s.username, s.name, s.namestatus, s.groupid, \155ain.dateline, \155a\151\156.buid AS uid
			F\x52\117\115 ".tname('blacklist')." \155a\x69\x6e
			LEFT JOIN ".tname('space')." s \x4f\x4e s.uid=\155\x61\151\x6e.buid
			WHERE \x6d\141\151\x6e.uid='$space[uid]'
			ORDER BY \155\x61i\x6e.dateline DESC
			LIMIT $start,$perpage"); while ($value = $_SGLOBAL['db']->fetch_array($query)) { $value['isfriend'] = 0; realname_set($value['uid'], $value['username'], $value['name'], $value['namestatus']); $fuids[] = $value['uid']; $list[$value['uid']] = $value; } } $multi = multi($count, $perpage, $page, $theurl); } else {  
$theurl = "space.\x70hp?uid=$space[uid]&\x64\x6f=$do"; $actives = array('me'=>' class="active"'); $_GET['view'] = 'me';  
$wheresql = ''; if($space['self']) { $groups = getfriendgroup(); $group = !isset($_GET['group'])?'-1':intval($_GET['group']); if($group > -1) { $wheresql = "AND mai\156.gid='$group'"; $theurl .= "&group=$group"; } } if($_GET['searchkey']) { $wheresql = "AND \x6d\x61i\156.fusername='$_GET[searchkey]'"; $theurl .= "&searchkey=$_GET[searchkey]"; } if($space['friendnum']) { if($wheresql) { $count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("\x53\105\114\x45\103\x54 COUNT(*) \x46\122\117\115 ".tname('friend')." \x6d\x61\x69\x6e WHERE \x6d\x61i\156.uid='$space[uid]' AND m\141\151\156.\163t\x61t\x75\163='1' $wheresql"), 0); } else { $count = $space['friendnum']; } if($count) { $query = $_SGLOBAL['db']->query("\123\x45\114\105C\124 s.*, f.resideprovince, f.residecity, f.note, f.spacenote, f.sex, \x6d\141i\x6e.gid, \155\x61i\156.num
				\106\x52OM ".tname('friend')." \x6d\x61\151\x6e
				LEFT JOIN ".tname('space')." s \x4f\116 s.uid=\155\141\151n.fuid
				LEFT JOIN ".tname('spacefield')." f \x4fN f.uid=ma\151\156.fuid
				WHERE \x6d\141in.uid='$space[uid]' AND ma\x69\156.\163\x74\141\164u\163='1' $wheresql
				ORDER BY \155ain.num DESC, \x6d\x61in.dateline DESC
				LIMIT $start,$perpage"); while ($value = $_SGLOBAL['db']->fetch_array($query)) { realname_set($value['uid'], $value['username'], $value['name'], $value['namestatus']); $value['p'] = rawurlencode($value['resideprovince']); $value['c'] = rawurlencode($value['residecity']); $value['group'] = $groups[$value['gid']]; $value['isfriend'] = 1; $fuids[] = $value['uid']; $value['note'] = getstr($value['note'], 28, 0, 0, 0, 0, -1); $list[$value['uid']] = $value; } }  
$multi = multi($count, $perpage, $page, $theurl); $friends = array();  
$query = $_SGLOBAL['db']->query("\123\x45L\x45\103\124 f.fusername, s.name, s.namestatus, s.groupid \106\122OM ".tname('friend')." f
			LEFT JOIN ".tname('space')." s O\116 s.uid=f.fuid
			WHERE f.uid=$_SGLOBAL[supe_uid] AND f.\x73\164\x61\x74\165\163='1' ORDER BY f.num DESC, f.dateline DESC LIMIT 0,100"); while ($value = $_SGLOBAL['db']->fetch_array($query)) { $fusername = ($_SCONFIG['realname'] && $value['name'] && $value['namestatus'])?$value['name']:$value['fusername']; $friends[] = addslashes($fusername); } $friendstr = implode(',', $friends); } if($space['self']) { $groupselect = array($group => ' class="current"');  
$maxfriendnum = checkperm('maxfriendnum'); if($maxfriendnum) { $maxfriendnum = checkperm('maxfriendnum') + $space['addfriend']; } } }  
if($fuids) { $query = $_SGLOBAL['db']->query("\123\105L\x45\103\x54 * \x46\x52\x4f\x4d ".tname('session')." WHERE uid IN (".simplode($fuids).")"); while ($value = $_SGLOBAL['db']->fetch_array($query)) { if(!$value['magichidden']) { $ols[$value['uid']] = $value['lastactivity']; } elseif($list[$value['uid']] && !in_array($_GET['view'], array('me', 'trace', 'blacklist'))) { unset($list[$value['uid']]); $count = $count - 1; } } } realname_get(); if(empty($_GET['view']) || $_GET['view'] == 'all') $_GET['view'] = 'me'; $a_actives = array($_GET['view'].$_GET['type'] => ' class="current"'); include_once template("space_friend");  ?>
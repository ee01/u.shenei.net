<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: network.php 13003 2009-08-05 06:46:06Z liguode $
*/

include_once('./common.php');

//是否关闭站点
checkclose();

//空间被锁定
if($_SGLOBAL['supe_uid']) {
	$space = getspace($_SGLOBAL['supe_uid']);
	
	if($space['flag'] == -1) {
		showmessage('space_has_been_locked');
	}
	
	//禁止访问
	if(checkperm('banvisit')) {
		ckspacelog();
		showmessage('you_do_not_have_permission_to_visit');
	}
}

        //大家的最新动态
        $feedlist = array();
        $query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('feed')." WHERE friend='0' ORDER BY dateline DESC LIMIT 0,10");
        while ($value = $_SGLOBAL['db']->fetch_array($query)) {
                realname_set($value['uid'], $value['username']);
                $feedlist[] = $value;
        }
        //格式化动态
        foreach ($feedlist as $key => $value) {
                $feedlist[$key] = mkfeed($value);
        }	
include_once(S_ROOT.'./source/sohovicstyle.php');

?>
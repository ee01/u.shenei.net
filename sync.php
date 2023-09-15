<?php
include_once('./common.php');
include_once(S_ROOT . './source/class.arrayiconv.php');

if ($_GET['sync_id'] || $_GET['sync_id']==='0') {
	$app = $_GET['app'] ? $_GET['app'] : 'sina';
	sync($app,$_GET['sync_id']);
}

function sync ($app, $sync_id) {
	global $_SGLOBAL;
	if ( $sync_id != null ) {
		$sync_id = intval($sync_id);
	}
	if ($app == 'sina' || $app == 'qq' || $app == 'facebook') {
		if ( $sync_id === null ) {		//同步自己
			$uid = $_SGLOBAL['supe_uid'];
			if (chktime($uid,$app,60)) {
				call_user_func('sync_'.$app, $uid);
			}
		}elseif( $sync_id === 0 ) {		//同步好友
			$query = $_SGLOBAL['db']->query("SELECT a.uid FROM ".tname('api')." a LEFT JOIN ".tname('friend')." f ON a.uid=f.fuid
				WHERE a.app='$app' AND f.uid=$_SGLOBAL[supe_uid] AND f.status='1' ORDER BY f.num DESC, f.dateline DESC");
			while ($value = $_SGLOBAL['db']->fetch_array($query)) {
				if (chktime($value['uid'],$app,300)) {
					call_user_func('sync_'.$app, $value['uid']);
				}
			}
			$uid = $_SGLOBAL['supe_uid'];
			call_user_func('sync_'.$app, $uid);
		}elseif( $sync_id == -1 ) {		//同步所有人
			$query = $_SGLOBAL['db']->query("SELECT uid FROM ".tname('api')." WHERE app='$app'");
			while ($value = $_SGLOBAL['db']->fetch_array($query)) {
				if (chktime($value['uid'],$app,3600)) {
					call_user_func('sync_'.$app, $value['uid']);
				}
			}
		}else{							//同步指定用户
			$uid = $sync_id;
			if (chktime($uid,$app,60)) {
				call_user_func('sync_'.$app, $uid);
			}
		}
	}
}

function sync_sina ($uid) {
	global $_SGLOBAL;
//	include_once(S_ROOT.'./source/OAuth.php');
	include_once(S_ROOT.'./source/OAuth10a.php');
	include_once(S_ROOT . './source/function_sina_t.php');

	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('api')." WHERE uid='$uid' and app='sina' and sync_down=1");
	$api_sina = $_SGLOBAL['db']->fetch_array($query);
	
	if ($api_sina) {
		$api_sina['sync_down_config'] = unserialize($api_sina['sync_down_config']);
		$api_sina['sync_fw_config'] = unserialize($api_sina['sync_fw_config']);
		$accesskey_sina = unserialize($api_sina['accesskey']);
	}else{
		return false;
	}

	$sina = new WeiboClient( WB_AKEY , WB_SKEY , $accesskey_sina['oauth_token'] , $accesskey_sina['oauth_token_secret'] );
	$me = $sina->verify_credentials();
	$me = arrayiconv::Conversion($me,"gbk","utf-8");

	if ($api_sina['updatetime'] == 0) {
		if ($me['statuses_count'] <= 200) {
			$ms = $sina->user_timeline(1,200);
			$ms = arrayiconv::Conversion($ms,"gbk","utf-8");
			save_sync($uid,'sina',$api_sina,$ms);
		}else{
			for ($i=intval($me['statuses_count']/200)+1; $i>0; $i--) {
				$ms = $sina->user_timeline($i,200);
				$ms = arrayiconv::Conversion($ms,"gbk","utf-8");
				save_sync($uid,'sina',$api_sina,$ms);
			}
		}
	}else{
		$ms = $sina->user_timeline();
		$ms = arrayiconv::Conversion($ms,"gbk","utf-8");
		save_sync($uid,'sina',$api_sina,$ms);
		fw_sync($uid,'sina',$api_sina,$ms);
	}
}

function sync_qq ($uid) {
	global $_SGLOBAL;
//	include_once(S_ROOT.'./source/OAuth.php');
	include_once(S_ROOT.'./source/OAuth10.php');
	include_once(S_ROOT . './source/function_qq_t.php');

	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('api')." WHERE uid='$uid' and app='qq' and sync_down=1");
	$api_qq = $_SGLOBAL['db']->fetch_array($query);
	
	if ($api_qq) {
		$api_qq['sync_down_config'] = unserialize($api_qq['sync_down_config']);
		$api_qq['sync_fw_config'] = unserialize($api_qq['sync_fw_config']);
		$accesskey_qq = unserialize($api_qq['accesskey']);
	}else{
		return false;
	}

	$qq = new MBApiClient( MB_AKEY , MB_SKEY , $accesskey_qq['oauth_token'] , $accesskey_qq['oauth_token_secret']  );
	$me = $qq->getUserInfo();
	$me = arrayiconv::Conversion($me,"gbk","utf-8");

	if ($api_qq['updatetime'] == 0) {
		if ($me['data']['tweetnum'] <= 20) {
			$p = array(
				'f' => 0,
				't' => 0,
				'n' => 20,
				'name' => $me['data']['name']
			);
			$ms = $qq->getTimeline($p);
			$ms = arrayiconv::Conversion($ms,"gbk","utf-8");
			save_sync($uid,'qq',$api_qq,$ms['data']['info']);
		}else{
			$pagetime = 0;
			for ($i=0; $i<=intval($me['data']['tweetnum']/20); $i++) {
				$p = array(
					'f' => $pagetime?1:0,
					't' => $pagetime,
					'n' => 20,
					'name' => $me['data']['name']
				);
				$ms = $qq->getTimeline($p);
				$ms = arrayiconv::Conversion($ms,"gbk","utf-8");
				$ms_arr[] = $ms;
				if ($ms['data']['info'][9]) {
					$pagetime = $ms['data']['info'][9]['timestamp'];
				}else{
					break;
				}
			}
			foreach (array_reverse($ms_arr) as $ms) {
				save_sync($uid,'qq',$api_qq,$ms['data']['info']);
			}
		}
	}else{
		$p = array(
			'f' => 0,
			't' => 0,
			'n' => 20,
			'name' => $me['data']['name']
		);
		$ms = $qq->getTimeline($p);
		$ms = arrayiconv::Conversion($ms,"gbk","utf-8");
		save_sync($uid,'qq',$api_qq,$ms['data']['info']);
		fw_sync($uid,'qq',$api_qq,$ms['data']['info']);
	}
}

function sync_facebook ($uid) {
	global $_SGLOBAL;
	include_once(S_ROOT.'./source/function_facebook.php');

	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('api')." WHERE uid='$uid' and app='facebook' and sync_down=1");
	$api_fb = $_SGLOBAL['db']->fetch_array($query);
	
	if ($api_fb) {
		$api_fb['sync_down_config'] = unserialize($api_fb['sync_down_config']);
		$api_fb['sync_fw_config'] = unserialize($api_fb['sync_fw_config']);
		$accesskey_fb = unserialize($api_fb['accesskey']);
	}else{
		return false;
	}

	$facebook = new Facebook(array(
	  'appId'  => FB_APPID,
	  'secret' => FB_SECRET,
	  'cookie' => true,
	));
	$facebook->setSession($accesskey_fb);

	$me = $facebook->api('/me');
	$me = arrayiconv::Conversion($me,"gbk","utf-8");

	if ($api_fb['updatetime'] == 0) {
		if ($me['statuses_count'] <= 200) {
			$ms = $facebook->user_timeline(1,200);
			$ms = arrayiconv::Conversion($ms,"gbk","utf-8");
			save_sync($uid,'facebook',$api_fb,$ms);
		}else{
			for ($i=intval($me['statuses_count']/200)+1; $i>0; $i--) {
				$ms = $facebook->user_timeline($i,200);
				$ms = arrayiconv::Conversion($ms,"gbk","utf-8");
				save_sync($uid,'facebook',$api_fb,$ms);
			}
		}
	}else{
		$ms = $facebook->user_timeline();
		$ms = arrayiconv::Conversion($ms,"gbk","utf-8");
		save_sync($uid,'facebook',$api_fb,$ms);
		fw_sync($uid,'facebook',$api_fb,$ms);
	}
}

function save_sync ($uid, $app, $api, $ms) {
	if (is_array($ms)) {
		foreach (array_reverse($ms) as $item) {
			$data = formatdata($app,$item);
			if ( $data['text']
			&& $data['dateline']>$api['updatetime']
			&& !strstr($data['from'],'舍内网')/* && !strstr($data['from'],'未通过审核应用')*/
			&& (!$api['sync_down_config']['nofw'] || !$data['fw']['id'])
			&& (!$api['sync_down_config']['nopic'] || (!$data['imgarr']['bmiddle_pic']&&!$data['fw']['imgarr']['bmiddle_pic'])) ) {
				//替换表情
				$tmp['text'] = formatface($app, $data['text']);
				//插入转发原文
				if($data['fw']['id']){
					$tmp['text'] .= '<div class="sub_doing_quote"><span>';
					$tmp['text'] .= $data['fw']['name'].'：'.$data['fw']['text'].'</span>';
					if($data['fw']['imgarr']['bmiddle_pic']){
						$tmp['text'] .= '<div class="img"><a target="_blank" href="'.$data['fw']['imgarr']['original_pic'].'"><img src="'.$data['fw']['imgarr']['bmiddle_pic'].'"></a></div>';
					}
					$tmp['text'] .= '<ol></ol></div>';
				}
				//记录数组
				$myarr = array(
					'uid' => $uid,
					'username' => $api['username'],
					'from' => $app,
					'dateline' => $data['dateline'],
					'message' => addslashes($tmp['text']),
					'image' => serialize($data['imgarr']),
					'ip' => getonlineip()
				);
				//跳过相同
				if (chkexist($uid,$myarr['message'])) continue;
				//入库
				$tmp['newdoid'] = inserttable('doing', $myarr, 1);
				//更新最后同步时间
				if ($api['sync_down_config']['mincycle']) {
					updatetable('api', array('updatetime'=>$data['dateline']+intval($api['sync_down_config']['mincycle'])), array('uid'=>$uid,'app'=>$app));
				}else{
					updatetable('api', array('updatetime'=>$data['dateline']), array('uid'=>$uid,'app'=>$app));
				}
				//事件Feed
				if (time()-$data['dateline']<3600*24*3) {
					if($data['fw']['id']){
						$tmp['retweeted'] = $data['fw']['name'].'：'.$data['fw']['text'];
						$tmp['body_template'] = '{retweeted}';
						$tmp['body_data'] = serialize(array('retweeted' => $tmp['retweeted']));
					}
					if($data['imgarr']['thumbnail_pic']){
						$tmp['image_1'] = $data['imgarr']['thumbnail_pic'];
						$tmp['image_1_link'] = 'space.php?do=doing&doid='.$tmp['newdoid'].'&goto=yes';
					}elseif($data['fw']['imgarr']['thumbnail_pic']){
						$tmp['image_1'] = $data['fw']['imgarr']['thumbnail_pic'];
						$tmp['image_1_link'] = 'space.php?do=doing&doid='.$tmp['newdoid'].'&goto=yes';
					}
					$feedarr = array(
						'appid' => UC_APPID,
						'icon' => 'doing',
						'uid' => $uid,
						'username' => $api['username'],
						'dateline' => $data['dateline'],
						'title_template' => cplang('feed_doing_title'),
						'title_data' => saddslashes(serialize(sstripslashes(array('message'=>$data['text'])))),
						'body_template' => $tmp['body_template'],
						'body_data' => addslashes($tmp['body_data']),
						'image_1' => $tmp['image_1'],
						'image_1_link' => $tmp['image_1_link'],
						'id' => $tmp['newdoid'],
						'idtype' => 'doid'
					);
					$feedarr['hash_template'] = md5($feedarr['title_template']."\t".$feedarr['body_template']);//喜好hash
					$feedarr['hash_data'] = md5($feedarr['title_template']."\t".$feedarr['title_data']."\t".$feedarr['body_template']."\t".$feedarr['body_data']);//合并hash
					inserttable('feed', $feedarr);
				}
				unset($tmp);
				unset($myarr);
				unset($feedarr);
			}
			unset($data);
		}
		//更新最后操作时间
		updatetable('api', array('donetime'=>time()), array('uid'=>$uid,'app'=>$app));
	}
}

function fw_sync ($uid, $app, $api, $ms) {
	global $_SGLOBAL;
	if ($app=='sina') {
		$sync_text = '新浪微博';
	}elseif($app=='qq') {
		$sync_text = '腾讯微博';
	}
	if (is_array($ms)) {
		if ($app != 'sina' && !$api['sync_fw_config']['nosina']) {
			$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('api')." WHERE uid='$uid' and app='sina'");
			$api_sina = $_SGLOBAL['db']->fetch_array($query);
			if ($api_sina) {
//				include_once(S_ROOT.'./source/OAuth.php');
				include_once(S_ROOT.'./source/OAuth10a.php');
				include_once(S_ROOT.'./source/function_sina_t.php');
				$accesskey_sina = unserialize($api_sina['accesskey']);
				$sina = new WeiboClient( WB_AKEY , WB_SKEY , $accesskey_sina['oauth_token'] , $accesskey_sina['oauth_token_secret'] );
			}
		}
		if ($app != 'qq' && !$api['sync_fw_config']['noqq']) {
			$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('api')." WHERE uid='$uid' and app='qq'");
			$api_qq = $_SGLOBAL['db']->fetch_array($query);
			if ($api_qq) {
//				include_once(S_ROOT.'./source/OAuth.php');
				include_once(S_ROOT.'./source/OAuth10.php');
				include_once(S_ROOT.'./source/function_qq_t.php');
				$accesskey_qq = unserialize($api_qq['accesskey']);
				$qq = new MBApiClient( MB_AKEY , MB_SKEY , $accesskey_qq['oauth_token'] , $accesskey_qq['oauth_token_secret']  );
			}
		}
		set_time_limit(300);
		foreach (array_reverse($ms) as $item) {
			$data = formatdata($app,$item);
			if ( $data['origtext']
			&& $data['dateline']>$api['updatetime']
			&& !strstr($data['from'],'舍内网')// && !strstr($data['from'],'未通过审核应用')
			&& !$data['fw']['id']
			&& (!$api['sync_down_config']['nopic'] || !$data['imgarr']['original_pic']) ) {
				//替换表情
				if ($sina) {
					$tmp['text'] = formatface($app, $data['origtext'], 'sina');
				}
				if ($qq) {
					$tmp['text'] = formatface($app, $data['origtext'], 'qq');
				}
				//同步微博	Add By 01
//				$tmp['post'] = siconv($tmp['text']." 〖From〗".$sync_text."【自动同步 By #舍内网#】( 我也要同步：http://u.shenei.net/space-doing.html )", "utf-8", "gbk");
				$tmp['post'] = siconv($tmp['text']." 〖From〗".$sync_text."【By】#舍内网#".(rand(0,5)?"":" ( 设置同步：http://u.shenei.net/space-doing.html )"), "utf-8", "gbk");
				if ($sina) {
					if ($data['imgarr']['original_pic']) {
						$tmp['sina_r'] = $sina->upload( $tmp['post'] , $data['imgarr']['original_pic'] );
					}else{
						$tmp['sina_r'] = $sina->update( $tmp['post'] );
					}
				}
				if ($qq) {
					$tmp['postarr'] = array(
						'c' => $tmp['post'],
						'ip' => $_SERVER['REMOTE_ADDR'], 
						'j' => '',
						'w' => ''
					);
					$tmp['imgname'] = array_reverse(explode('/', $data['imgarr']['original_pic']));
					if ($data['imgarr']['original_pic']) {
						$tmp['postarr']['p'] = array('image/jpeg',$tmp['imgname'][0],file_get_contents($data['imgarr']['original_pic']));
						$tmp['postarr']['type'] = 0;
					}
					$tmp['qq_r'] = $qq->postOne( $tmp['postarr'] );
				}
				unset($tmp);
			}
			unset($data);
		}
		//更新最后操作时间
		updatetable('api', array('donetime'=>time()), array('uid'=>$uid,'app'=>$app));
	}
}


function formatdata($app, $data) {
	if ($app == 'sina') {
		$textlink = autolink($data['text']);
		$fwtextlink = autolink($data['retweeted_status']['text']);
		$formatdata = array(
			'id' => $data['id'],
			'name' => $data['user']['name'],
			'text' => $textlink?$textlink:$data['text'],
			'origtext' => $data['text'],
			'dateline' => strtotime($data['created_at']),
			'from' => $data['source']
		);
		if ($data['user']['profile_image_url']) {
			$formatdata['head'] = array(
				'thumbnail_pic' => $data['user']['profile_image_url'],
				'original_pic' => str_replace("/50/","/180/",$data['user']['profile_image_url'])
			);
		}
		if ($data['original_pic']) {
			$formatdata['imgarr'] = array(
				'thumbnail_pic' => $data['thumbnail_pic'],
				'bmiddle_pic' => $data['bmiddle_pic'],
				'original_pic' => $data['original_pic']
			);
		}
		if ($data['retweeted_status']) {
			$formatdata['fw'] = array(
				'id' => $data['retweeted_status']['id'],
				'name' => $data['retweeted_status']['user']['name'],
				'text' => $fwtextlink?$fwtextlink:$data['retweeted_status']['text'],
				'origtext' => $data['retweeted_status']['text'],
				'dateline' => strtotime($data['retweeted_status']['created_at']),
				'from' => $data['retweeted_status']['source']
			);
			if ($data['retweeted_status']['user']['profile_image_url']) {
				$formatdata['fw']['head'] = array(
					'thumbnail_pic' => $data['retweeted_status']['user']['profile_image_url'],
					'original_pic' => str_replace("/50/","/180/",$data['retweeted_status']['user']['profile_image_url'])
				);
			}
			if ($data['retweeted_status']['original_pic']) {
				$formatdata['fw']['imgarr'] = array(
					'thumbnail_pic' => $data['retweeted_status']['thumbnail_pic'],
					'bmiddle_pic' => $data['retweeted_status']['bmiddle_pic'],
					'original_pic' => $data['retweeted_status']['original_pic']
				);
			}
		}
	} elseif ($app == 'qq') {
		$formatdata = array(
			'id' => $data['id'],
			'name' => $data['name'],
			'text' => $data['text']?$data['text']:$data['origtext'],
			'origtext' => $data['origtext'],
			'dateline' => $data['timestamp'],
			'from' => $data['from']
		);
		if ($data['head']) {
			$formatdata['head'] = array(
				'thumbnail_pic' => $data['head'].'/50',
				'original_pic' => $data['head'].'/100'
			);
		}
		if ($data['image']) {
			$formatdata['imgarr'] = array(
				'thumbnail_pic' => $data['image'][0].'/160',
				'bmiddle_pic' => $data['image'][0].'/460',
				'original_pic' => $data['image'][0].'/2000'
			);
		}
		if ($data['source']) {
			$formatdata['fw'] = array(
				'id' => $data['source']['id'],
				'name' => $data['source']['name'],
				'text' => $data['source']['text']?$data['source']['text']:$data['source']['origtext'],
				'origtext' => $data['source']['origtext'],
				'dateline' => $data['source']['timestamp'],
				'from' => $data['source']['from']
			);
			if ($data['source']['head']) {
				$formatdata['fw']['head'] = array(
					'thumbnail_pic' => $data['source']['head'].'/50',
					'original_pic' => $data['source']['head'].'/100'
				);
			}
			if ($data['source']['image']) {
				$formatdata['fw']['imgarr'] = array(
					'thumbnail_pic' => $data['source']['image'][0].'/160',
					'bmiddle_pic' => $data['source']['image'][0].'/460',
					'original_pic' => $data['source']['image'][0].'/2000'
				);
			}
		}
	}
	return $formatdata;
}

/**自动转为超链接*/
function autolink($foo) 
{
	$foo = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="\\1" target=_blank rel=nofollow>\\1</a>', $foo);
	if( strpos($foo, "http") === FALSE )
	{
		$foo = eregi_replace('(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="http://\\1" target=_blank rel=nofollow >\\1</a>', $foo);
    }
	else
	{
		$foo = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '\\1<a href="http://\\2" target=_blank rel=nofollow >\\2</a>', $foo);
	}
	return $foo; 
}

function formatface($app, $data, $toapp) {
	if ($app == 'sina') {
		$match='/\[(.+)]/isU';
		$formatdata = formatface_sub($app,$data,$match,$toapp);
	} elseif ($app == 'qq') {
		$match[0]='/\/(.{2})/isU';
		$match[1]='/\/(.{4})/isU';
		$match[2]='/\/(.{6})/isU';
		for ($i=0;$i<3;$i++) {
			$tmpdata = formatface_sub($app,$data,$match[$i],$toapp);
			if ($data != $tmpdata) {
				$formatdata = $tmpdata;
			}
		}
		$formatdata = $formatdata?$formatdata:$data;
	}
	return $formatdata;
}
function formatface_sub($app, $data, $match, $toapp) {
	global $_SGLOBAL;
	if (preg_match_all($match,$data,$arr)) {
		$formatdata = $data;
		for ($i=0;$i<count($arr[1]);$i++) {
			$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('face')." WHERE $app='{$arr[1][$i]}' ORDER BY level");
			$face = $_SGLOBAL['db']->fetch_array($query);
			$face_text = $arr[0][$i];
			$arr2 = explode($face_text, $formatdata);
			if ($face['id']) {
				if ($toapp) {
					if ($toapp == 'sina' && $face[$toapp]) {
						$face_text = '['.$face[$toapp].']';
					} elseif ($toapp == 'qq' && $face[$toapp]) {
						$face_text = '/'.$face[$toapp];
					}
				}else{
					$face_text = '<img src="image/face/'.$face['id'].'.gif" class="face">';
				}
				$formatdata = $arr2[0].$face_text.$arr2[1];
			}
		}
	}else{
		$formatdata = $data;
	}
	return $formatdata;
}

function chktime($uid, $app, $time) {
	global $_SGLOBAL;
	$query = $_SGLOBAL['db']->query("SELECT donetime FROM ".tname('api')." WHERE app='$app' AND uid='$uid'");
	$donetime = $_SGLOBAL['db']->fetch_array($query);
	if (time()-$donetime['donetime'] > $time) {
		return true;
	}else{
		return false;
	}
}

function chkexist($uid, $text) {
	global $_SGLOBAL;
	$query = $_SGLOBAL['db']->query("SELECT doid FROM ".tname('doing')." WHERE message='$text' AND uid='$uid'");
	$doing = $_SGLOBAL['db']->fetch_array($query);
	if ($doing) {
		return true;
	}else{
		return false;
	}
}
?>
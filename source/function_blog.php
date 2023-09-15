<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: function_blog.php 13245 2009-08-25 02:01:40Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//添加博客
function blog_post($POST, $olds=array()) {
	global $_SGLOBAL, $_SC, $_SN, $space;	//Modify By 01
	
	//操作者角色切换
	$isself = 1;
	if(!empty($olds['uid']) && $olds['uid'] != $_SGLOBAL['supe_uid']) {
		$isself = 0;
		$__SGLOBAL = $_SGLOBAL;
		$_SGLOBAL['supe_uid'] = $olds['uid'];
		$_SGLOBAL['supe_username'] = addslashes($olds['username']);
	}

	//标题
	$POST['subject'] = getstr(trim($POST['subject']), 80, 1, 1, 1);
	if(strlen($POST['subject'])<1) $POST['subject'] = sgmdate('Y-m-d');
	$POST['friend'] = intval($POST['friend']);
	
	//隐私
	$POST['target_ids'] = '';
	if($POST['friend'] == 2) {
		//特定好友
		$uids = array();
		$names = empty($_POST['target_names'])?array():explode(' ', str_replace(cplang('tab_space'), ' ', $_POST['target_names']));
		if($names) {
			$query = $_SGLOBAL['db']->query("SELECT uid FROM ".tname('space')." WHERE username IN (".simplode($names).")");
			while ($value = $_SGLOBAL['db']->fetch_array($query)) {
				$uids[] = $value['uid'];
			}
		}
		if(empty($uids)) {
			$POST['friend'] = 3;//仅自己可见
		} else {
			$POST['target_ids'] = implode(',', $uids);
		}
	} elseif($POST['friend'] == 4) {
		//加密
		$POST['password'] = trim($POST['password']);
		if($POST['password'] == '') $POST['friend'] = 0;//公开
	}
	if($POST['friend'] !== 2) {
		$POST['target_ids'] = '';
	}
	if($POST['friend'] !== 4) {
		$POST['password'] == '';
	}

	$POST['tag'] = shtmlspecialchars(trim($POST['tag']));
	$POST['tag'] = getstr($POST['tag'], 500, 1, 1, 1);	//语词屏蔽

	//内容
	if($_SGLOBAL['mobile']) {
		$POST['message'] = getstr($POST['message'], 0, 1, 0, 1, 1);
	} else {
		$POST['message'] = checkhtml($POST['message']);
		$POST['message'] = getstr($POST['message'], 0, 1, 0, 1, 0, 1);
		$POST['message'] = preg_replace(array(
				"/\<div\>\<\/div\>/i",
				"/\<a\s+href\=\"([^\>]+?)\"\>/i"
			), array(
				'',
				'<a href="\\1" target="_blank">'
			), $POST['message']);
	}
	$message = $POST['message'];
	
	//图片本地化	Add By 01
	include_once(S_ROOT.'./source/class.sp.php');
	$sp = new sp($message);
	$message = $sp->init();

	//个人分类
	if(empty($olds['classid']) || $POST['classid'] != $olds['classid']) {
		if(!empty($POST['classid']) && substr($POST['classid'], 0, 4) == 'new:') {
			//分类名
			$classname = shtmlspecialchars(trim(substr($POST['classid'], 4)));
			$classname = getstr($classname, 0, 1, 1, 1);
			if(empty($classname)) {
				$classid = 0;
			} else {
				$classid = getcount('class', array('classname'=>$classname, 'uid'=>$_SGLOBAL['supe_uid']), 'classid');
				if(empty($classid)) {
					$setarr = array(
						'classname' => $classname,
						'uid' => $_SGLOBAL['supe_uid'],
						'dateline' => $_SGLOBAL['timestamp']
					);
					$classid = inserttable('class', $setarr, 1);
				}
			}
		} else {
			$classid = intval($POST['classid']);

		}
	} else {
		$classid = $olds['classid'];
	}
	if($classid && empty($classname)) {
		//是否是自己的
		$classname = getcount('class', array('classid'=>$classid, 'uid'=>$_SGLOBAL['supe_uid']), 'classname');
		if(empty($classname)) $classid = 0;
	}
	
	//主表
	$blogarr = array(
		'subject' => $POST['subject'],
		'classid' => $classid,
		'friend' => $POST['friend'],
		'password' => $POST['password'],
		'noreply' => empty($_POST['noreply'])?0:1,
		'hiddenreply' => empty($_POST['hiddenreply'])?0:1	//Add By 01
	);

	//标题图片
	$titlepic = '';
	
	//获取上传的图片
	$uploads = array();
	if(!empty($POST['picids'])) {
		$picids = array_keys($POST['picids']);
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('pic')." WHERE picid IN (".simplode($picids).") AND uid='$_SGLOBAL[supe_uid]'");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			if(empty($titlepic) && $value['thumb']) {
				$titlepic = $value['filepath'].'.thumb.jpg';
				$blogarr['picflag'] = $value['remote']?2:1;
			}
			$uploads[$POST['picids'][$value['picid']]] = $value;
		}
		if(empty($titlepic) && $value) {
			$titlepic = $value['filepath'];
			$blogarr['picflag'] = $value['remote']?2:1;
		}
	}
	
	//插入文章
	if($uploads) {
		preg_match_all("/\<img\s.*?\_uchome\_localimg\_([0-9]+).+?src\=\"(.+?)\"/i", $message, $mathes);
		if(!empty($mathes[1])) {
			$searchs = $idsearchs = array();
			$replaces = array();
			foreach ($mathes[1] as $key => $value) {
				if(!empty($mathes[2][$key]) && !empty($uploads[$value])) {
					$searchs[] = $mathes[2][$key];
					$idsearchs[] = "_uchome_localimg_$value";
					$replaces[] = pic_get($uploads[$value]['filepath'], $uploads[$value]['thumb'], $uploads[$value]['remote'], 0);
					unset($uploads[$value]);
				}
			}
			if($searchs) {
				$message = str_replace($searchs, $replaces, $message);
				$message = str_replace($idsearchs, 'uchomelocalimg[]', $message);
			}
		}
		//未插入文章
		foreach ($uploads as $value) {
			$picurl = pic_get($value['filepath'], $value['thumb'], $value['remote'], 0);
			$message .= "<div class=\"uchome-message-pic\"><img src=\"$picurl\"><p>$value[title]</p></div>";
		}
	}
	
	//没有填写任何东西
	$ckmessage = preg_replace("/(\<div\>|\<\/div\>|\s|\&nbsp\;|\<br\>|\<p\>|\<\/p\>)+/is", '', $message);
	if(empty($ckmessage)) {
		return false;
	}
	
	//最小日志字数 Add By 01
	if(strlen($ckmessage)<50){
		showmessage('这么短的日志就写在一句话博客(记录)里就好了嘛！');
	}
	
	//添加slashes
	$message = addslashes($message);
	
	//从内容中读取图片
	if(empty($titlepic)) {
		$titlepic = getmessagepic($message);
		$blogarr['picflag'] = 0;
	}
	$blogarr['pic'] = $titlepic;
	
	//热度
	if(checkperm('manageblog')) {
		$blogarr['hot'] = intval($POST['hot']);
	}
	
	if($olds['blogid']) {
		//更新
		$blogid = $olds['blogid'];
		updatetable('blog', $blogarr, array('blogid'=>$blogid));
		
		$fuids = array();
		
		$blogarr['uid'] = $olds['uid'];
		$blogarr['username'] = $olds['username'];
	} else {
		//参与热闹
		$blogarr['topicid'] = topic_check($POST['topicid'], 'blog');

		$blogarr['uid'] = $_SGLOBAL['supe_uid'];
		$blogarr['username'] = $_SGLOBAL['supe_username'];
		$blogarr['dateline'] = empty($POST['dateline'])?$_SGLOBAL['timestamp']:$POST['dateline'];
		$blogid = inserttable('blog', $blogarr, 1);
	}
	
	$blogarr['blogid'] = $blogid;
	
	//附表	
	$fieldarr = array(
		'message' => $message,
		'postip' => getonlineip(),
		'target_ids' => $POST['target_ids']
	);
	
	//TAG
	$oldtagstr = addslashes(empty($olds['tag'])?'':implode(' ', unserialize($olds['tag'])));
	

	$tagarr = array();
	if($POST['tag'] != $oldtagstr) {
		if(!empty($olds['tag'])) {
			//先把以前的给清理掉
			$oldtags = array();
			$query = $_SGLOBAL['db']->query("SELECT tagid, blogid FROM ".tname('tagblog')." WHERE blogid='$blogid'");
			while ($value = $_SGLOBAL['db']->fetch_array($query)) {
				$oldtags[] = $value['tagid'];
			}
			if($oldtags) {
				$_SGLOBAL['db']->query("UPDATE ".tname('tag')." SET blognum=blognum-1 WHERE tagid IN (".simplode($oldtags).")");
				$_SGLOBAL['db']->query("DELETE FROM ".tname('tagblog')." WHERE blogid='$blogid'");
			}
		}
		$tagarr = tag_batch($blogid, $POST['tag']);
		//更新附表中的tag
		$fieldarr['tag'] = empty($tagarr)?'':addslashes(serialize($tagarr));
	}

	if($olds) {
		//更新
		updatetable('blogfield', $fieldarr, array('blogid'=>$blogid));
	} else {
		$fieldarr['blogid'] = $blogid;
		$fieldarr['uid'] = $blogarr['uid'];
		inserttable('blogfield', $fieldarr);
	}

	//空间更新
	if($isself) {
		if($olds) {
			//空间更新
			$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET updatetime='$_SGLOBAL[timestamp]' WHERE uid='$_SGLOBAL[supe_uid]'");
		} else {
			if(empty($space['blognum'])) {
				$space['blognum'] = getcount('blog', array('uid'=>$space['uid']));
				$blognumsql = "blognum=".$space['blognum'];
			} else {
				$blognumsql = 'blognum=blognum+1';
			}
			//积分
			$reward = getreward('publishblog', 0);
			$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET {$blognumsql}, lastpost='$_SGLOBAL[timestamp]', updatetime='$_SGLOBAL[timestamp]', credit=credit+$reward[credit], experience=experience+$reward[experience] WHERE uid='$_SGLOBAL[supe_uid]'");
			
			//统计
			updatestat('blog');
		}
	}
	
	//产生feed
	if($POST['makefeed']) {
		include_once(S_ROOT.'./source/function_feed.php');
		feed_publish($blogid, 'blogid', $olds?0:1);
	}
	
	//热闹
	if(empty($olds) && $blogarr['topicid']) {
		topic_join($blogarr['topicid'], $_SGLOBAL['supe_uid'], $_SGLOBAL['supe_username']);
	}
	
	//同步WordPress	Add By 01
	if (empty($olds)) {
		if ($_SGLOBAL['wp_uid'] && $_SC['wp_blog_level'] > 1 && $_SC['wp_sync_new']) {
			$new_post = array (
				'post_author' => $_SGLOBAL['wp_uid'],
				'post_title' => $blogarr['subject'],
				'post_name' => urlencode(iconv('gbk','utf-8',$blogarr['subject'])),
				'post_content' => $fieldarr['message'],
				'post_password' => $blogarr['password'],
				'comment_status' => $blogarr['noreply']?'closed':'open',
				'post_type' => 'post',
				'post_date' => date('Y-m-d H:i:s', $_SGLOBAL['timestamp']),
				'post_date_gmt' => date('Y-m-d H:i:s', $_SGLOBAL['timestamp']-28800),
				'post_modified' => date('Y-m-d H:i:s', $_SGLOBAL['timestamp']),
				'post_modified_gmt' => date('Y-m-d H:i:s', $_SGLOBAL['timestamp']-28800)
			);
			//Private
			if ($blogarr['friend'] == 3) {
				$new_post['post_status'] = 'draft';
			} elseif ($blogarr['friend'] == 2) {
				$target_ids = explode(",",$fieldarr['target_ids']);
				foreach ($target_ids as $target_id) {
					$username_arr[] = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT username FROM ".tname('member')." WHERE uid='$target_id'"), 0);
				}
				$access_users = implode(",",$username_arr);
				$new_post['post_status'] = 'publish';
				unset($username_arr);
			} elseif ($blogarr['friend'] == 1) {
				$query = $_SGLOBAL['db']->query("SELECT fusername FROM ".tname('friend')." WHERE uid='$_SGLOBAL[supe_uid]'");
				while ($value = $_SGLOBAL['db']->fetch_array($query)) {
					$fusername_array[] = $value['fusername'];
				}
				$access_users = implode(",",$fusername_array);
				$new_post['post_status'] = 'publish';
				unset($fusername_array);
			}else{
				$new_post['post_status'] = 'publish';
			}
			$post_id = inserttable($_SC['wp_usertablepre'].'posts', $new_post, 1, false, 0, 'wpdb');
			if ($post_id) {
				updatetable($_SC['wp_usertablepre'].'posts', array('guid'=>$_SGLOBAL['wp_url'].'?p='.$post_id), array('ID'=>$post_id), 0, 'wpdb');
				inserttable($_SC['wp_usertablepre'].'postmeta', array('post_id'=>$post_id, 'meta_key'=>'uch_blogid', 'meta_value'=>$blogid), 0, false, 0, 'wpdb');
				if ($access_users) inserttable($_SC['wp_usertablepre'].'postmeta', array('post_id'=>$post_id, 'meta_key'=>'access_users', 'meta_value'=>$access_users), 0, false, 0, 'wpdb');
			}
		}
	}
	
	//同步微博	Add By 01
	if (empty($olds) || ($_SGLOBAL['timestamp']-$POST['dateline']) > 3600) {
		if (empty($olds)) {
			$text_head = '发表了新日志：';
		}else{
			$text_head = '修改更新了一篇日志：';
		}
		if ($_POST['sina']) {
//			include_once(S_ROOT.'./source/OAuth.php');
			include_once(S_ROOT.'./source/OAuth10a.php');
			include_once(S_ROOT.'./source/function_sina_t.php');
			$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('api')." WHERE uid='$_SGLOBAL[supe_uid]' and app='sina'");
			$api_sina = $_SGLOBAL['db']->fetch_array($query);
			$accesskey_sina = unserialize($api_sina['accesskey']);
			
			$sina = new WeiboClient( WB_AKEY , WB_SKEY , $accesskey_sina['oauth_token'] , $accesskey_sina['oauth_token_secret'] );
			$text = $text_head.$POST['subject']."( ".$_SC[siteurl]."viewspace.php?uid=".$_SGLOBAL['supe_uid']."&do=blog&id=".$blogid." ) 【From】#舍内网#";
			$text = siconv($text, "utf-8", "gbk");
			$sina_r = $sina->update( $text );
		}
		if ($_POST['qq']) {
//			include_once(S_ROOT.'./source/OAuth.php');
			include_once(S_ROOT.'./source/OAuth10.php');
			include_once(S_ROOT.'./source/function_qq_t.php');
			$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('api')." WHERE uid='$_SGLOBAL[supe_uid]' and app='qq'");
			$api_qq = $_SGLOBAL['db']->fetch_array($query);
			$accesskey_qq = unserialize($api_qq['accesskey']);
			
			$qq = new MBApiClient( MB_AKEY , MB_SKEY , $accesskey_qq['oauth_token'] , $accesskey_qq['oauth_token_secret']  );
			$text = $text_head.$POST['subject']."( ".$_SC[siteurl]."viewspace.php?uid=".$_SGLOBAL['supe_uid']."&do=blog&id=".$blogid." ) 【From】#舍内网#";
			$text = siconv($text, "utf-8", "gbk");
			$post = array(
				'c' => $text,
				'ip' => $_SERVER['REMOTE_ADDR'], 
				'j' => '',
				'w' => ''
			);
			$qq_r = $qq->postOne( $post );
		}
		if ($_POST['facebook']) {
			include_once(S_ROOT.'./source/function_facebook.php');
			include_once(S_ROOT . './source/class.arrayiconv.php');
			$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('api')." WHERE uid='$_SGLOBAL[supe_uid]' and app='facebook'");
			$api_fb = $_SGLOBAL['db']->fetch_array($query);
			$accesskey_fb = unserialize($api_fb['accesskey']);
			
			$facebook = new Facebook(array(
			  'appId'  => FB_APPID,
			  'secret' => FB_SECRET,
			  'cookie' => true,
			));
			$facebook->setSession($accesskey_fb);
			$post_feed = array(
				'name' => $POST['subject'],
				'link' => $_SC[siteurl]."viewspace.php?uid=".$_SGLOBAL['supe_uid']."&do=blog&id=".$blogid, 
				'caption' => $_SC[siteurl].'space-'.$_SGLOBAL['supe_uid'].'-do-blog-id-'.$blogid.'.html',
				'picture' => $titlepic,
				'description' => getstr($message, 150, 1, 1, 0, 0, -1),
				'actions' => array(
					'name' => '@'.$_SN[$_SGLOBAL[supe_uid]].' On 舍内网',
					'link' => $_SC[siteurl]."viewspace.php?uid=".$_SGLOBAL['supe_uid']
				)
			);
			if (empty($olds)) {
				$post_feed['message'] = '在【舍内网】发表了新日志：';
			}else{
				$post_feed['message'] = '在【舍内网】修改更新了一篇日志：';
			}
			$post_feed = arrayiconv::Conversion($post_feed,"utf-8","gbk");
			$fb_fr = $facebook->api('/me/feed', "POST", $post_feed);
			if (empty($olds) && $_POST['facebook_note']) {
				$inscription  = '<br><br><br><strong>　　此日志由<a href="'.$_SC[siteurl]."viewspace.php?uid=".$_SGLOBAL['supe_uid'].'">'.$_SN[$_SGLOBAL[supe_uid]].'</a>在【<a href="'.$_SC[siteurl].'">舍内网</a>】发布~<br>';
				$inscription .= '　　　需评论请到原文地址操作！否则'.$_SN[$_SGLOBAL[supe_uid]].'也许无法及时回复。。<br>';
				$inscription .= '　　　　原文地址：<a href="'.$_SC[siteurl]."viewspace.php?uid=".$_SGLOBAL['supe_uid']."&do=blog&id=".$blogid.'">'.$_SC[siteurl].'space-'.$_SGLOBAL['supe_uid'].'-do-blog-id-'.$blogid.'.html</a></strong>';
				$post = array(
					'subject' => $POST['subject'],
					'message' => getstr(strip_tags($message, "<p> <br> <b> <strong> <a>"), 0, 1, 1, 0, 0, 1).$inscription
				);
				$post = arrayiconv::Conversion($post,"utf-8","gbk");
				$fb_pr = $facebook->api('/me/notes', "POST", $post);
			}
		}
	}

	//角色切换
	if(!empty($__SGLOBAL)) $_SGLOBAL = $__SGLOBAL;

	return $blogarr;
}

//处理tag
function tag_batch($blogid, $tags) {
	global $_SGLOBAL;

	$tagarr = array();
	$tagnames = empty($tags)?array():array_unique(explode(' ', $tags));
	if(empty($tagnames)) return $tagarr;

	$vtags = array();
	$query = $_SGLOBAL['db']->query("SELECT tagid, tagname, close FROM ".tname('tag')." WHERE tagname IN (".simplode($tagnames).")");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		$value['tagname'] = addslashes($value['tagname']);
		$vkey = md5($value['tagname']);
		$vtags[$vkey] = $value;
	}
	$updatetagids = array();
	foreach ($tagnames as $tagname) {
		if(!preg_match('/^([\x7f-\xff_-]|\w){3,20}$/', $tagname)) continue;
		
		$vkey = md5($tagname);
		if(empty($vtags[$vkey])) {
			$setarr = array(
				'tagname' => $tagname,
				'uid' => $_SGLOBAL['supe_uid'],
				'dateline' => $_SGLOBAL['timestamp'],
				'blognum' => 1
			);
			$tagid = inserttable('tag', $setarr, 1);
			$tagarr[$tagid] = $tagname;
		} else {
			if(empty($vtags[$vkey]['close'])) {
				$tagid = $vtags[$vkey]['tagid'];
				$updatetagids[] = $tagid;
				$tagarr[$tagid] = $tagname;
			}
		}
	}
	if($updatetagids) $_SGLOBAL['db']->query("UPDATE ".tname('tag')." SET blognum=blognum+1 WHERE tagid IN (".simplode($updatetagids).")");
	$tagids = array_keys($tagarr);
	$inserts = array();
	foreach ($tagids as $tagid) {
		$inserts[] = "('$tagid','$blogid')";
	}
	if($inserts) $_SGLOBAL['db']->query("REPLACE INTO ".tname('tagblog')." (tagid,blogid) VALUES ".implode(',', $inserts));

	return $tagarr;
}

//获取日志图片
function getmessagepic($message) {
	$pic = '';
	$message = stripslashes($message);
	$message = preg_replace("/\<img src=\".*?image\/face\/(.+?).gif\".*?\>\s*/is", '', $message);	//移除表情符
	preg_match("/src\=[\"\']*([^\>\s]{25,105})\.(jpg|gif|png)/i", $message, $mathes);
	if(!empty($mathes[1]) || !empty($mathes[2])) {
		$pic = "{$mathes[1]}.{$mathes[2]}";
	}
	return addslashes($pic);
}

//屏蔽html
function checkhtml($html) {
	$html = stripslashes($html);
	if(!checkperm('allowhtml')) {
		
		preg_match_all("/\<([^\<]+)\>/is", $html, $ms);

		$searchs[] = '<';
		$replaces[] = '&lt;';
		$searchs[] = '>';
		$replaces[] = '&gt;';
		
		if($ms[1]) {
			$allowtags = 'img|a|font|div|table|tbody|caption|tr|td|th|br|p|b|strong|i|u|em|span|ol|ul|li|blockquote|object|param|embed';//允许的标签
			$ms[1] = array_unique($ms[1]);
			foreach ($ms[1] as $value) {
				$searchs[] = "&lt;".$value."&gt;";
				$value = shtmlspecialchars($value);
				$value = str_replace(array('\\','/*'), array('.','/.'), $value);
				$value = preg_replace(array("/(javascript|script|eval|behaviour|expression)/i", "/(\s+|&quot;|')on/i"), array('.', ' .'), $value);
				if(!preg_match("/^[\/|\s]?($allowtags)(\s+|$)/is", $value)) {
					$value = '';
				}
				$replaces[] = empty($value)?'':"<".str_replace('&quot;', '"', $value).">";
			}
		}
		$html = str_replace($searchs, $replaces, $html);
	}
	$html = addslashes($html);
	
	return $html;
}

//屏蔽html
function checpopkhtml($html) {
	$html = stripslashes($html);
		preg_match_all("/\<([^\<]+)\>/is", $html, $ms);

		$searchs[] = '<';
		$replaces[] = '&lt;';
		$searchs[] = '>';
		$replaces[] = '&gt;';
		
		if($ms[1]) {
			$allowtags = 'img|a|font|div|table|tbody|caption|tr|td|th|br|p|b|strong|i|u|em|span|ol|ul|li|blockquote|object|param|embed';//允许的标签
			$ms[1] = array_unique($ms[1]);
			foreach ($ms[1] as $value) {
				$searchs[] = "&lt;".$value."&gt;";
				$value = shtmlspecialchars($value);
				$value = str_replace(array('\\','/*'), array('.','/.'), $value);
				$value = preg_replace(array("/(javascript|script|eval|behaviour|expression)/i", "/(\s+|&quot;|')on/i"), array('.', ' .'), $value);
				if(!preg_match("/^[\/|\s]?($allowtags)(\s+|$)/is", $value)) {
					$value = '';
				}
				$replaces[] = empty($value)?'':"<".str_replace('&quot;', '"', $value).">";
			}
		}
	$html = str_replace($searchs, $replaces, $html);
	$html = addslashes($html);
	return $html;
}
//视频标签处理
function blog_bbcode($message) {
	$message = preg_replace("/\[flash\=?(media|real)*\](.+?)\[\/flash\]/ie", "blog_flash('\\2', '\\1')", $message);
	$message = preg_replace( "/\\[music\\=?(auto)*\\](.+?)\\[\\/music\\]/ie","blog_music(\"\\2\", \"\\1\")",$message);
	return $message;
}
//视频
function blog_flash($swf_url, $type='') {
	$width = '520';
	$height = '390';
	if ($type == 'media') {
		$html = '<object classid="clsid:6bf52a52-394a-11d3-b153-00c04f79faa6" width="'.$width.'" height="'.$height.'">
			<param name="autostart" value="0">
			<param name="url" value="'.$swf_url.'">
			<embed autostart="false" src="'.$swf_url.'" type="video/x-ms-wmv" width="'.$width.'" height="'.$height.'" controls="imagewindow" console="cons"></embed>
			</object>';
	} elseif ($type == 'real') {
		$html = '<object classid="clsid:cfcdaa03-8be4-11cf-b84b-0020afbbccfa" width="'.$width.'" height="'.$height.'">
			<param name="autostart" value="0">
			<param name="src" value="'.$swf_url.'">
			<param name="controls" value="Imagewindow,controlpanel">
			<param name="console" value="cons">
			<embed autostart="false" src="'.$swf_url.'" type="audio/x-pn-realaudio-plugin" width="'.$width.'" height="'.$height.'" controls="controlpanel" console="cons"></embed>
			</object>';
	} else {
		$html = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="'.$width.'" height="'.$height.'">
			<param name="movie" value="'.$swf_url.'">
			<param name="allowscriptaccess" value="always">
			<embed src="'.$swf_url.'" type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'" allowfullscreen="true" allowscriptaccess="always"></embed>
			</object>';
	}
	return $html;
}
//音乐	Add By 01
function blog_music( $music_url, $state )
{
    $optauto = "";
	$geshi = substr($music_url,-3,3);
	if ( $geshi == 'mp3') {
		if ( $state == "auto" )
		{
			$optauto = "&autostart=yes";
		}
   		$html = '<script language="JavaScript" src="image/audio-player.js"></script><object type="application/x-shockwave-flash" data="image/player.swf" height="24" width="290"><param name="movie" value="image/player.swf" /><param name="FlashVars" value="bg=0xCDDFF3&leftbg=0x357DCE&lefticon=0xF2F2F2&rightbg=0xF06A51&rightbghover=0xAF2910&righticon=0xF2F2F2&righticonhover=0xFFFFFF&text=0x357DCE&slider=0x357DCE&track=0xFFFFFF&border=0xFFFFFF&loader=0xAF2910&soundFile='.$music_url.$optauto.'" /><param name="quality" value="high" /><param name="menu" value="false" /><param name="wmode" value="transparent" /></object>';
	} else {
		if ( $state == "auto" )
		{
			$optauto = "1";
		} else {
			$optauto = "0";
		}
		$html ='<object height="64" width="290" data="'.$music_url.'" type="audio/x-ms-wma"><param value="'.$music_url.'" name="src"/><param value="'.$optauto.'" name="autostart"/><param value="true" name="controller"/></object>';
	}
    return $html;
}

//回复评论留言隐藏判断	Add By 01
function comment_hidden( $cid, $uid, $bool )
{
	global $_SGLOBAL;
	$query1 = $_SGLOBAL['db']->query("SELECT * FROM ".tname('comment')." WHERE cid='$cid'");
	$comment = $_SGLOBAL['db']->fetch_array($query1);
	
	if ( $comment['idtype'] == 'blogid' ) {
		$query0 = $_SGLOBAL['db']->query("SELECT hiddenreply FROM ".tname('blog')." WHERE blogid='$comment[id]'");
		$blog = $_SGLOBAL['db']->fetch_array($query0);
		
		if ( $blog['hiddenreply'] && !$comment['replyid'] ) {
			$comment['hiddenreply'] = $blog['hiddenreply'];
		}
		if ( $comment[replyid] ) {
			$query2 = $_SGLOBAL['db']->query("SELECT old.replyid,old.authorid,old.hiddenreply, older.authorid AS older_authorid,older.hiddenreply AS older_hiddenreply,older.replyid AS older_replyid FROM ".tname('comment')." AS old LEFT JOIN ".tname('comment')." AS older ON old.replyid=older.cid WHERE old.cid='$comment[replyid]'");
			$comment_old = $_SGLOBAL['db']->fetch_array($query2);
			
			if ( $blog['hiddenreply'] ) {
				if ( !$comment_old['replyid'] ) {
					$comment_old['hiddenreply'] = $blog['hiddenreply'];
				}else{
					if ( !$comment_old['older_replyid'] ) {
						$comment_old['older_hiddenreply'] = $blog['hiddenreply'];
					}
				}
			}
			if ( !$comment['hiddenreply'] && !$comment_old['hiddenreply'] ) {
				$message = $comment['message'];
				$replyenabled = 1;
			}else{
				$match='/^<div[^>]+class\=\"quote\"[^>]*>.*<span[^>]+class\=\"q\"[^>]*>.*<b>(.*)<\/b>: (.*)<\/span>.*<\/div>(.*)$/isU';
				if (preg_match_all($match,$comment['message'],$arr)) {
					$arr2 = explode(': '.$arr[2][0], $comment['message']);
					$arr22 = explode($arr[3][0], $arr2[1]);
					$reply_hidden_message = '<font color="dimgray">(此评论仅作者可见…)</font>';
					$this_hidden_message = '<font color="dimgray">(此回复仅作者可见…)</font>';
					
					if ($uid == $comment_old['authorid'] or $uid == $comment['authorid']) {
						$reply_message = $arr[2][0];
						$this_message = $arr[3][0];
						$replyenabled = 1;
					}elseif ($uid == $comment['uid']) {
						if($comment_old['hiddenreply']&&$comment_old['replyid']&&$comment_old['older_hiddenreply']&&$comment_old['older_authorid']!=$comment['uid']){
							$reply_message = $reply_hidden_message;
						}else{
							$reply_message = $arr[2][0];
						}
						if($comment['hiddenreply']){
							$this_message = $this_hidden_message;
							$replyenabled = 0;
						}else{
							$this_message = $arr[3][0];
							$replyenabled = 1;
						}
					}else{
						if($comment_old['hiddenreply']){
							$reply_message = $reply_hidden_message;
						}else{
							$reply_message = $arr[2][0];
						}
						if($comment['hiddenreply']){
							$this_message = $this_hidden_message;
							$replyenabled = 0;
						}else{
							$this_message = $arr[3][0];
							$replyenabled = 1;
						}
					}
					$message = $arr2[0].': '.$reply_message.$arr22[0].$this_message.$arr22[1];
				}elseif ($uid == $comment_old['authorid'] or $uid == $comment['authorid']) {
					$message = $comment['message'];
					$replyenabled = 1;
				}else{
					$message = '<font color="dimgray">(此回复评论仅作者可见…)</font>';
					$replyenabled = 0;
				}
			}
		}else{
			if ($uid == $comment['uid'] or $uid == $comment['authorid']) {
				$message = $comment['message'];
				$replyenabled = 1;
			}else{
				$message = $comment['hiddenreply']?'<font color="dimgray">(此评论仅作者可见…)</font>':$comment['message'];
				$replyenabled = 0;
			}
		}
	}elseif ( $comment['idtype'] == 'uid' ) {
		if ($uid == $comment['uid'] or $uid == $comment['authorid']) {
			$message = $comment['message'];
			$replyenabled = 1;
		}else{
			$message = $comment['hiddenreply']?'<font color="dimgray">(此留言是悄悄话噢…)</font>':$comment['message'];
			$replyenabled = 0;
		}
	}else{
		$message = $comment['message'];
		$replyenabled = 1;
	}
	
    if ( $bool ) {
		return $replyenabled;
	}else{
		return $message;
	}
}

?>

<?php

/*
	������ƣ�PicWall
	���ߣ�54alin	http://hialin.com
	��������ʹ�ã���ӭ����������
	�������ߵ��Ͷ�������ɾ������Ϣ��
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

$content ='<?xml version="1.0" encoding="gb2312" ?>';
$content.='<tiltviewergallery>';
$content.='<photos>';

$query = $_SGLOBAL['db']->query("SELECT s.picid, s.albumid, s.uid, s.filename, s.size, s.filepath
FROM ".tname('pic')." s
LEFT JOIN ".tname('album')." sf ON sf.albumid = s.albumid
WHERE s.size > '10000'
AND s.size < '100000'
AND sf.friend = '0'
ORDER BY s.picid DESC");
while($get = $_SGLOBAL['db']->fetch_array($query)){  
	$content.='<photo imageurl="http://u.shenei.net/attachment/'.$get['filepath'].'" linkurl="http://u.shenei.net/space.php?uid='.$get['uid'].'&amp;do=album&amp;picid='.$get['picid'].'">';
        $content.='<title>'.$get['filename'].'</title>';
        $content.='<description>'.$get['filename'].'</description>';
        $content.='</photo>';
}

$content.='</photos>';
$content.='</tiltviewergallery>';

$fp=fopen(S_ROOT."./picwall/show.xml","w+");
fwrite($fp,$content);
fclose($fp);                                       
?>
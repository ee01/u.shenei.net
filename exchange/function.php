<?php

if (!defined('IN_UCHOME')) {
	exit('Access Denied');
} 

//������Ƿ�ر�
function checkAPP($isAdmin){
global $_SGLOBAL, $_SC, $SConfig; 
if(!$isAdmin){

//����ر�
if ($SConfig["APP_OFF"] == 1 ) showmessage($SConfig["APP_TITLE"] . '��ʱ�رգ���͹���Ա��ϵ��', 'index.php', 5);

//�Զ������رղ��
$opendate = explode(",", $SConfig["OPENDATE"]);
$_opentime = explode(":", $SConfig["OPENTIME"]);
$_closetime = explode(":", $SConfig["CLOSETIME"]);

$nowdate=gmdate("d",$_SGLOBAL['timestamp']);
$_nowhour=gmdate("H",$_SGLOBAL['timestamp']);
$_nowminute=gmdate("i",$_SGLOBAL['timestamp']);

//$h=str_split($_nowhour);
$h=array();
$h[0]=substr($_nowhour,0,1);
$h[1]=substr($_nowhour,1,2);
if($h[0]>0){
	$nowhour=$_nowhour;
}else{
	$nowhour=$h[1];
}
//$m=str_split($_nowminute);
$m=array();
$m[0]=substr($_nowminute,0,1);
$m[1]=substr($_nowminute,1,2);

if($m[0]>0){
	$nowminute=($_nowminute);
}else{
	$nowminute=($m[1]);
}
$nowtime=($nowhour)*60+($nowminute);
$opentime = ($_opentime[0])*60+($_opentime[1]);
$closetime =  ($_closetime[0])*60+($_closetime[1]);

if($SConfig["APP_OFF"]==2){
		if(!in_array($nowdate ,$opendate)) showmessage($SConfig["APP_TITLE"] . '��ʱ�رգ�������'.$nowdate.'�š�    ��������:<br><br>'.$SConfig["OPENDATE"].'<br><br>��л֧��!', 'index.php', 10);			
		if($nowtime<$opentime ) showmessage('���� '.$SConfig["APP_TITLE"].' ����ʱ�� '.$SConfig["OPENTIME"]. '����'.($opentime-$nowtime). '����,��׼����Ŷ��', 'index.php', 10);	
		if($nowtime > $closetime) showmessage('�Ѿ����� '.$SConfig["APP_TITLE"].' ����ʱ�� '.$SConfig["OPENTIME"]. ' -'.($closetime-$nowtime). '���ӣ����´�������', 'index.php', 10);	
}

}
}

//���ʦ�Ƿ���Ҫ��¼
function checkislogin(){
global $_SGLOBAL;
if (empty($_SGLOBAL['supe_uid'])) showmessage('to_login', 'do.php?ac=login');
}

//���ע��ʱ���Ƿ񵽴�ҽ��޶����ʱ��
function checkregtime($needregtime){
	global $_SGLOBAL;	


	if($_SGLOBAL['my']['isamdin'] || $needregtime=="0") return true;
	if($_SGLOBAL['timestamp'] >= $_SGLOBAL['my']['dateline']+intval($needregtime)) return true;

	return false;
}

//����޴ζһ�
function checkrobnumber($robnumber,$gid){
	global $_SGLOBAL;	

	if($_SGLOBAL['my']['isamdin']||$robnumber<1) return true;	

     $count=0;
     $query = $_SGLOBAL['db'] -> query("SELECT gid,data FROM " . tname('exchange_orderform') . " WHERE gid=".$gid ); 
    
	 while ($value = $_SGLOBAL['db'] -> fetch_array($query)) {
		  if(gmdate("Y-m-d",$_SGLOBAL['timestamp']) != gmdate("Y-m-d",$value['data'])) $count ++;
	  }

    if($count<$robnumber) return true;

	return false;
}


// �����Ʒ
function gif_post($POST, $olds = array()) {
	global $_SGLOBAL, $_SC, $SConfig; 
    
	// ����
	$POST['subject'] = getstr($POST['subject'], 80, 1, 1, 1);
	if (strlen($POST['subject']) < 1) $POST['subject'] = sgmdate('Y-m-d'); 
	// ����
	$POST['message'] = checkhtml($POST['message']);
	$POST['message'] = getstr($POST['message'], 0, 1, 0, 1, 0, 1);
	$POST['message'] = preg_replace("/\<div\>\<\/div\>/i", '', $POST['message']);
	$message = $POST['message']; 
	// ��Ʒ����
	if (empty($olds['classid']) || $POST['classid'] != $olds['classid']) {
		if (!empty($POST['classid']) && substr($POST['classid'], 0, 4) == 'new:') {
			// ������
			$classname = shtmlspecialchars(trim(substr($POST['classid'], 4)));
			$classname = getstr($classname, 0, 1, 1, 1);
			if (empty($classname)) {
				$classid = 0;
			} else {
				$classid = getcount('exchange_class', array('classname' => $classname), 'classid');
				if (empty($classid)) {
					$setarr = array('classname' => $classname
						);
					$classid = inserttable('exchange_class', $setarr, 1);
				} 
			} 
		} else {
			$classid = intval($POST['classid']);
		} 
	} else {
		$classid = $olds['classid'];
	} 
	if ($classid && empty($classname)) {
		$classname = getcount('exchange_class', array('classid' => $classid), 'classname');
		if (empty($classname)) $classid = 0;
	} 
	$expiration = $POST['expiration'] ? strtotime($POST['expiration']) : '';
	$begin = $POST['begin'] ? strtotime($POST['begin']) : ''; 
	$needregtime=$POST['needregtime'] ? ($POST['needregtime']) : '0';
	$robnumber=$POST['robnumber'] ? ($POST['robnumber']) : '0';
	// ������Զ��������Զ����¿����Ŀ
	if (($POST['autokey']==1) && $olds['id']) {
		$POST['num'] = $_SGLOBAL['db'] -> result($_SGLOBAL['db'] -> query("SELECT COUNT(*) FROM " . tname('exchange_keys') . " WHERE gifts_id=" . $olds['id']));
	} 
	// ����
	$blogarr = array('title' => $POST['subject'],
		'rank' => $POST['rank'],
		'body' => $message,
		'type' => $classid,
		'num' => $POST['num']?intval($POST['num']):0,
		'price' => $POST['price']?intval($POST['price']):0,
		'sale' => $POST['sale']?intval($POST['sale']):0,
		'ac' => $POST['ac'],
		'autokey' => $POST['autokey']?$POST['autokey']:0,
		'url' => $POST['url'],
		'buynum' => $POST['buynum'],
		'robnumber'=>$POST['robnumber'],
		'begin' => $begin,
		'expiration' => $expiration ,
		'needregtime'=>$needregtime ,
		'uid' => $POST['uid']?$POST['uid']:0
		); 


	// ����ͼƬ
	$titlepic = ''; 
	// ��ȡ�ϴ���ͼƬ
	$uploads = array();
	if (!empty($POST['picids'])) {
		$picids = array_keys($POST['picids']);
		$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('pic') . " WHERE picid IN (" . simplode($picids) . ") AND uid='$_SGLOBAL[supe_uid]'");
		while ($value = $_SGLOBAL['db'] -> fetch_array($query)) {
			if (empty($titlepic) && $value['thumb']) {
				$titlepic = $value['filepath'] . '.thumb.jpg';
				$blogarr['picflag'] = $value['remote']?2:1;
			} 
			$uploads[$POST['picids'][$value['picid']]] = $value;
		} 
		if (empty($titlepic) && $value) {
			$titlepic = $value['filepath'];
			$blogarr['picflag'] = $value['remote']?2:1;
		} 
	} 
	// ��������
	if ($uploads) {
		preg_match_all("/\<img\s.*?\_uchome\_localimg\_([0-9]+).+?src\=\"(.+?)\"/i", $message, $mathes);
		if (!empty($mathes[1])) {
			$searchs = $idsearchs = array();
			$replaces = array();
			foreach ($mathes[1] as $key => $value) {
				if (!empty($mathes[2][$key]) && !empty($uploads[$value])) {
					$searchs[] = $mathes[2][$key];
					$idsearchs[] = "_uchome_localimg_$value";
					$replaces[] = mkpicurl($uploads[$value], 0);
					unset($uploads[$value]);
				} 
			} 
			if ($searchs) {
				$message = str_replace($searchs, $replaces, $message);
				$message = str_replace($idsearchs, 'uchomelocalimg[]', $message);
			} 
		} 
		// δ��������
		foreach ($uploads as $value) {
			$picurl = mkpicurl($value, 0);
			$message .= "<div class=\"uchome-message-pic\"><img src=\"$picurl\"><p>$value[title]</p></div>";
		} 
	} 
	// ��������
	if (empty($message) || empty($POST['subject']) || empty($POST['classid']) || empty($POST['price']) || empty($POST['num'])) {
		return false; //û���κ�����
	} 
	// ���slashes
	$message = addslashes($message);
	$blogarr['body'] = $message;
	$blogarr['pic'] = $picurl; 
	// �������ж�ȡͼƬ
	if (empty($titlepic)) {
		$titlepic = getmessagepic($message);
		$blogarr['picflag'] = 0;
		$blogarr['pic'] = $titlepic;
	} 

	if ($olds['id']) {
		// ����
		$id = $olds['id'];
		updatetable('exchange_gifts', $blogarr, array('id' => $id));
		Return $id;
	} else {

		$blogarr['dateline'] = empty($POST['dateline'])?$_SGLOBAL['timestamp']:$POST['dateline'];
		$blogid = inserttable('exchange_gifts', $blogarr, 1); 
		
		// ����Զ���������Ϸ���б�
		$keys = trim($POST['keys']);
		$list = explode("\n", $keys);

		if ($list && $blogid) {
			$count = 0;
			for($i = 0;$i < count($list);$i++) {
				if (strlen($list[$i]) > 0) {
					$keyid = inserttable('exchange_keys', array('gifts_id' => $blogid, 'keys' => $list[$i], 'keyuid' => 0, 'keyac' => 1), 1);
					$count++;
				} 
			} 

			// ���¿����
			if ($POST['autokey']==1 && $blogid) {			
			updatetable('exchange_gifts', array('num' => $count), array('id' => $blogid));
			}

		} 
		// �¼��ƶ�
		$feedmessage = "<B>[<a href=".$_SC['siteurl']."exchange.php>" . $SConfig["APP_TITLE"] . "</a>]</B> <a href=".$_SC['siteurl'] ."space.php?uid=" . $space[uid] . " target=_black>" . $space[username] . "</a> ������һ���ҽ���Ʒ��<a href=exchange.php?op=detailcontent&id=" . $blogid . ">" . $POST['subject'] . "</a>��";
		feed_add('exchange', $feedmessage);

   	return $blogid;
	} 
	

} 
// ��ȡ��ƷͼƬ
function getmessagepic($message) {
	$pic = '';
	$message = stripslashes($message);
	$message = preg_replace("/\<img src=\".*?image\/face\/(.+?).gif\".*?\>\s*/is", '', $message); //�Ƴ������
	preg_match("/src\=[\"\']*([^\>\s]{25,105})\.(jpg|gif|png)/i", $message, $mathes);
	if (!empty($mathes[1]) || !empty($mathes[2])) {
		$pic = "{$mathes[1]}.{$mathes[2]}";
	} 
	return addslashes($pic);
} 
// ����html
function checkhtml($html) {
	$html = stripslashes($html);
	if (!checkperm('allowhtml')) {
		$html = preg_replace("/\<script.*?\>.*?\<\/script\>/is", '', $html); //ȥ��script
		preg_match_all("/\<([^\<]+)\>/is", $html, $ms);
		$searchs = $replaces = array();
		if ($ms[1]) {
			$allowtags = 'img|a|font|div|table|tbody|caption|tr|td|br|p|b|strong|i|u|em|span|ol|ul|li|blockquote|object|param|embed'; //����ı�ǩ
			$ms[1] = array_unique($ms[1]);
			foreach ($ms[1] as $value) {
				$value = shtmlspecialchars($value);
				$searchs[] = "&lt;" . $value . "&gt;";
				$value = str_replace(array('\\', '/*'), array('.', '/.'), $value);
				$value = preg_replace(array("/(javascript|script|eval|behaviour|expression)/i", "/(\s+|&quot;|')on/i"), array('.', ' .'), $value);
				if (!preg_match("/^[\/|\s]?($allowtags)(\s+|$)/is", $value)) {
					$value = '';
				} 
				$replaces[] = empty($value)?'':"<" . str_replace('&quot;', '"', $value) . ">";
			} 
		} 
		$html = shtmlspecialchars($html);
		if ($searchs) {
			$html = str_replace($searchs, $replaces, $html);
		} 
		$html = preg_replace("/\&amp\;lt\;(.*?)\&amp\;gt\;/is", '&lt;\\1&gt;', $html); //�ָ�<>��ʾ
	} 
	// $html = preg_replace("/\<([^\>]*?)width([=|:].*?(\s|\>|\'|\"|;))/is", '<\\1!width\\2', $html);
	$html = addslashes($html);

	return $html;
} 
// ����Ƿ��Ѿ�������Զ����ŵĿ�
function check_buyer($POST) {
	global $_SGLOBAL, $_SC, $SConfig;
	$id = $POST['id']?$POST['id']:"0"; 
	// ����Ƿ��Ѿ���ȡ����
	$is_have = $_SGLOBAL['db'] -> result($_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_keys') . " WHERE gifts_id=" . $id . " AND keyuid=" . $_SGLOBAL['supe_uid']));

	Return $is_have;
} 
// ������Ʒ
function buy_gif($POST) {
	global $_SGLOBAL, $_SC, $SConfig; 

   //ȷ����¼
	checkislogin();

	// ȷ����Ʒ����
	$id = intval($POST['id'])?intval($POST['id']):0;
	$uid = intval($POST['uid'])?intval($POST['uid']):0;
	$autokey = trim($POST['autokey']);
	$num = intval($POST['amount']); 
	$price=intval($POST['price']); 
    $buynum=intval($POST['buynum']); 
	//�������
	if(!eregi('^[0-9]+$',$num)||($num<1))   showmessage("��������ȷ����");

	//��ȡ��Ʒ��Ϣ���۸�ȣ���ֹpost���ף�
    $sql="SELECT * FROM " . tname('exchange_gifts') . " WHERE id=" . $id . " AND ac='1' LIMIT 1" ;
	$query = $_SGLOBAL['db']->query($sql);
	$gifs= $_SGLOBAL['db']->fetch_array($query);
	if($gifs){
    $price=intval($gifs['price']); 
	$buynum=intval($gifs['buynum']); 
	$autokey=intval($gifs['autokey']); 
    $robnumber=intval($gifs['robnumber']); 

	if(!checkregtime($gifs['needregtime'])) showmessage('�ҽ����ޣ�����Ʒ����Ա�趨�˻�Աע��ʱ����� '.$gifs['needregtime'].' ���Ӻ󷽿ɶҽ���');

     if(!checkrobnumber($robnumber,$id)) showmessage('�ҽ����ޣ�����һ�������������������������ҡ�<br><br>ÿ���޶�: '.$robnumber.' �Ρ�');

	}else{
	showmessage("�˽�Ʒ�Ѿ��رգ���͹�����ϵ");
	}

	// �Զ�����
	if ($autokey) {

		//������ȡ��
		if($buynum!=0){		

		//��������
		// ����Ƿ��Ѿ���ȡ����
		$sql="SELECT COUNT(*) FROM " . tname('exchange_keys') . " WHERE gifts_id=" . $id . " AND keyuid=" . $_SGLOBAL['supe_uid'];
		$is_have = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($sql), 0);
	
		if ($is_have>=$buynum) showmessage("���Ѿ��һ�������Ʒ ".$is_have."�����뵽�ռ���鿴��");
		}

		$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_keys') . " WHERE gifts_id=" . $id . " AND keyac=1 AND keyuid=0  LIMIT ".$num); 
        
		$keylist=array();
		$total_key=0;

         while ($value = $_SGLOBAL['db'] -> fetch_array($query)) {
				 $keylist[]=$value;
				 $total_key++;
		 }

		if ($total_key>=$num) {		

			//���ͻ�����
			$total = $num * $price; //	
            if ($_SGLOBAL['my']['credits'] < $total) showmessage("���Ļ��ֲ��㣬�뷵�ء�"); 

			// �۳����
			$_SGLOBAL['db'] -> query("UPDATE " . tname('space') . " SET credit=credit-$total WHERE uid=" . $_SGLOBAL['supe_uid']);

			//���ӽ��
			if ($uid) {
				$_SGLOBAL['db'] -> query("UPDATE " . tname('space') . " SET credit=credit+$total WHERE uid=" . $uid);
			}

			$keycount=0;
			$keymessage="";

			foreach($keylist as  $key=>$val){
			
			$keycount++;
			//���붩��
			$setarr = array('uid' => $_SGLOBAL['supe_uid'],
				'gid' => $id,
				'keyid' =>  $val['keyid'],
				'amount' => $_POST['amount'],
				'status' => 3,
				'data' => $_SGLOBAL['timestamp']
				);

			$adid = inserttable('exchange_orderform', $setarr, 1); 

			// ������� ac=0 ��Ч ac=1 ���� ac=2 ����
			updatetable('exchange_keys', array('keyuid' => $_SGLOBAL['supe_uid'], 'keyac' => 2, 'dateline' => $_SGLOBAL['timestamp']), array('keyid' => $val['keyid'])); 				
               
			$temStr=str_replace('��',':',trim($val['keys'])); 
			$temStr = explode(":", $temStr); 
            
			if($total_key>1){
				$keymessage .="#  �� ".$keycount." �� <br>";
			}else{
				$keymessage .="<br>";
			}

			if ($temStr[1]) {
			$keymessage .= "����      �š�" . $temStr[0] . "<br>";
			$keymessage .= "����      �롻" . $temStr[1]. "<br>";
			} else {
			$keymessage .= "����      Կ��" . trim($val['keys'])."<br>";
			} 
			$keymessage .="--------------------------------------------------------------------------<br>";
            
			}//foreach($value as  $val)

			// ��վ��֪ͨ
			$subbject = "���һ���ȡ�ġ�" . $POST['title'] . "���ʺ���Ϣ(ϵͳ�Զ�����)";
			$message = "";
			$message .=  "<br>";
			$message .= "��л��ʹ�����ǵ�" . $SConfig["APP_TITLE"] . "�Զ����ŷ���,��������һ�����ϸ��Ϣ��<br><br> ";

			$message .= "========================================<br>";
			$message .= "��Ʒ      ����" . $POST['title'] . "<br>";
			$message .= "========================================<br>";
			$message .= "<br>";			
			$message .=$keymessage;
			$message .=  "<br>";

			if(strlen($POST['url'])>6) 	{
			 $message .="���ٷ���վ��".$POST['url'];	
			}

			$message .= "<br>";
			$message .= "========================================<br>";
			$message .= "�ʵ���ϸ " . $num . " �� (����" . $price . ") ������ " . $total . " ����<br> ";
			$message .= "========================================<br>";
			$message .= "<br><br>";
			$message .= "ע����������м���ð�Ż��߷ֺţ����ʾ��Щ����ǰ�����ʺţ����������롣û����ֱ�����뼴�ɡ�<br>";
			$message .= "Ϊ��ȷ����Դ��Ч�ԣ� ��������ȡ��48Сʱ��ʹ�á� ������ּ�����������Ϣ���뵽�ٷ��ύ��лл����";
			$message .= "<br>";
			$message .= "����������Ϣ�����Ʊ��ܣ���ʧ���ɲ���������������PM����Ա��";
			$message .= "<br><br>";
			$message .= "�ٴ�лл��";

			include_once S_ROOT . './uc_client/client.php';
			uc_pm_send($uid?$uid:$SConfig ['ADMINUID'], $_SGLOBAL['supe_uid'], $subbject , str_replace("<br>", "\n", $message), 1, 0, 0);		
			
			//����Ϣ�͸�������
			if ($uid) {
				$subbject_uid = "������Ʒ��" . $POST['title'] . "���ѱ��һ���";
				$message_uid  = "���ڡ��һ����ġ�����Ʒ��" . $POST['title'] . "���ѳɹ��� " . $_SGLOBAL['my']['username'] . " �һ���<br>";
				$message_uid .= "�ʵ���ϸ " . $num . " �� (����" . $price . ") ����� " . $total . " ����";
				uc_pm_send($_SGLOBAL['supe_uid'], $uid, $subbject_uid , str_replace("<br>", "\n", $message_uid), 1, 0, 0);	
			}
			
			if($SConfig["EMAIL_MOD"] && $_SGLOBAL['my']['email'] && $_SGLOBAL['my']['emailcheck']  ){
			//��վ���ʼ�֪ͨ
			$mail_subject=$subbject;
			$mail_message=$temStr=$message; 
			include_once(S_ROOT.'./source/function_cp.php');
	        smail(0, $_SGLOBAL['my']['email'], $mail_subject, $mail_message);
	        $mail_syssubject="��վ�л�Ա�һ���Ʒ";
	        smail(0, $SConfig[emailinfo], $mail_syssubject, $mail_message);			
			}

			// ���¿����
			$_SGLOBAL['db'] -> query("UPDATE " . tname('exchange_gifts') . " SET num=num-$num,sale=sale+$num WHERE id=$id");

			// �¼�����
			$msg = "�һ���Ʒ <a href=".$_SC['siteurl'] ."exchange.php?op=detailcontent&id=".$gifs['id']."><b>" . $POST['title'] . "</b></a> " . $num . " �� (����" . $price . ") �ϼ����� " . $total . " ���֡� ";
			feed_add('exchange', "<a href=".$_SC['siteurl'] ."space.php?uid=" . $_SGLOBAL['supe_uid'] . " target=_blank>" . $_SGLOBAL['my']['username'] . "</a>: " . $msg);

			Return $keycount;
		} else {

			//֪ͨ����Ա����治��
			$subbject="����治�㡫";
			$message ="";	
			$message .="�����Ĺ���Ա����".$POST['title']." ����治�㣬�鷳�벹����\n";
			$message .="\n\n";
			$message .="���û�����PM�ҡ�лл�����������ˣ�";
			include_once S_ROOT . './uc_client/client.php';
			uc_pm_send( $_SGLOBAL['supe_uid'],$SConfig ['ADMINUID'], $subbject , $message, 1, 0, 0);

			showmessage("���ſ�治�㣬ϵͳ�Ѿ�֪ͨ����Ա����л֧�֣�");
		} 
	} else {

		//������ȡ��
		if($buynum!=0){
		//��������
		// ����Ƿ��Ѿ���ȡ����
		$is_have = $_SGLOBAL['db'] -> result($_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_orderform') . " WHERE gid=" . $id . " AND uid=" . $_SGLOBAL['supe_uid']));
   
		if ($is_have>=$buynum) showmessage("���Ѿ��һ�������Ʒ���뵽�ҵĶ����鿴��");
		}

		// �ֹ�����
		$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_gifts') . " WHERE id=" . $id . " AND ac=1");
		$value = $_SGLOBAL['db'] -> fetch_array($query) ;

		if ($value['num'] < 1 || $value['num'] < $num) {

			//֪ͨ����Ա����治��
			$subbject="����治�㡫";
			$message ="";	
			$message .="�����Ĺ���Ա����".$value['title']." ����治�㣬�鷳�벹����\n";
			$message .="\n\n";
			$message .="���û�����PM�ҡ�лл�����������ˣ�";
			include_once S_ROOT . './uc_client/client.php';
			uc_pm_send( $_SGLOBAL['supe_uid'],$SConfig ['ADMINUID'], $subbject , $message, 1, 0, 0);

			showmessage("��治�㣬ϵͳ�Ѿ�֪ͨ����Ա����л֧�֣�");
		}

		if ($value && $value['num'] >= $num) {
			$total = $num * $value['price']; //	
			
			if ($_SGLOBAL['my']['credits'] < $total) showmessage("���Ļ��ֲ��㣬�뷵�ء�"); 
			// �۳����
			$_SGLOBAL['db'] -> query("UPDATE " . tname('space') . " SET credit=credit-$total WHERE uid=" . $_SGLOBAL['supe_uid']);

			$setarr = array('uid' => $_SGLOBAL['supe_uid'],
				'gid' => $id,
				'amount' => $_POST['amount'],
				'status' => 1,
				'data' => $_SGLOBAL['timestamp']
				);

			$adid = inserttable('exchange_orderform', $setarr, 1); 
			// ������Ʒ������������
			$_SGLOBAL['db'] -> query("UPDATE " . tname('exchange_gifts') . " SET num=num-$num,sale=sale+$num WHERE id=$id"); 
			// �¼�����
			$msg = "�һ���Ʒ<a href=".$_SC['siteurl'] ."exchange.php?op=detailcontent&id=".$value['id']."><b>" . $value['title'] . "</b></a> " . $_POST['amount'] . " �� (����" . $value['price'] . ") �ϼ����� " . $total . " ���֡� ";
            feed_add('exchange', "<a href=".$_SC['siteurl'] ."space.php?uid=" . $_SGLOBAL['supe_uid'] . " target=_blank>" . $_SGLOBAL['my']['username'] . "</a>: " . $msg);
			
			$pmmsg = "�һ���Ʒ" . $value['title'] . " " . $_POST['amount'] . " �� (����" . $value['price'] . ") �ϼ����� " . $total . " ���� ";

			//���Ӹ����ջ���Ϣ
            $info=getinfo($_SGLOBAL['supe_uid']);
		   //������Ϣ
		    $subbject="���Ķ����Ѿ��ύ���ֹ���������";
			$message="����������������: \n".$pmmsg."\n\n ��ܰ��ʾ��\n 1.���ٴμ�������ջ���ַ�Ƿ���ȷ�����ⵢ�����ջ����ڡ��ջ���Ϣ����������д��\n 2.���ɵ��ҵĶ������ٶ���������̡�";
			$message.=str_replace("<br>", "\n", $info);
			$mailbody="����������������: <br>".$msg."<br><br> ��ܰ��ʾ��<br> 1.���ٴμ�������ջ���ַ�Ƿ���ȷ�����ⵢ�����ջ����ڡ��ջ���Ϣ����������д��<br> 2.���ɵ��ҵĶ������ٶ���������̡�";
			$mailbody.=$info;
			include_once S_ROOT . './uc_client/client.php';
			uc_pm_send( $SConfig['ADMINUID'],$_SGLOBAL['supe_uid'], $subbject , $message, 1, 0, 0);

            //if($SConfig["EMAIL_MOD"] && $_SGLOBAL['my']['email'] && $_SGLOBAL['my']['emailcheck']  ){
			if($SConfig["EMAIL_MOD"] && $_SGLOBAL['my']['email'] ){
			//��վ���ʼ�֪ͨ
			$mail_subject="���Ļ��ֶһ���Ʒ��������";
			$mail_message=$mailbody;
			include_once(S_ROOT.'./source/function_cp.php');
	        smail(0, $_SGLOBAL['my']['email'], $mail_subject, $mail_message);
	        $mail_syssubject="��վ�л�Ա�һ���Ʒ";
	        smail(0, $SConfig[emailinfo], $mail_syssubject, $mail_message);			
			}			

			Return $adid;
		} 
	} //end else autokey
} 
// ɾ����������Ա��������
function del_orderform($ids) {
	global $_SGLOBAL, $_SC, $SConfig;

	   //ȷ����¼
	checkislogin();

	foreach ($_POST['ids'] as $id => $value) {
		$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_gifts') . " a LEFT JOIN " . tname('exchange_orderform') . " b ON(a.id=b.gid)  WHERE b.uid=" . $_SGLOBAL['supe_uid'] . " AND b.status=1 AND b.id=" . $id);

		while ($value = $_SGLOBAL['db'] -> fetch_array($query)) {
			if ($value) {
				$num = $value['amount'];

				$coin = $value['amount'] * $value['price'];

				$gid = $value['gid'];

				$_SGLOBAL['db'] -> query("DELETE FROM " . tname('exchange_orderform') . " WHERE id=" . $id . " AND (status=1 OR status=4)"); 
				// �����־
				$msg = "������� id=" . $id . " status=1 OR status=4 <br> SQL: " . "DELETE FROM " . tname('exchange_orderform') . " WHERE id=" . $id . " AND (status=1 OR status=4)";

				if ($value['status'] == 1) {
					$_SGLOBAL['db'] -> query("UPDATE " . tname('space') . " SET credit=credit+" . $coin . " WHERE uid=" . $_SGLOBAL['supe_uid']);
				} 
				// ������Ʒ������������
				$_SGLOBAL['db'] -> query("UPDATE " . tname('exchange_gifts') . " SET num=num+$num,sale=sale-$num WHERE id=" . $gid);
			} 
		} 
	} 
	Return $ids;
} 
// ɾ����Ʒ
function del_gif($ids) {
	global $_SGLOBAL, $_SC, $SConfig;

	$_SGLOBAL['db'] -> query("DELETE FROM " . tname('exchange_gifts') . " WHERE id IN (" . simplode($ids) . ")");

	Return $ids;
} 
// ������Ʒ
function up_gif($ids) {
	global $_SGLOBAL;
	foreach ($ids as $id => $value) {
		$setstr = "";
		if (!empty($_POST['rank'][$id])) {
			$setstr .= "rank = '" . $_POST['rank'][$id] . "', ";
		} else {
			$setstr .= "rank = '0', ";
		} 
		if (!empty($_POST['price'][$id])) {
			$setstr .= "price = '" . $_POST['price'][$id] . "', ";
		} else {
			$setstr .= "price = '0', ";
		} 
			// ������Զ��������Զ����¿����Ŀ
	if ($_POST['autokey'][$id]==1) {
		$_POST['num'][$id] = $_SGLOBAL['db'] -> result($_SGLOBAL['db'] -> query("SELECT COUNT(*) FROM " . tname('exchange_keys') . " WHERE gifts_id=" . $id));
	} 

		if (!empty($_POST['num'][$id])) {
			$setstr .= "num = '" . $_POST['num'][$id] . "', ";
		} else {
			$setstr .= "num = '0', ";
		} 
		if (!empty($_POST['sale'][$id])) {
			$setstr .= "sale = '" . $_POST['sale'][$id] . "', ";
		} else {
			$setstr .= "sale = '0', ";
		} 
		if (!empty($_POST['ac'][$id])) {
			$setstr .= "ac = '" . $_POST['ac'][$id] . "', ";
		} else {
			$setstr .= "ac = '0', ";
		} 

		$setstr = substr($setstr, 0, -2);
		$query = "UPDATE " . tname('exchange_gifts') . " SET $setstr WHERE id=" . $id;

		$_SGLOBAL['db'] -> query($query);
	} 
	Return $ids;
} 
// ��������ʺ�	//����Զ���������Ϸ���б�
function add_keys($POST) {
	global $_SGLOBAL;
	$key = trim($POST['keys']);
	$list = explode("\n", $key);

	if ($list && $POST['id']) {
		for($i = 0;$i < count($list) + 1;$i++) {
			if (strlen(trim($list[$i])) > 0) $keyid = inserttable('exchange_keys', array('gifts_id' => $POST['id'], 'keys' => trim($list[$i]), 'keyuid' => 0, 'keyac' => 1), 1);
		} 
		if(count($list)){
		$count = $_SGLOBAL['db'] -> result($_SGLOBAL['db'] -> query("SELECT COUNT(*) FROM " . tname('exchange_keys') . " WHERE gifts_id=" . $POST['id'] ." AND keyuid='0' AND keyac='1'" )); 		
		// ���¿������������Ϊ�Զ�����
		updatetable('exchange_gifts', array('num' => $count,'autokey'=>1), array('id' => $POST['id']));
		}
	} 
	Return $keyid;
} 
// ɾ���ʺ��б�
function del_keys($ids) {
	global $_SGLOBAL, $_SC, $SConfig;

	$_SGLOBAL['db'] -> query("DELETE FROM " . tname('exchange_keys') . " WHERE keyid IN (" . simplode($ids) . ")");

foreach ($_POST['ids'] as $id => $value) {
	if (!empty($_POST['gifts_id'][$id])) {
			$lim = " AND gifts_id='" . $_POST['gifts_id'][$id]."'" ;
			$uplim=$_POST['gifts_id'][$id];
		} else {
			$lim = "";
	} 

	// ���¿������������Ϊ�Զ�����
	 $count = $_SGLOBAL['db'] -> result($_SGLOBAL['db'] -> query("SELECT COUNT(*) FROM " . tname('exchange_keys') . " WHERE keyuid='0'".$lim)); 		
	  if($uplim) updatetable('exchange_gifts', array('num' => $count,'autokey'=>1), array('id' => $uplim));
}
	Return $ids;
} 
// �����ʺ��б�
function up_keys($ids) {
	global $_SGLOBAL;

	foreach ($_POST['ids'] as $id => $value) {
		$setstr = "";
		if (!empty($_POST['keys'][$id])) {
			$setstr .= "`keys` = '" . trim($_POST['keys'][$id]) . "', ";
		} else {
			$setstr .= "`keys` = '0', ";
		} 
		/*
		if (!empty($_POST['keyuid'][$id])) {
			$setstr .= "`keyuid` = '" . $_POST['keyuid'][$id] . "', ";
		} else {
			$setstr .= "`keyuid` =0, ";
		} 
		*/
		if (!empty($_POST['ac'][$id])) {
			$setstr .= "`keyac` = '" . $_POST['ac'][$id] . "', ";
		} else {
			$setstr .= "`keyac` = 0, ";
		} 
		if (!empty($_POST['gifts_id'][$id])) {
			$lim = " AND gifts_id='" . $_POST['gifts_id'][$id]."'" ;
			$uplim=$_POST['gifts_id'][$id];
		} else {
			$lim = "";
		} 

		$setstr = substr($setstr, 0, -2);
		$query = "UPDATE `" . tname('exchange_keys') . "` SET " . $setstr . " WHERE `keyid`='" . $id . "' AND keyuid='0'";
		$_SGLOBAL['db'] -> query($query);

		// ���¿������������Ϊ�Զ�����
	    $count = $_SGLOBAL['db'] -> result($_SGLOBAL['db'] -> query("SELECT COUNT(*) FROM " . tname('exchange_keys') . " WHERE keyuid='0'".$lim)); 		
	    if($uplim) updatetable('exchange_gifts', array('num' => $count,'autokey'=>1), array('id' => $uplim));
	} 



	Return $ids;
} 
// ���¸�����Ϣ
function up_myinfo($olds) {
	global $_SGLOBAL, $_SC, $SConfig; 

	//ȷ����¼
	checkislogin();

	// ����
	$arr = array('uid' => $_SGLOBAL['supe_uid'],
		'name' => $_POST['name'],
		'address' => $_POST['address'],
		'zipcode' => $_POST['zipcode'],
		'phone' => $_POST['phone'],
		'mobile' => $_POST['mobile'],
		'city' => $_POST['city'],
		'state' => $_POST['state'],
		'country' => $_POST['country'],
		'qq' => $_POST['qq'],
		'msn' => $_POST['msn'],
		'skype' => $_POST['skype'],
		'email' => $_POST['email']
		);

	if ($olds) {
		// ����
		$uid = $olds['uid'];
		updatetable('exchange_user', $arr, array('uid' => $uid));
	} else {
		$uid = inserttable('exchange_user', $arr, 1);
	} 
	Return $uid;
} 
// ��������
function saveConfig($confItem, $newVal) {
	global $_SC, $SConfig; 
	// �����ļ��Ƿ��д
	if (is_writable("exchange/config.php")) {
		$newLine = "\$SConfig[\"" . strtoupper($confItem) . "\"] = ";

		if (is_numeric($newVal)) $newLine .= $newVal;
		elseif (is_bool($newVal)) $newLine .= ($newVal?"true":"false");
		else $newLine .= "\"" . str_replace("\"", "\\\"", stripslashes($newVal)) . "\"";

		$newLine .= ";";

		$configBody = file_get_contents("exchange/config.php");

		$configBody = preg_replace('/\\$SConfig\\["' . preg_quote(strtoupper($confItem)) . '"\\].*/',
			$newLine,
			$configBody
			);

		ignore_user_abort(true);
		if ($handle = fopen("exchange/config.php", "w")) {
			flock($handle, LOCK_EX);
			fwrite($handle, $configBody);
			flock($handle, LOCK_UN);
			fclose($handle);
			ignore_user_abort(false);

			$SConfig[$confItem] = $newVal;

			return true;
		} 
	} else return false;
} 

function saveCONF($POST) {
	$saveList = array("APP_OFF",
		"TIME_LIMIT",
		"VIEW_MOD",
		"EMAIL_MOD",
		"OPENDATE",
		"OPENTIME",
		"CLOSETIME",
		"PERPAGE",
		"PERPAGE_ODER",
		"PERPAGE_ADMIN",
		"PERPAGE_KEYS",
		"PERPAGE_ORDERFORM",
		"TITLE_LIMIT",
		"BODY_LIMIT",
		"APP_TITLE",
		"APP_GROUPID",
		"HELP_BODY",
		);

	$i = 0;

	foreach ($saveList as $saveEntry) {
		if (isset($POST[$saveEntry])) {
			if ($saveEntry == "HELP_BODY") {
				$POST['message'] = $POST[$saveEntry];
				$POST['message'] = checkhtml($POST['message']);
				$POST['message'] = getstr($POST['message'], 0, 1, 0, 1, 0, 1);
				$POST['message'] = preg_replace("/\<div\>\<\/div\>/i", '', $POST['message']);

				$message = $POST['message'];
				$POST[$saveEntry] = base64_encode($message);
			} 
          if ($saveEntry == "OPENDATE") {
			  $POST[$saveEntry]=implode(",",$POST[$saveEntry]);
		  }

			saveConfig($saveEntry, $POST[$saveEntry]);
		} else saveConfig($saveEntry, 0);
		$i++;
	} 
	Return $i;
} 
// ���¶���״̬����������
function up_orderform($POST) {
	global $_SGLOBAL, $_SC, $SConfig;

	if ($POST['status'] <= 1) {
		Return;
	} 

	$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_gifts') . " a LEFT JOIN " . tname('exchange_orderform') . " b ON(a.id=b.gid)  WHERE b.uid>0  AND b.id=" . $POST['id']);

	while ($value = $_SGLOBAL['db'] -> fetch_array($query)) {
		if ($value) {
			$coin = $value['amount'] * $value['price'];

			if ($value['status'] != 4 && $POST['status'] == 4) {
				$_SGLOBAL['db'] -> query("UPDATE " . tname('space') . " SET credit=credit+" . $coin . " WHERE uid=" . $value['uid']);
			} 

			updatetable('exchange_orderform', array('status' => $POST['status']), array('id' => $POST['id']));

			//��վ�ڶ���
			$subbject="������״̬����֪ͨ��";
			$message ="";	
			$message .="���Ķ���״̬�и��¡�\n";
			$message .="\n\n";
			$message .="�뵽�ҵĶ������в��ģ�";
			include_once S_ROOT . './uc_client/client.php';
			uc_pm_send( $SConfig ['ADMINUID'],$value['uid'], $subbject , $message, 1, 0, 0);

        	//��վ���ʼ�֪ͨ
            $tomail=getemailbyuid($value['uid']);
			if($SConfig["EMAIL_MOD"] && $tomail  ){		
			$mail_subject="���Ļ��ֶһ���Ʒ�������²���";
			$mail_message="���½��վ�Ķ�������鿴����״̬��";
			include_once(S_ROOT.'./source/function_cp.php');
			
	        if($tomail) smail(0, $tomail , $mail_subject, $mail_message);			
			}

		} 
	} 
	Return $POST['id'];
} 
// ɾ������������Ա��������
function admin_del_orderform($ids) {
	global $_SGLOBAL,$SConfig;
	foreach ($_POST['ids'] as $id => $value) {

		//ȷ����Ʒ����
		$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_gifts') . " a LEFT JOIN " . tname('exchange_orderform') . " b ON(a.id=b.gid)  WHERE b.uid>0 AND (b.status=1 OR b.status=4) AND b.id=" . $id);

		while ($value = $_SGLOBAL['db'] -> fetch_array($query)) {
			if ($value) {
				$num = $value['amount'] ;
				$gid = $value['gid'] ;
				$coin = $value['amount'] * $value['price'];
				$autokey=$value['autokey'];
				
				$_SGLOBAL['db'] -> query("DELETE FROM " . tname('exchange_orderform') . " WHERE id=" . $id . " AND (status=1 OR status=4)"); 

				// ������Ʒ������������
				$_SGLOBAL['db'] -> query("UPDATE " . tname('exchange_gifts') . " SET num=num+$num,sale=sale-$num WHERE id=$gid");
				
				//ֻ�������л���
				if ($value['status' == 1]) {
					$_SGLOBAL['db'] -> query("UPDATE " . tname('space') . " SET credit=credit+" . $coin . " WHERE uid=" . $value['uid']);
				} 

			//��վ�ڶ���
			$subbject="������״̬����֪ͨ��";
			$message ="";	
			$message .="���Ķ���״̬�и��¡�\n";
			$message .="\n\n";
			$message .="�뵽�ҵĶ������в��ģ�";
			include_once S_ROOT . './uc_client/client.php';
			uc_pm_send( $SConfig ['ADMINUID'],$value['uid'], $subbject , $message, 1, 0, 0);

        	//��վ���ʼ�֪ͨ
            $tomail=getemailbyuid($value['uid']);
			if($SConfig["EMAIL_MOD"] && $tomail  ){		
			$mail_subject="���Ļ��ֶһ���Ʒ�������²���";
			$mail_message="���½��վ��������鿴����״̬��";
			include_once(S_ROOT.'./source/function_cp.php');
			
	        if($tomail) smail(0, $tomail , $mail_subject, $mail_message);			
			}

			} 
		} 
	} 
	Return $ids;
} 

// ��������Ϣ�Ƿ�����
function checkinfo($uid){
		global $_SGLOBAL;
	if($uid<1) return 0;
		
	$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_user') . " where uid='" . $uid . "'");

	$row = $_SGLOBAL['db'] -> fetch_array($query) ;

	if (empty($row['name']) || empty($row['address']) || empty($row['zipcode']) || empty($row['qq']) || empty($row['mobile']) || empty($row['city']) || empty($row['state']) || empty($row['country'])) {
		return 0;
	} else {
		return 1;
	} 
}

//��ȡ������Ϣ
function getinfo($uid){
		global $_SGLOBAL;
	if($uid<1) return ;
		
	$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_user') . " where uid='" . $uid . "'");

 $row = $_SGLOBAL['db'] -> fetch_array($query) ;
 $info= "<br>�ջ���Ϣ:<br>";
 $info.="�ջ�������:".$row['name']."<br>";
 $info.="�ջ���ַ:".$row['address']." (".$row['state'].$row['city']." ".$row['country'].")<br>";
 $info.="�ʱ�:".$row['zipcode']."<br>";
 $info.="��ϵ�ֻ�:".$row['mobile']."<br>";
 $info.="��ϵ�ѣ�:".$row['qq']."<br>";
 $info.="---------------------------------------------------------<br>";
  $info.="������Ϣ�粻�ԣ��뼰ʱ����վ����Ա��ϵ��<br>";

 return $info;
}

//ͨ��uid��ȡ�ʼ���ַ
function getemailbyuid($uid){
	global $_SGLOBAL;
	if($uid<1) return;
	$query = $_SGLOBAL['db']->query('SELECT email FROM '.tname('spacefield')."  WHERE uid='".$uid."'");
	$row = $_SGLOBAL['db']->fetch_array($query);
    return $row['email'];
}

// ҳ�����ת��
function utf2gbk($str) {
	if (function_exists('mb_convert_encoding')) {
		return mb_convert_encoding($str , 'GBK' , 'UTF-8');
	} 
	if (function_exists('iconv')) {
		return iconv('UTF-8' , 'GBK' , $str);
	} 
} 

function gbk2utf($str) {
	if (function_exists('mb_convert_encoding')) {
		return mb_convert_encoding($str , 'UTF-8', 'GBK');
	} 
	if (function_exists('iconv')) {
		return iconv('GBK' , 'UTF-8' , $str);
	} 
} 

?>
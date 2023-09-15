<?php
include_once('./common.php');
include_once(S_ROOT . './source/function_cp.php');
include_once(S_ROOT . './exchange/config.php');
include_once(S_ROOT . './exchange/function.php');
if (!defined('IN_UCHOME')) {
	exit('Access Denied');
} 
// �����վ�Ƿ�ر�
checkclose();
// ��Ҫ��¼
if (empty($SConfig ['VIEW_MOD'])) checklogin();

// �ռ���Ϣ
$space = getspace($_SGLOBAL['supe_uid']);
//ʵ����֤
ckrealname('myexchange');
	//��Ҫ�ϴ�ͷ��
	if($_SCONFIG['need_avatar'] && empty($space['avatar'])) {
		if(empty($return)) showmessage('no_privilege_avatar');
		$result = false;
	}
// �����ǩ
$op = $_GET['op']? $_GET['op']:"exchange";
$ac = $_GET['ac']?intval($_GET['ac']):"0";
$id = $_GET['id']?intval($_GET['id']):"0";

$status = $_GET['status']?intval($_GET['status']):"0";

$page = empty($_GET['page'])?1:intval($_GET['page']);
if ($page < 1) $page = 1;
// ������
$dos = array('exchange', 'detailcontent', 'setup', 'help', 'card', 'orderform', 'buyinto', 'admin_config', 'admin_list', 'admin_keys', 'add_keys', 'admin_edit', 'admin_add', 'admin_orderform_list', 'admin_orderform');
$op = (!empty($_GET['op']) && in_array($_GET['op'], $dos))?$_GET['op']:'exchange';

if (!empty($op)) {
	$actives[$op] = "class=active";
} else {
	$actives['exchange'] = "class=active";
} 
// ��̨����ԱȨ��
if ($SConfig ['ADMINUID'] == $_SGLOBAL['supe_uid']) {
	$isAdmin = true;
} else {
	$isAdmin = false;
} 
// ������Ƿ�ر�
checkAPP($isAdmin);

$space['credits'] = $space['credit']; //�ҽ�ѧ�Ͷ�

$_SGLOBAL['my']['credits'] = $space['credits'];
$_SGLOBAL['my'] = $space; 
$_SGLOBAL['my']['isamdin'] =$isAdmin;

// ��ȡ���˷���
$eclassarr = geteclassarr(); 
// ��ȡ��Ʒ���ͷ���
$albums = getalbums($SConfig['ADMINUID']);
// �����Ա
if ($_SGLOBAL['supe_uid']) {
	include_once(S_ROOT . './source/function_cache.php');
	include_once(S_ROOT . './data/data_exchange.php');
	$time_limit = intval($SConfig['TIME_LIMIT'])?intval($SConfig['TIME_LIMIT']):600; // ����������ʱ�� (��)
	if (empty($_SGLOBAL['exchange'])) $_SGLOBAL['exchange'] = array();
	$_SGLOBAL['exchange'][$_SGLOBAL['supe_uid']]['uptime'] = $_SGLOBAL['timestamp']; //����ʱ��
	$_SGLOBAL['exchange'][$_SGLOBAL['supe_uid']]['username'] = $space['username']; //��Ա��
	$_SGLOBAL['exchange'][$_SGLOBAL['supe_uid']]['uid'] = $space['uid']; //id
	$newcache = $listuid = array();
	foreach ($_SGLOBAL['exchange'] as $uid => $uptime) {
		if (intval($_SGLOBAL['timestamp']) - intval($uptime['uptime']) < intval($time_limit)) {
			$newcache['exchange'][$uid]['uptime'] = $uptime['uptime'];
			$newcache['exchange'][$uid]['username'] = $uptime['username'];
			$newcache['exchange'][$uid]['uid'] = $uptime['uid'];
			$listuid[] = $uid;
		} 
	} 
	cache_write('exchange', "_SGLOBAL['exchange']", $newcache['exchange']);
} 
// ����op
/**
 * if ($op == "top10") {
 * //��ȡ���¶һ���Ʒ
 * $list=array();
 * $query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_gifts') . " ORDER BY sale DESC,rank ASC,dateline DESC LIMIT 10");
 * while ($value = $_SGLOBAL['db'] -> fetch_array($query)) {
 * if ($value) {			
 * $list[] = $value;
 * } 	
 * } 
 * 
 * }
 */


if ($op == "exchange" || empty($op)) {
	// ÿҳ��ʾ��¼��
	$perpage = intval($SConfig["PERPAGE"]) ; 
	// ��ҳ
	$start = ($page-1) * $perpage; 
	// ��鿪ʼ��
	ckstart($start, $perpage);

	$key = empty($_GET['key'])?'':$_GET['key'];
	$do = empty($_GET['search'])?"":$_GET['search'];

	$type = $_GET['type']?$_GET['type']:"all";

	if ($type == "all") {
		$wheresql = " WHERE ac=1 ";
		$mpurl = $theurl = "exchange.php?op=exchange";
	} else {
		$wheresql = " WHERE ac=1 AND type='" . $type . "' " ;
		$mpurl = $theurl = "exchange.php?op=exchange&type=" . $type;
	} 

	if($do){
	$wheresql.=" AND title LIKE '%".$key."%'";
	$mpurl = $theurl = "exchange.php?op=exchange&do=search";
	}

	$list = array();

	$count = $_SGLOBAL['db'] -> result($_SGLOBAL['db'] -> query("SELECT COUNT(*) FROM " . tname('exchange_gifts') . " $wheresql"), 0);

	$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_gifts') . "  $wheresql ORDER BY rank DESC,id DESC LIMIT $start, $perpage");

	while ($value = $_SGLOBAL['db'] -> fetch_array($query)) {
		if ($value) {
			$value['showprice'] = number_format($value['price']);
			$value['showtitle'] = getstr($value['title'], $SConfig["TITLE_LIMIT"] , 1, 1, 1);
			$value['showbody'] = getstr(strip_tags($value['body']), $SConfig["BODY_LIMIT"] , 1, 1, 1);
			$list[] = $value;
		} 
	} 
	// ��ҳ
	$multi = multi($count, $perpage, $page, $theurl); 

//��������Ϣ�Ƿ�����
$myinfo = checkinfo($_SGLOBAL['supe_uid']);

	if (empty($SConfig ['VIEW_MOD'])) {
		if (!$myinfo) showmessage(' ��������������ϸ���ϣ��ջ�����Ϣ�͵�ַ����', "exchange.php?op=setup", 3);
	} 
	if ($ac == "buy") {
		// ���Ϲ���
		if (submitcheck('buysubmit')) {
			if (!empty($_POST['id']) && buy_gif($_POST)) {
				if ($_POST['autokey']) {
					showmessage('��ϲ���һ��ɹ����뵽�ռ���鿴��ϸ�������Ʊ��档', $_POST['mpurl'], 3);
				} else {
					showmessage('���Ķ����ύ�ɹ���7�������������ǻᴦ����������<br><br> �����Ե�"�ҵĶ���"���й���Ͳ�ѯ���ȣ�', $_POST['mpurl'], 5);
				} 
			} else {
				showmessage(' ���������߽�Ʒ��治�㡣', $_POST['mpurl'], 3);
			} 
		} 
	} //end if($ac=="buy")
} 
// ��Ʒ��ϸ
if ($op == "detailcontent") {
	if (empty($id)) showmessage(' �޴˽�Ʒ��');

//��������Ϣ�Ƿ�����
$myinfo = checkinfo($_SGLOBAL['supe_uid']);

	$list = array();
	$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_gifts') . " WHERE id=" . $id);
	$row = $_SGLOBAL['db'] -> fetch_array($query);
	$row['showprice'] = number_format($row['price']);
	$row['begin'] = sgmdate('Y-m-d H:i:s', $row['begin']);
	$row['expiration'] = sgmdate('Y-m-d H:i:s', $row['expiration']);
} 
// =============================================================================
if ($op == "setup") {
	$list = array();
	$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_user') . " WHERE uid=" . $_SGLOBAL['supe_uid']);
	$row = $_SGLOBAL['db'] -> fetch_array($query);

	$mpurl = "exchange.php?op=setup";

	if ($ac == "updata") {
		if (submitcheck('setupsubmit')) {
			if (up_myinfo($row)) {
				showmessage('������ϵ��Ϣ���³ɹ���', $_POST['mpurl']);
			} else {
				showmessage(' ����д���������Ŀ��', $_POST['mpurl'], 3);
			} 
		} 
	} 
} 
if ($op == "help") {
	$SConfig[HELP_BODY] = base64_decode($SConfig[HELP_BODY]);
} 
// =============================================================================
if ($op == "orderform") {
	// ÿҳ��ʾ��¼��
	$perpage = intval($SConfig["PERPAGE_ODER"]); 
	// ��ҳ
	$start = ($page-1) * $perpage; 
	// ��鿪ʼ��
	ckstart($start, $perpage);

	$arr_status = array('1' => '������', '2' => '������', '3' => '�ѷ���', '4' => '���˿�');

	$status = $_GET['status'];

	if (empty($status)) {
		$where = " WHERE b.uid='" . $_SGLOBAL['supe_uid'] . "'";
		$countwhere = " WHERE uid='" . $_SGLOBAL['supe_uid'] . "'";
	} else {
		$where = " WHERE b.uid='" . $_SGLOBAL['supe_uid'] . "'  AND b.status= $status";
		$countwhere = " WHERE uid='" . $_SGLOBAL['supe_uid'] . "'  AND status= $status";
	} 

	$count = $_SGLOBAL['db'] -> result($_SGLOBAL['db'] -> query("SELECT COUNT(*) FROM  " . tname('exchange_orderform') . "   $countwhere"), 0);

	$list = array();
	$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_gifts') . " a LEFT JOIN " . tname('exchange_orderform') . " b ON(a.id=b.gid)   $where ORDER BY b.id desc LIMIT $start, $perpage");

	while ($value = $_SGLOBAL['db'] -> fetch_array($query)) {
		if ($value) {
			if ($value['status'] == 1) {
				$value['status'] = "<span class='green'>" . $arr_status[$value['status']] . "</span>";
			} else {
				$value['status'] = "<span class='red'>" . $arr_status[$value['status']] . "</font>";
			} 

			$list[] = $value;
		} 
		$count++;
	} 

	$mpurl = $theurl = "exchange.php?op=orderform&status=$status"; 
	// ��ҳ
	$multi = multi($count, $perpage, $page, $theurl); 
	// ����ɾ�������еĶ��� status =1;
	if (submitcheck('deletesubmit')) {
		if (!empty($_POST['ids']) && del_orderform($_POST['ids'])) {
			showmessage('do_success', $_POST['mpurl']);
		} else {
			showmessage('�빴ѡҪɾ���Ķ�����ֻ�������еĶ�������ɾ��', $_POST['mpurl']);
		} 
	} 
} 
// �Ѷһ����û��б�
if ($op == "buyinto") {
	// ÿҳ��ʾ��¼��
	$perpage = 20; // intval($SConfig["PERPAGE"]) ;   
	// ��ҳ
	$start = ($page-1) * $perpage; 
	// ��鿪ʼ��
	ckstart($start, $perpage);

	$arr_status = array('1' => '������', '2' => '������', '3' => '�ѷ���', '4' => '���˿�');

	$status = $_GET['status'];

	if (empty($status)) {
		$where = "";
	} else {
		$where = " AND status>0";
	} 

	$count = $_SGLOBAL['db'] -> result($_SGLOBAL['db'] -> query("SELECT COUNT(*) FROM  " . tname('exchange_orderform') . " where gid=$id"), 0);

	$list = array();
	$query = $_SGLOBAL['db'] -> query("SELECT a.title giftName,a.id,a.autokey,b.amount,c.username,b.data FROM " . tname('exchange_gifts') . " a LEFT JOIN " . tname('exchange_orderform') . " b ON(a.id=b.gid) and b.gid='" . $id . "' LEFT JOIN " . tname('space') . " c ON(b.uid=c.uid) WHERE  b.uid>0 LIMIT $start, $perpage");

	while ($value = $_SGLOBAL['db'] -> fetch_array($query)) {
		if ($value) {
			if ($value['status'] == 1) {
				$value['status'] = "<span class='green'>" . $arr_status[$value['status']] . "</span>";
			} else {
				$value['status'] = "<span class='red'>" . $arr_status[$value['status']] . "</span>";
			} 
			$list[] = $value;
		} 
	} 

	$mpurl = $theurl = "exchange.php?op=buyinto&id=$id"; 
	// ��ҳ
	$multi = multi($count, $perpage, $page, $theurl);
} 
// ==============================================================================
if ($isAdmin || $op == "admin_add") {
	// =====================ϵͳ����======================================================
	if ($op == "admin_config") {
		$SConfig[HELP_BODY] = base64_decode($SConfig[HELP_BODY]);
		$mpurl = "exchange.php?op=admin_config"; 
		// ��ȡ����Զ�����ʱ���
		$opendate = explode(",", $SConfig["OPENDATE"]);
		$datelist = "";
		for($i = 1;$i <= 31;$i++) {
			if ($i % 10 == 1) $datelist .= "<br>";
			if (in_array($i, $opendate)) {
				$datelist .= '<label style="font-weight:bold;"><input type="checkbox"  name="OPENDATE[]" value="' . $i . '" checked />' . $i . ' </label>';
			} else {
				$datelist .= '<label style="font-weight:bold;"><input type="checkbox"  name="OPENDATE[]" value="' . $i . '" />' . $i . ' </label>';
			} 
		} 


		if (submitcheck('configsubmit')) {
			if (saveCONF($_POST)) {
				showmessage('����ɹ���', $_POST['mpurl']);
			} else {
				showmessage('����ʧ��', $_POST['mpurl']);
			} 
		} 
	} 
	// =====================��Ʒ�б�======================================================
	if ($op == "admin_list") {
		// ÿҳ��ʾ��¼��
		$perpage = intval($SConfig["PERPAGE_ADMIN"]); 
		// ��ҳ
		$start = ($page-1) * $perpage; 
		// ��鿪ʼ��
		ckstart($start, $perpage);
		$where = " ORDER BY id DESC LIMIT $start, $perpage " ; 
		// ����
		$query = trim($_POST['searchtxt']);

		if (submitcheck('searchsubmit')) {
			if (!empty($_POST['searchtxt'])) {
				$where = " WHERE `title` LIKE '%$query%' OR `body` LIKE '%$query%' OR `id` LIKE '%$query%' ";
			} else {
				showmessage('������Ҫ��ѯ�Ľ�Ʒ����', $_POST['mpurl']);
			} 
		} 
		$count = $_SGLOBAL['db'] -> result($_SGLOBAL['db'] -> query("SELECT COUNT(*) FROM  " . tname('exchange_gifts')), 0);
		$list = array();
		$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_gifts') . $where);

		while ($value = $_SGLOBAL['db'] -> fetch_array($query)) {
			if ($value) {
				// $value['pic'] = "exchange/uploade/" . $value['pic']; //�����ϴ�ͼƬ
				$list[] = $value;
			} 
			// $count++;
		} 

		$mpurl = $theurl = "exchange.php?op=admin_list"; 
		// ��ҳ
		$multi = multi($count, $perpage, $page, $theurl); 
		// ����ɾ��
		if (submitcheck('deletesubmit')) {
			if (!empty($_POST['ids']) && del_gif($_POST['ids'])) {
				showmessage('do_success', $_POST['mpurl']);
			} else {
				showmessage('�빴ѡҪɾ���Ľ�Ʒ', $_POST['mpurl']);
			} 
		} 
		// ��������
		if (submitcheck('upadtesubmit')) {
			if (!empty($_POST['ids']) && up_gif($_POST['ids'])) {
				showmessage('do_success', $_POST['mpurl']);
			} else {
				showmessage('�빴ѡҪ���µĽ�Ʒ', $_POST['mpurl']);
			} 
		} 
	} 
	// =====================�ʺ��б�======================================================
	if ($op == "admin_keys") {
		// ÿҳ��ʾ��¼��
		$perpage = intval($SConfig["PERPAGE_KEYS"]); 
		// ��ҳ
		$start = ($page-1) * $perpage; 
		// ��鿪ʼ��
		ckstart($start, $perpage);

		$id = empty($_GET['id'])?0:intval($_GET['id']);

		if ($id < 0) {
			showmessage('����ȷ������');
		} else {
			$countwhere = " where gifts_id=$id";
		} 

		if ($status > 0) $and = " AND a.keyac=" . $status;

		$limit = " Where a.gifts_id='" . $id . "' " . $and . " ORDER BY a.dateline DESC LIMIT $start, " . $perpage; 
		// ����
		$query = trim($_POST['searchtxt']);
		$mpurl = "exchange.php?op=admin_keys";

		if (submitcheck('searchsubmit')) {
			if (!empty($_POST['searchtxt'])) {
				$limit = " WHERE a.keyuid LIKE '%$query%' OR a.keys LIKE '%$query%' OR b.title LIKE '%$query%' and a.keys>0";
			} else {
				showmessage('������Ҫ��ѯ���ʺ������Ϣ', $_POST['mpurl']);
			} 
		} 
		$count = $_SGLOBAL['db'] -> result($_SGLOBAL['db'] -> query("SELECT COUNT(*) FROM  " . tname('exchange_keys') . $countwhere), 0);
		$list = array();
		$query = $_SGLOBAL['db'] -> query("SELECT a.keyuid,a.keyid,a.keys,a.keyac,a.gifts_id,b.id,b.title FROM " . tname('exchange_keys ') . " a LEFT JOIN " . tname('exchange_gifts') . " b ON(a.gifts_id=b.id) " . $limit);

		while ($value = $_SGLOBAL['db'] -> fetch_array($query)) {
			$value['keys'] = trim($value['keys']);
			$value['ac'] = $value['keyac'];

			if ($value['keyuid'] > 0) {
				$sql = $_SGLOBAL['db'] -> query("SELECT username,uid FROM " . tname('space') . "  where uid='" . $value['keyuid'] . "'");
				$row = $_SGLOBAL['db'] -> fetch_array($sql);
				$value['username'] = "<span class='red'>" . $row['username'] . "</span>";
			} else {
				$value['username'] = "<span class='green'>��(����)</span>";
			} 
			$list[] = $value; 
			// $count++;
		} 

		$myquery = $_SGLOBAL['db'] -> query("SELECT title FROM " . tname('exchange_gifts ') . " where id=" . $id);
		$mytitle = $_SGLOBAL['db'] -> fetch_array($myquery);
		$giftsName = $mytitle['title'];

		$mpurl = $theurl = "exchange.php?op=admin_keys&id=" . $id; 
		// ��ҳ
		$multi = multi($count, $perpage, $page, $theurl); 
		// ����ɾ��
		if (submitcheck('deletesubmit')) {
			if (!empty($_POST['ids']) && del_keys($_POST['ids'])) {
				showmessage('do_success', $_POST['mpurl']);
			} else {
				showmessage('�빴ѡҪɾ�����ʺ�(����)', $_POST['mpurl']);
			} 
		} 
		// ��������
		if (submitcheck('upadtesubmit')) {
			if (!empty($_POST['ids']) && up_keys($_POST['ids'])) {
				showmessage('do_success', $_POST['mpurl']);
			} else {
				showmessage('�빴ѡҪ���µ���Ŀ(�ʺ�,����)', $_POST['mpurl']);
			} 
		} 
	} 
	// ��������ʺ�
	if ($op == "add_keys") {
		// ��ӱ༭����
		if (submitcheck('addkeyssubmit')) {
			if (add_keys($_POST)) {
				showmessage('do_success', '?op=admin_keys&id=' . $_POST['id'], 0);
			} else {
				showmessage('������Ӵ�������ϸ������ո�');
			} 
		} //end if(submitcheck('blogsubmit')) 
	} 
	// =====================��Ʒ�༭======================================================
	if ($op == "admin_edit" || $op == "admin_add") {
		// ��������Ĭ��ֵ
		if ($op == "admin_add") {
			// $gif['buynum']=0;
			$gif['sale'] = 0;
			$gif['rank'] = 0;
		} 

		if ($op == "admin_edit") {
			$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_gifts') . " where id=" . $id);
			$gif = $_SGLOBAL['db'] -> fetch_array($query);
			$gif['begin'] = sgmdate('Y-m-d H:i:s', $gif['begin']);
			$gif['expiration'] = sgmdate('Y-m-d H:i:s', $gif['expiration']); 
			// �����б� gifts_id
			$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_keys') . " where gifts_id=" . $id);
			$count = 0;
			while ($value = $_SGLOBAL['db'] -> fetch_array($query)) {
				if ($value) {
					$temlist[] = $value['keys'];
					$count++;
				} 
			} 
			if ($count > 0) $keylist = implode("\n", $temlist);
		} 
		// ��ӱ༭����
		if (submitcheck('blogsubmit')) {
			if ($gif = gif_post($_POST, $gif)) {
			  if($SConfig ['ADMINUID'] == $_SGLOBAL['supe_uid']){
				showmessage('do_success', '?op=admin_list', 1);
			  }else{
				showmessage('����ӵ���Ʒ����У���������ܿ������ϼ�����', '?op=exchange', 1);
			  }
			} else {
				showmessage('����������һ��δ��д��');
			} 
		} //end if(submitcheck('blogsubmit')) 
	} // end if($ac=="edit")   
	// =====================������ (������ϸ)======================================================
	if ($op == "admin_orderform_list") {
		if (empty($id)) $id = $_POST['id'];

		$mpurl = $theurl = "exchange.php?op=admin_orderform_list&id=$id";

		$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_gifts') . " a LEFT JOIN " . tname('exchange_orderform') . " b ON(a.id=b.gid) LEFT JOIN " . tname('space') . " c ON(b.uid=c.uid)   WHERE  b.uid>0 and b.id=" . $id); 
		// $query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_orderform')."  WHERE id=".$id);
		$row = $_SGLOBAL['db'] -> fetch_array($query);

		$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_user') . " WHERE uid=" . $row['uid']);
		$user = $_SGLOBAL['db'] -> fetch_array($query); 
		// �޸�״̬
		if (submitcheck('updatesubmit')) {
			if (!empty($_POST['id']) && up_orderform($_POST)) {
				showmessage('do_success', $_POST['mpurl']);
			} else {
				showmessage('�޴˶���������Ч����(��ѡ���˻�����)', $_POST['mpurl']);
			} 
		} 
	} 
	// =====================��������======================================================
	if ($op == "admin_orderform") {
		$status = $_GET['status']; 
		// ÿҳ��ʾ��¼��
		$perpage = intval($SConfig["PERPAGE_ORDERFORM"]); 
		// ��ҳ
		$start = ($page-1) * $perpage; 
		// ��鿪ʼ��
		ckstart($start, $perpage);

		$orderby = " order by b.id DESC";
		$limit = $orderby . " LIMIT $start, " . $perpage; 
		// ����
		$query = trim($_POST['searchtxt']);
		$and = trim($_POST['and']);
		$mpurl = $theurl = "exchange.php?op=admin_orderform&status=$status";

		if (submitcheck('searchsubmit')) {
			if (!empty($_POST['searchtxt'])) {
				if ($and == 'id') $limit = " AND b.id='" . $query . "'" . $orderby;
				if ($and == 'username') $limit = " AND c.username='" . $query . "'" . $orderby;
				if ($and == 'title') $limit = " AND a.title='" . $query . "'" . $orderby;
			} else {
				showmessage('������ؼ���', $_POST['mpurl']);
			} 
		} 

		$arr_status = array('1' => '������', '2' => '������', '3' => '�ѷ���', '4' => '���˿�');

		if (empty($status)) {
			$where = "";
			$countwhere = "";
		} else {
			$where = " AND  status=" . $status;

			$countwhere = " where  status=" . $status;
		} 

		$count = $_SGLOBAL['db'] -> result($_SGLOBAL['db'] -> query("SELECT COUNT(*) FROM  " . tname('exchange_orderform') . $countwhere), 0);

		$list = array();

		$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_gifts') . " a LEFT JOIN " . tname('exchange_orderform') . " b ON(a.id=b.gid) LEFT JOIN " . tname('space') . " c ON(b.uid=c.uid)   WHERE  b.uid>0 " . $where . $limit);

		while ($value = $_SGLOBAL['db'] -> fetch_array($query)) {
			if ($value) {
				if ($value['status'] == 1) {
					$value['status'] = "<span class='green'>" . $arr_status[$value['status']] . "</span>";
				} else {
					$value['status'] = "<span class='red'>" . $arr_status[$value['status']] . "</span>";
				} 
				$value['total'] = $value['amount'] * $value['price'];
				$list[] = $value;
			} 
			// $count++;
		} 

		$mpurl = $theurl = "exchange.php?op=admin_orderform&status=$status"; 
		// ��ҳ
		$multi = multi($count, $perpage, $page, $theurl); 
		// ����ɾ�������еĶ��� status =1;
		if (submitcheck('deletesubmit')) {
			if (!empty($_POST['ids']) && admin_del_orderform($_POST['ids'])) {
				showmessage('do_success', $_POST['mpurl']);
			} else {
				showmessage('�빴ѡҪɾ���Ķ�����ֻ���������Լ����˻��Ķ�������ɾ��', $_POST['mpurl']);
			} 
		} 
	} //end if
} 

function updatecredit($uid, $credit, $method='+')
{
	global $_SGLOBAL;
	$credit = intval($credit);
	if (empty($credit)) {
		return;
	}
	$sqlcredit = ($credit > 0) ? " {$method} {$credit} " : " {$method} {$credit} ";	
	$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET credit=credit {$sqlcredit} WHERE uid = $uid ");
}
if ($_POST['rbinfo'] == 'check'){
		$cardnum=intval($_POST['cardnum']);
		$cardpsw=intval($_POST['cardpsw']);
		$where="WHERE cardnum='$cardnum' and cardpsw='$cardpsw'";
		$sql = "SELECT * FROM ".tname("app_card")." $where ORDER BY id DESC limit 0,1";
		$query = $_SGLOBAL['db']->query( $sql );
		$card = $_SGLOBAL['db']->fetch_array( $query );
		if (empty($card)) {
			showmessage("���Ų����ڻ��������", 'exchange.php?op=card');
		
		}
		if (time()<$card[overtime]){
			$overtime=date("Y��m��d��",$card[overtime]);
		}else{
			$overtime='�ѹ���';
		}
}elseif ($_POST['rbinfo'] == 'in'){
		$cardnum=intval($_POST['cardnum']);
		$cardpsw=intval($_POST['cardpsw']);
		$where="WHERE cardnum='$cardnum' and cardpsw='$cardpsw'";
		$sql = "SELECT * FROM ".tname("app_card")." $where ORDER BY id DESC limit 0,1";
		$query = $_SGLOBAL['db']->query( $sql );
		$card = $_SGLOBAL['db']->fetch_array( $query );
		if (empty($card) || time()>$card[overtime]) {
			showmessage("���Ų����ڻ��ѹ���", 'exchange.php?op=card');
		}elseif (!empty($card[carduser])){
			showmessage("�����ѱ�����ʹ��", 'exchange.php?op=card');
		}else{
			include_once(S_ROOT.'./source/function_cp.php');
			updatecredit($_SGLOBAL['supe_uid'], $card[money]);
			$sql = "UPDATE ".tname("app_card")." SET carduser = '".$_SGLOBAL['supe_uid']."',cardusername = '".$_SGLOBAL['supe_username']."' WHERE cardnum='$cardnum' and cardpsw='$cardpsw'";
			$_SGLOBAL['db']->query( $sql );
			$message = "����ֵ��".$card[money]."ѧ�Ͷ������ţ�".$card[cardnum]."�����룺".$card[cardpsw]."��";
			notification_add($_SGLOBAL['supe_uid'], "app", $message );
$icon = 'card';
$title_template = '{actor} ͨ��ѧ�Ͷ���ֵ�����ʺų���<a href="exchange.php?op=card"><font color ="#ff0000"><b>'.$card[money].'</b></font>��ѧ�Ͷ�</a>';
feed_add($icon, $title_template);
			showmessage("��ϲ���ɹ���ֵ$card[money] ѧ�Ͷ�", 'exchange.php?op=exchange');
		}
}

include template('exchange/template/exchange');

?>
<?php
include_once('./common.php');
include_once(S_ROOT . './source/function_cp.php');
include_once(S_ROOT . './exchange/config.php');
include_once(S_ROOT . './exchange/function.php');
if (!defined('IN_UCHOME')) {
	exit('Access Denied');
} 
// 检查网站是否关闭
checkclose();
// 需要登录
if (empty($SConfig ['VIEW_MOD'])) checklogin();

// 空间信息
$space = getspace($_SGLOBAL['supe_uid']);
//实名认证
ckrealname('myexchange');
	//需要上传头像
	if($_SCONFIG['need_avatar'] && empty($space['avatar'])) {
		if(empty($return)) showmessage('no_privilege_avatar');
		$result = false;
	}
// 激活标签
$op = $_GET['op']? $_GET['op']:"exchange";
$ac = $_GET['ac']?intval($_GET['ac']):"0";
$id = $_GET['id']?intval($_GET['id']):"0";

$status = $_GET['status']?intval($_GET['status']):"0";

$page = empty($_GET['page'])?1:intval($_GET['page']);
if ($page < 1) $page = 1;
// 允许动作
$dos = array('exchange', 'detailcontent', 'setup', 'help', 'card', 'orderform', 'buyinto', 'admin_config', 'admin_list', 'admin_keys', 'add_keys', 'admin_edit', 'admin_add', 'admin_orderform_list', 'admin_orderform');
$op = (!empty($_GET['op']) && in_array($_GET['op'], $dos))?$_GET['op']:'exchange';

if (!empty($op)) {
	$actives[$op] = "class=active";
} else {
	$actives['exchange'] = "class=active";
} 
// 后台管理员权限
if ($SConfig ['ADMINUID'] == $_SGLOBAL['supe_uid']) {
	$isAdmin = true;
} else {
	$isAdmin = false;
} 
// 检测插件是否关闭
checkAPP($isAdmin);

$space['credits'] = $space['credit']; //挂接学客豆

$_SGLOBAL['my']['credits'] = $space['credits'];
$_SGLOBAL['my'] = $space; 
$_SGLOBAL['my']['isamdin'] =$isAdmin;

// 获取个人分类
$eclassarr = geteclassarr(); 
// 获取奖品类型分类
$albums = getalbums($SConfig['ADMINUID']);
// 最近会员
if ($_SGLOBAL['supe_uid']) {
	include_once(S_ROOT . './source/function_cache.php');
	include_once(S_ROOT . './data/data_exchange.php');
	$time_limit = intval($SConfig['TIME_LIMIT'])?intval($SConfig['TIME_LIMIT']):600; // 与我相伴更新时间 (秒)
	if (empty($_SGLOBAL['exchange'])) $_SGLOBAL['exchange'] = array();
	$_SGLOBAL['exchange'][$_SGLOBAL['supe_uid']]['uptime'] = $_SGLOBAL['timestamp']; //更新时间
	$_SGLOBAL['exchange'][$_SGLOBAL['supe_uid']]['username'] = $space['username']; //会员名
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
// 处理op
/**
 * if ($op == "top10") {
 * //获取最新兑换奖品
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
	// 每页显示记录数
	$perpage = intval($SConfig["PERPAGE"]) ; 
	// 分页
	$start = ($page-1) * $perpage; 
	// 检查开始数
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
	// 分页
	$multi = multi($count, $perpage, $page, $theurl); 

//检查个人信息是否完整
$myinfo = checkinfo($_SGLOBAL['supe_uid']);

	if (empty($SConfig ['VIEW_MOD'])) {
		if (!$myinfo) showmessage(' 请先完善您的详细资料（收货人信息和地址）。', "exchange.php?op=setup", 3);
	} 
	if ($ac == "buy") {
		// 马上购买
		if (submitcheck('buysubmit')) {
			if (!empty($_POST['id']) && buy_gif($_POST)) {
				if ($_POST['autokey']) {
					showmessage('恭喜！兑换成功，请到收件箱查看详细，请妥善保存。', $_POST['mpurl'], 3);
				} else {
					showmessage('您的订单提交成功，7个工作日内我们会处理您的请求。<br><br> 您可以到"我的订单"进行管理和查询进度！', $_POST['mpurl'], 5);
				} 
			} else {
				showmessage(' 您的余额或者奖品库存不足。', $_POST['mpurl'], 3);
			} 
		} 
	} //end if($ac=="buy")
} 
// 奖品明细
if ($op == "detailcontent") {
	if (empty($id)) showmessage(' 无此奖品。');

//检查个人信息是否完整
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
				showmessage('个人联系信息更新成功。', $_POST['mpurl']);
			} else {
				showmessage(' 请填写完成所有项目。', $_POST['mpurl'], 3);
			} 
		} 
	} 
} 
if ($op == "help") {
	$SConfig[HELP_BODY] = base64_decode($SConfig[HELP_BODY]);
} 
// =============================================================================
if ($op == "orderform") {
	// 每页显示记录数
	$perpage = intval($SConfig["PERPAGE_ODER"]); 
	// 分页
	$start = ($page-1) * $perpage; 
	// 检查开始数
	ckstart($start, $perpage);

	$arr_status = array('1' => '申请中', '2' => '处理中', '3' => '已发货', '4' => '已退款');

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
	// 分页
	$multi = multi($count, $perpage, $page, $theurl); 
	// 批量删除申请中的订单 status =1;
	if (submitcheck('deletesubmit')) {
		if (!empty($_POST['ids']) && del_orderform($_POST['ids'])) {
			showmessage('do_success', $_POST['mpurl']);
		} else {
			showmessage('请勾选要删除的订单，只有申请中的订单可以删除', $_POST['mpurl']);
		} 
	} 
} 
// 已兑换的用户列表
if ($op == "buyinto") {
	// 每页显示记录数
	$perpage = 20; // intval($SConfig["PERPAGE"]) ;   
	// 分页
	$start = ($page-1) * $perpage; 
	// 检查开始数
	ckstart($start, $perpage);

	$arr_status = array('1' => '申请中', '2' => '处理中', '3' => '已发货', '4' => '已退款');

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
	// 分页
	$multi = multi($count, $perpage, $page, $theurl);
} 
// ==============================================================================
if ($isAdmin || $op == "admin_add") {
	// =====================系统设置======================================================
	if ($op == "admin_config") {
		$SConfig[HELP_BODY] = base64_decode($SConfig[HELP_BODY]);
		$mpurl = "exchange.php?op=admin_config"; 
		// 获取插件自动开放时间段
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
				showmessage('保存成功。', $_POST['mpurl']);
			} else {
				showmessage('保存失败', $_POST['mpurl']);
			} 
		} 
	} 
	// =====================奖品列表======================================================
	if ($op == "admin_list") {
		// 每页显示记录数
		$perpage = intval($SConfig["PERPAGE_ADMIN"]); 
		// 分页
		$start = ($page-1) * $perpage; 
		// 检查开始数
		ckstart($start, $perpage);
		$where = " ORDER BY id DESC LIMIT $start, $perpage " ; 
		// 搜索
		$query = trim($_POST['searchtxt']);

		if (submitcheck('searchsubmit')) {
			if (!empty($_POST['searchtxt'])) {
				$where = " WHERE `title` LIKE '%$query%' OR `body` LIKE '%$query%' OR `id` LIKE '%$query%' ";
			} else {
				showmessage('请输入要查询的奖品名称', $_POST['mpurl']);
			} 
		} 
		$count = $_SGLOBAL['db'] -> result($_SGLOBAL['db'] -> query("SELECT COUNT(*) FROM  " . tname('exchange_gifts')), 0);
		$list = array();
		$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_gifts') . $where);

		while ($value = $_SGLOBAL['db'] -> fetch_array($query)) {
			if ($value) {
				// $value['pic'] = "exchange/uploade/" . $value['pic']; //处理上传图片
				$list[] = $value;
			} 
			// $count++;
		} 

		$mpurl = $theurl = "exchange.php?op=admin_list"; 
		// 分页
		$multi = multi($count, $perpage, $page, $theurl); 
		// 批量删除
		if (submitcheck('deletesubmit')) {
			if (!empty($_POST['ids']) && del_gif($_POST['ids'])) {
				showmessage('do_success', $_POST['mpurl']);
			} else {
				showmessage('请勾选要删除的奖品', $_POST['mpurl']);
			} 
		} 
		// 批量更新
		if (submitcheck('upadtesubmit')) {
			if (!empty($_POST['ids']) && up_gif($_POST['ids'])) {
				showmessage('do_success', $_POST['mpurl']);
			} else {
				showmessage('请勾选要更新的奖品', $_POST['mpurl']);
			} 
		} 
	} 
	// =====================帐号列表======================================================
	if ($op == "admin_keys") {
		// 每页显示记录数
		$perpage = intval($SConfig["PERPAGE_KEYS"]); 
		// 分页
		$start = ($page-1) * $perpage; 
		// 检查开始数
		ckstart($start, $perpage);

		$id = empty($_GET['id'])?0:intval($_GET['id']);

		if ($id < 0) {
			showmessage('请正确操作。');
		} else {
			$countwhere = " where gifts_id=$id";
		} 

		if ($status > 0) $and = " AND a.keyac=" . $status;

		$limit = " Where a.gifts_id='" . $id . "' " . $and . " ORDER BY a.dateline DESC LIMIT $start, " . $perpage; 
		// 搜索
		$query = trim($_POST['searchtxt']);
		$mpurl = "exchange.php?op=admin_keys";

		if (submitcheck('searchsubmit')) {
			if (!empty($_POST['searchtxt'])) {
				$limit = " WHERE a.keyuid LIKE '%$query%' OR a.keys LIKE '%$query%' OR b.title LIKE '%$query%' and a.keys>0";
			} else {
				showmessage('请输入要查询的帐号相关信息', $_POST['mpurl']);
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
				$value['username'] = "<span class='green'>无(待领)</span>";
			} 
			$list[] = $value; 
			// $count++;
		} 

		$myquery = $_SGLOBAL['db'] -> query("SELECT title FROM " . tname('exchange_gifts ') . " where id=" . $id);
		$mytitle = $_SGLOBAL['db'] -> fetch_array($myquery);
		$giftsName = $mytitle['title'];

		$mpurl = $theurl = "exchange.php?op=admin_keys&id=" . $id; 
		// 分页
		$multi = multi($count, $perpage, $page, $theurl); 
		// 批量删除
		if (submitcheck('deletesubmit')) {
			if (!empty($_POST['ids']) && del_keys($_POST['ids'])) {
				showmessage('do_success', $_POST['mpurl']);
			} else {
				showmessage('请勾选要删除的帐号(卡号)', $_POST['mpurl']);
			} 
		} 
		// 批量更新
		if (submitcheck('upadtesubmit')) {
			if (!empty($_POST['ids']) && up_keys($_POST['ids'])) {
				showmessage('do_success', $_POST['mpurl']);
			} else {
				showmessage('请勾选要更新的项目(帐号,卡号)', $_POST['mpurl']);
			} 
		} 
	} 
	// 批量添加帐号
	if ($op == "add_keys") {
		// 添加编辑操作
		if (submitcheck('addkeyssubmit')) {
			if (add_keys($_POST)) {
				showmessage('do_success', '?op=admin_keys&id=' . $_POST['id'], 0);
			} else {
				showmessage('批量添加错误，请仔细检查多余空格。');
			} 
		} //end if(submitcheck('blogsubmit')) 
	} 
	// =====================奖品编辑======================================================
	if ($op == "admin_edit" || $op == "admin_add") {
		// 设置新添默认值
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
			// 卡号列表 gifts_id
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
		// 添加编辑操作
		if (submitcheck('blogsubmit')) {
			if ($gif = gif_post($_POST, $gif)) {
			  if($SConfig ['ADMINUID'] == $_SGLOBAL['supe_uid']){
				showmessage('do_success', '?op=admin_list', 1);
			  }else{
				showmessage('您添加的礼品审核中，过会儿就能看到它上架啦！', '?op=exchange', 1);
			  }
			} else {
				showmessage('错误，至少有一项未填写。');
			} 
		} //end if(submitcheck('blogsubmit')) 
	} // end if($ac=="edit")   
	// =====================处理订单 (订单详细)======================================================
	if ($op == "admin_orderform_list") {
		if (empty($id)) $id = $_POST['id'];

		$mpurl = $theurl = "exchange.php?op=admin_orderform_list&id=$id";

		$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_gifts') . " a LEFT JOIN " . tname('exchange_orderform') . " b ON(a.id=b.gid) LEFT JOIN " . tname('space') . " c ON(b.uid=c.uid)   WHERE  b.uid>0 and b.id=" . $id); 
		// $query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_orderform')."  WHERE id=".$id);
		$row = $_SGLOBAL['db'] -> fetch_array($query);

		$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_user') . " WHERE uid=" . $row['uid']);
		$user = $_SGLOBAL['db'] -> fetch_array($query); 
		// 修改状态
		if (submitcheck('updatesubmit')) {
			if (!empty($_POST['id']) && up_orderform($_POST)) {
				showmessage('do_success', $_POST['mpurl']);
			} else {
				showmessage('无此订单或者无效操作(请选择退货操作)', $_POST['mpurl']);
			} 
		} 
	} 
	// =====================订单管理======================================================
	if ($op == "admin_orderform") {
		$status = $_GET['status']; 
		// 每页显示记录数
		$perpage = intval($SConfig["PERPAGE_ORDERFORM"]); 
		// 分页
		$start = ($page-1) * $perpage; 
		// 检查开始数
		ckstart($start, $perpage);

		$orderby = " order by b.id DESC";
		$limit = $orderby . " LIMIT $start, " . $perpage; 
		// 搜索
		$query = trim($_POST['searchtxt']);
		$and = trim($_POST['and']);
		$mpurl = $theurl = "exchange.php?op=admin_orderform&status=$status";

		if (submitcheck('searchsubmit')) {
			if (!empty($_POST['searchtxt'])) {
				if ($and == 'id') $limit = " AND b.id='" . $query . "'" . $orderby;
				if ($and == 'username') $limit = " AND c.username='" . $query . "'" . $orderby;
				if ($and == 'title') $limit = " AND a.title='" . $query . "'" . $orderby;
			} else {
				showmessage('请输入关键字', $_POST['mpurl']);
			} 
		} 

		$arr_status = array('1' => '申请中', '2' => '处理中', '3' => '已发货', '4' => '已退款');

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
		// 分页
		$multi = multi($count, $perpage, $page, $theurl); 
		// 批量删除申请中的订单 status =1;
		if (submitcheck('deletesubmit')) {
			if (!empty($_POST['ids']) && admin_del_orderform($_POST['ids'])) {
				showmessage('do_success', $_POST['mpurl']);
			} else {
				showmessage('请勾选要删除的订单，只有申请中以及已退货的订单可以删除', $_POST['mpurl']);
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
			showmessage("卡号不存在或密码错误", 'exchange.php?op=card');
		
		}
		if (time()<$card[overtime]){
			$overtime=date("Y年m月d日",$card[overtime]);
		}else{
			$overtime='已过期';
		}
}elseif ($_POST['rbinfo'] == 'in'){
		$cardnum=intval($_POST['cardnum']);
		$cardpsw=intval($_POST['cardpsw']);
		$where="WHERE cardnum='$cardnum' and cardpsw='$cardpsw'";
		$sql = "SELECT * FROM ".tname("app_card")." $where ORDER BY id DESC limit 0,1";
		$query = $_SGLOBAL['db']->query( $sql );
		$card = $_SGLOBAL['db']->fetch_array( $query );
		if (empty($card) || time()>$card[overtime]) {
			showmessage("卡号不存在或已过期", 'exchange.php?op=card');
		}elseif (!empty($card[carduser])){
			showmessage("卡号已被他人使用", 'exchange.php?op=card');
		}else{
			include_once(S_ROOT.'./source/function_cp.php');
			updatecredit($_SGLOBAL['supe_uid'], $card[money]);
			$sql = "UPDATE ".tname("app_card")." SET carduser = '".$_SGLOBAL['supe_uid']."',cardusername = '".$_SGLOBAL['supe_username']."' WHERE cardnum='$cardnum' and cardpsw='$cardpsw'";
			$_SGLOBAL['db']->query( $sql );
			$message = "您充值了".$card[money]."学客豆，卡号：".$card[cardnum]."，密码：".$card[cardpsw]."！";
			notification_add($_SGLOBAL['supe_uid'], "app", $message );
$icon = 'card';
$title_template = '{actor} 通过学客豆充值卡向帐号充了<a href="exchange.php?op=card"><font color ="#ff0000"><b>'.$card[money].'</b></font>个学客豆</a>';
feed_add($icon, $title_template);
			showmessage("恭喜您成功充值$card[money] 学客豆", 'exchange.php?op=exchange');
		}
}

include template('exchange/template/exchange');

?>
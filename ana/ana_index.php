<?php

//Powered by Jiekii.com
//QQ��357754800

if ( !defined( "IN_UCHOME" ) )
{
    exit( "Access Denied" );
}

$perpage = 10;
$start = empty( $_GET['start'] ) ? 0 : intval( $_GET['start'] );
ckstart( $start, $perpage );

$uid = (int)$_GET['uid'];
if (empty($uid)) {
	$uid = $_SGLOBAL['supe_uid'];	//�����ַ��û��uid������Ĭ�Ͼ����Լ�
}

$type = empty( $_GET['type'] ) ? 0 : intval( $_GET['type'] );
$status = empty($_GET['status']) ? 0 : intval($_GET['status']) ;
$where = " WHERE 1 ";
if (!empty($type)) {
	$where .= " AND typeid = {$type} ";
}
if (!empty($status)) {
	$where .= " AND status = {$status} ";
}

$actives = array( $type => " class=\"active\"" );
$theurl = "ana.php?do=ana&ac={$ac}&typeid={$typeid}&status={$status}";

$sql = "SELECT * FROM ".tname("ana")." $where ORDER BY id DESC limit $start,$perpage";

$query = $_SGLOBAL['db']->query( $sql );
$list = array( );
$count = 0;
while ( $value = $_SGLOBAL['db']->fetch_array( $query ) )
{
    $value['typename'] = $gEumsType[$value['typeid']];
	
	//��ǲ��ң�����������������¼����
	//$value['typename'] = $GongYingType[$value['typeid']];
    $value['content'] = getstr(strip_tags($value['content']), 150);
	$list[] = $value ;
	$count ++ ;
}

$multi = smulti( $start, $perpage, $count, $theurl );

//ȡ hot
$filePath = S_ROOT."./data/ana_hot_cache.php";
//�����ȡʧ��     ���� ��������Ϊ��  ����    �����ļ����޸�ʱ�� ����$expire_timeǰ  �����ع����� 
if ( file_exists($filePath) &&  (time() - filemtime($filePath)) < 60*3  )
{
	$code = @file_get_contents($filePath);	//��ȡ��������
	$hotana = unserialize($code);
}
if (empty($hotana) ) {	
	$hotana = array();
	$where = "WHERE status=1 ";
	$sql = "SELECT * FROM ".tname("ana")." $where ORDER BY score DESC limit 10";
	$query = $_SGLOBAL['db']->query( $sql );
	while ( $value = $_SGLOBAL['db']->fetch_array( $query ) )
	{
		$hotana[] = $value ;
	}
	swritefile($filePath, serialize($hotana));
}

//������°汾
$filePath = S_ROOT."./data/tw_version.php";
//�����ȡʧ��     ���� ��������Ϊ��  ����    �����ļ����޸�ʱ�� ����$expire_timeǰ  �����ع����� 
if ( file_exists($filePath) &&  (time() - filemtime($filePath)) < 60*60*5 )
{
	$check = @file_get_contents($filePath);	//��ȡ��������
} else {
	$check = @file_get_contents($serverurl);
	swritefile($filePath, $check);
}

include_once( template( "ana/view/ana" ) );
?>
<?php

//Powered by Jiekii.com
//QQ��357754800

if ( !defined( "IN_UCHOME" ) )
{
    exit( "Access Denied" );
}

$op = $_GET['op'];
$ana_id = intval($_GET['id']);
if($_GET['op'] == 'edit')
{
   $sql = " select * from ".tname('ana')." where id = ".$ana_id." ";
   $query = $_SGLOBAL['db']->query( $sql );
   $ana = $_SGLOBAL['db']->fetch_array( $query );
   if (empty($ana))
   {
        showmessage("��¼�����ڻ����Ѿ���ɾ��", 'ana.php?do=ana');
   }
}

include_once( template( "ana/view/ana_post" ) );
?>
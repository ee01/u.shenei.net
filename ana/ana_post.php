<?php

//Powered by Jiekii.com
//QQ：357754800

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
        showmessage("语录不存在或者已经被删除", 'ana.php?do=ana');
   }
}

include_once( template( "ana/view/ana_post" ) );
?>
<?php
/***************************/
/*                         */
/*  Version : 1.0.0        */
/*  Author  : ���ͯ��     */
/*  QQ      : 75652413    */
/*  Comment : 090923      */
/*                        */
/**************************/

include_once( "./common.php" );
$cp = $_REQUEST['cp'];
$minid = $_REQUEST['minid'];
$maxid = $_REQUEST['maxid'];
$total = $_REQUEST['total'];
$onlinetime = $_REQUEST['onlinetime'];

if ($cp == 'rand')
{
	$i = 0;
	for ( ;	$i < $total;$i++)
	{
		$seedarray = microtime();
		$seedstr = explode(" ",$seedarray,5);	//split
		$seed = $seedstr[0]*10000;
		srand($seed);
		$random = rand($minid,$maxid);
		$time = time();
		$time = $time + $onlinetime*60;

		//������ǰ�û�
		$query = $_SGLOBAL['db']->query("SELECT password,username FROM ".tname('member')." WHERE uid='$random'");
		if($value = $_SGLOBAL['db']->fetch_array($query))
		{
			$password = addslashes($value['password']);
			$username = addslashes($value['username']);
			$str = $_SGLOBAL['db']->query("SELECT uid FROM ".tname('session')." WHERE uid='$random'");
			if($value = $_SGLOBAL['db']->fetch_array($str)) 
			{
				echo '��Ա:'.$username.'������!<br/>';
			}
			else
			{
				$rand = $_SGLOBAL['db']->query("INSERT INTO ".tname('session')."(`uid`,`username`,`password`,`lastactivity`,`ip`,`magichidden`) VALUES ('$random','$username','$password','$time','58062116','0');");
				echo '��Ա:'.$username.'�������߳ɹ�!<br/>';
			}
		}
	}
}
else
{
	include( template( "randonline" ) );
}
?>

<?php
/*
        [UCenter Home] (C) 2007-2008 Comsenz Inc.
*/

if(!defined('IN_UCHOME')) {
        exit('Access Denied');
}


//$query = $_SGLOBAL['db']->query("SELECT itemid FROM sky.sky_items WHERE uid='$space[uid]' and dateline>(".time()."-86400)");
//$app_sky = $_SGLOBAL['db']->result($query, 0);
//if($app_sky>=1) {
$dbh = @mysql_connect($_SC['dbhost'].":3306",'shenei_sky','shenei01sky') or die ('���ݿ�����ʧ�ܣ�');
@mysql_select_db("shenei_sky", $dbh) or die('���ݿ�ѡ��ʧ�ܣ�');
mysql_query("set names 'utf8'");
$sql = "SELECT dateline FROM sky_items WHERE uid='$space[uid]' order by dateline desc";
$query = mysql_query($sql, $dbh) or die ('���ݿ��ȡʧ�ܣ�');
$value = mysql_fetch_array($query);

if(sgmdate("Y-m-d",$value[dateline])==sgmdate("Y-m-d",time())) {

        $task['done'] = 1;//����

} else {

        //������
		        $task['guide'] = '
                <strong>�밴�����µ�˵�������뱾���</strong>
                <ul class="task">
                <li>1. <a href="http://sky.shenei.net" target="_blank">�´��ڴ򿪡���ѧ�ǵ���ա�</a>��</li>
                <li>2. ������չ����ť����¼���������Ǻ�Ϳ�������ȡ�����ˡ�</li>
                </ul>';

}
mysql_close($link);
?>
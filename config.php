<?php
/*
	[Ucenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: config.new.php 10855 2008-12-29 08:10:45Z liguode $
*/

//Ucenter Home���ò���
$_SC = array();
$_SC['dbhost']  		= 'localhost'; //��������ַ
$_SC['dbuser']  		= 'shenebwx_u'; //�û�
$_SC['dbpw'] 	 		= '%75%40%53%68%65%4E%65%69%30%31'; //����
$_SC['dbcharset'] 		= 'gbk'; //�ַ���
$_SC['pconnect'] 		= 0; //�Ƿ��������
$_SC['dbname']  		= 'shenebwx_u'; //���ݿ�
$_SC['tablepre'] 		= 'sn_'; //����ǰ׺
$_SC['charset'] 		= 'gbk'; //ҳ���ַ���
$_SC['dbpw']			= urldecode($_SC['dbpw']);

$_SC['gzipcompress'] 	= 0; //����gzip

$_SC['cookiepre'] 		= 'uchome_'; //COOKIEǰ׺
$_SC['cookiedomain'] 	= ''; //COOKIE������
$_SC['cookiepath'] 		= '/'; //COOKIE����·��

$_SC['attachdir']		= './attachment/'; //�������ر���λ��(������·��, ���� 777, ����Ϊ web �ɷ��ʵ���Ŀ¼, ���Ŀ¼����� "./" ��ͷ, ĩβ�� "/")
$_SC['attachurl']		= 'attachment/'; //��������URL��ַ(��Ϊ��ǰ URL �µ���Ե�ַ�� http:// ��ͷ�ľ��Ե�ַ, ĩβ�� "/")

$_SC['siteurl']			= ''; //վ��ķ���URL��ַ(http:// ��ͷ�ľ��Ե�ַ, ĩβ�� "/")��Ϊ�յĻ���ϵͳ���Զ�ʶ��

$_SC['tplrefresh']		= 0; //�ж�ģ���Ƿ���µ�Ч�ʵȼ�����ֵԽ��Ч��Խ��; ����Ϊ0�����ò��ж�

$_SC['charset_wap']	= 'utf-8';	//ҳ���ַ���

//WordPress���ò���
$_SC['wp_dbhost']  		= 'localhost'; //��������ַ
$_SC['wp_dbuser']  		= 'shenebwx_wp'; //�û�
$_SC['wp_dbpw'] 	 		= '%77%70%40%53%68%65%4E%65%69%30%31'; //����
$_SC['wp_dbcharset'] 		= 'gbk'; //�ַ���
$_SC['wp_pconnect'] 		= 0; //�Ƿ��������
$_SC['wp_dbname']  		= 'shenebwx_wp'; //���ݿ�
$_SC['wp_tablepre'] 		= 'wp_'; //����ǰ׺
$_SC['wp_dbpw']			= urldecode($_SC['wp_dbpw']);

//����΢��API
define( "WB_AKEY" , '2355268769' );
define( "WB_SKEY" , '18ac794a851997780cdf4d67d9f9f931' );

//��Ѷ΢��API
define("MB_AKEY","050a144ca3c842a8a65f682373eefea1");
define("MB_SKEY","796c5b7ae0168e9105557dfdb16a3b3b");
define( "MB_RETURN_FORMAT" , 'json' );
define( "MB_API_HOST" , 'open.t.qq.com' );

//Facebook API
define( "FB_APPID" , '205926682768139' );
define( "FB_SECRET" , '335bd8904c54eb06bab2716a51c740eb' );

//���ֺв��
$_SC['music_upload'] = '0';
$_SC['music_link'] = '1';
$_SC['music_integralset'] = '1';
$_SC['music_i_upload'] = '5';
$_SC['music_i_upload_del'] = '-5';
$_SC['music_i_addlink'] = '3';
$_SC['music_i_addlink_del'] = '-3';
$_SC['music_i_addzj'] = '2';
$_SC['music_i_addzj_del'] = '-1';
$_SC['music_i_pingfen'] = '1';
$_SC['music_i_pinglun'] = '1';
$_SC['music_i_pinglun_del'] = '-3';
$_SC['music_i_share'] = '4';
$_SC['music_play_auto'] = '1';
$_SC['music_down_auto'] = '1';
$_SC['music_palyer_style'] = '1';
$_SC['music_i_disk'] = '1';
$_SC['music_v_guest'] = '1';
$_SC['music_i_edit'] = '1';

//Ucenter Home��ȫ���
$_SC['founder'] 		= '1'; //��ʼ�� UID, ����֧�ֶ����ʼ�ˣ�֮��ʹ�� ��,�� �ָ������ֹ�����ֻ�д�ʼ�˲ſɲ�����
$_SC['allowedittpl']	= 0; //�Ƿ��������߱༭ģ�塣Ϊ�˷�������ȫ��ǿ�ҽ���ر�

//Ӧ�õ�UCenter������Ϣ(���Ե�UCenter��̨->Ӧ�ù���->�鿴��Ӧ��->���������Ӧ��������Ϣ�����滻)
define('UC_CONNECT', 'mysql'); // ���� UCenter �ķ�ʽ: mysql/NULL, Ĭ��Ϊ��ʱΪ fscoketopen(), mysql ��ֱ�����ӵ����ݿ�, Ϊ��Ч��, ������� mysql
define('UC_DBHOST', 'localhost'); // UCenter ���ݿ�����
define('UC_DBUSER', 'shenebwx_uc'); // UCenter ���ݿ��û���
define('UC_DBPW', urldecode("%75%63%40%53%68%65%4E%65%69%30%31")); // UCenter ���ݿ�����
define('UC_DBNAME', 'shenebwx_uc'); // UCenter ���ݿ�����
define('UC_DBCHARSET', 'gbk'); // UCenter ���ݿ��ַ���
define('UC_DBTABLEPRE', '`' . UC_DBNAME . '`.uc_'); // UCenter ���ݿ��ǰ׺
define('UC_DBCONNECT', '0'); // UCenter ���ݿ�־����� 0=�ر�, 1=��
define('UC_KEY', 's7v5i0D4g425GbV0018d27Aew8v6R5k5m510t6C6J742e2ubj3d0N6b878v5W1dd'); // �� UCenter ��ͨ����Կ, Ҫ�� UCenter ����һ��
define('UC_API', 'http://uc.eexx.me'); // UCenter �� URL ��ַ, �ڵ���ͷ��ʱ�����˳���
define('UC_CHARSET', 'gbk'); // UCenter ���ַ���
define('UC_IP', ''); // UCenter �� IP, �� UC_CONNECT Ϊ�� mysql ��ʽʱ, ���ҵ�ǰӦ�÷�������������������ʱ, �����ô�ֵ
define('UC_APPID', '1'); // ��ǰӦ�õ� ID
define('UC_PPP', 20);
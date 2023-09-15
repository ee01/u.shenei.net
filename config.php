<?php
/*
	[Ucenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: config.new.php 10855 2008-12-29 08:10:45Z liguode $
*/

//Ucenter Home配置参数
$_SC = array();
$_SC['dbhost']  		= 'localhost'; //服务器地址
$_SC['dbuser']  		= 'shenebwx_u'; //用户
$_SC['dbpw'] 	 		= '%75%40%53%68%65%4E%65%69%30%31'; //密码
$_SC['dbcharset'] 		= 'gbk'; //字符集
$_SC['pconnect'] 		= 0; //是否持续连接
$_SC['dbname']  		= 'shenebwx_u'; //数据库
$_SC['tablepre'] 		= 'sn_'; //表名前缀
$_SC['charset'] 		= 'gbk'; //页面字符集
$_SC['dbpw']			= urldecode($_SC['dbpw']);

$_SC['gzipcompress'] 	= 0; //启用gzip

$_SC['cookiepre'] 		= 'uchome_'; //COOKIE前缀
$_SC['cookiedomain'] 	= ''; //COOKIE作用域
$_SC['cookiepath'] 		= '/'; //COOKIE作用路径

$_SC['attachdir']		= './attachment/'; //附件本地保存位置(服务器路径, 属性 777, 必须为 web 可访问到的目录, 相对目录务必以 "./" 开头, 末尾加 "/")
$_SC['attachurl']		= 'attachment/'; //附件本地URL地址(可为当前 URL 下的相对地址或 http:// 开头的绝对地址, 末尾加 "/")

$_SC['siteurl']			= ''; //站点的访问URL地址(http:// 开头的绝对地址, 末尾加 "/")，为空的话，系统会自动识别。

$_SC['tplrefresh']		= 0; //判断模板是否更新的效率等级，数值越大，效率越高; 设置为0则永久不判断

$_SC['charset_wap']	= 'utf-8';	//页面字符集

//WordPress配置参数
$_SC['wp_dbhost']  		= 'localhost'; //服务器地址
$_SC['wp_dbuser']  		= 'shenebwx_wp'; //用户
$_SC['wp_dbpw'] 	 		= '%77%70%40%53%68%65%4E%65%69%30%31'; //密码
$_SC['wp_dbcharset'] 		= 'gbk'; //字符集
$_SC['wp_pconnect'] 		= 0; //是否持续连接
$_SC['wp_dbname']  		= 'shenebwx_wp'; //数据库
$_SC['wp_tablepre'] 		= 'wp_'; //表名前缀
$_SC['wp_dbpw']			= urldecode($_SC['wp_dbpw']);

//新浪微博API
define( "WB_AKEY" , '2355268769' );
define( "WB_SKEY" , '18ac794a851997780cdf4d67d9f9f931' );

//腾讯微博API
define("MB_AKEY","050a144ca3c842a8a65f682373eefea1");
define("MB_SKEY","796c5b7ae0168e9105557dfdb16a3b3b");
define( "MB_RETURN_FORMAT" , 'json' );
define( "MB_API_HOST" , 'open.t.qq.com' );

//Facebook API
define( "FB_APPID" , '205926682768139' );
define( "FB_SECRET" , '335bd8904c54eb06bab2716a51c740eb' );

//音乐盒插件
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

//Ucenter Home安全相关
$_SC['founder'] 		= '1'; //创始人 UID, 可以支持多个创始人，之间使用 “,” 分隔。部分管理功能只有创始人才可操作。
$_SC['allowedittpl']	= 0; //是否允许在线编辑模板。为了服务器安全，强烈建议关闭

//应用的UCenter配置信息(可以到UCenter后台->应用管理->查看本应用->复制里面对应的配置信息进行替换)
define('UC_CONNECT', 'mysql'); // 连接 UCenter 的方式: mysql/NULL, 默认为空时为 fscoketopen(), mysql 是直接连接的数据库, 为了效率, 建议采用 mysql
define('UC_DBHOST', 'localhost'); // UCenter 数据库主机
define('UC_DBUSER', 'shenebwx_uc'); // UCenter 数据库用户名
define('UC_DBPW', urldecode("%75%63%40%53%68%65%4E%65%69%30%31")); // UCenter 数据库密码
define('UC_DBNAME', 'shenebwx_uc'); // UCenter 数据库名称
define('UC_DBCHARSET', 'gbk'); // UCenter 数据库字符集
define('UC_DBTABLEPRE', '`' . UC_DBNAME . '`.uc_'); // UCenter 数据库表前缀
define('UC_DBCONNECT', '0'); // UCenter 数据库持久连接 0=关闭, 1=打开
define('UC_KEY', 's7v5i0D4g425GbV0018d27Aew8v6R5k5m510t6C6J742e2ubj3d0N6b878v5W1dd'); // 与 UCenter 的通信密钥, 要与 UCenter 保持一致
define('UC_API', 'http://uc.eexx.me'); // UCenter 的 URL 地址, 在调用头像时依赖此常量
define('UC_CHARSET', 'gbk'); // UCenter 的字符集
define('UC_IP', ''); // UCenter 的 IP, 当 UC_CONNECT 为非 mysql 方式时, 并且当前应用服务器解析域名有问题时, 请设置此值
define('UC_APPID', '1'); // 当前应用的 ID
define('UC_PPP', 20);
<?php

	$_GOOGLE = array();
	
	$_GOOGLE['key'] = "ABQIAAAA3mldlaJkz7UmJAr1UbOp-BTkkbTgbt3GDptmGf1N7oGcIxRgnBQ76QU98hLWs0z4GmprcyfuT2A7sg";//你网站申请的api key
	
	$_GOOGLE['lat'] = "26.040281";//初始坐标 默认是中国的中心点
	$_GOOGLE['lng'] = "119.215736";
	
	$_GOOGLE['time'] = 15000;//1000为一秒   自动弹出窗口的间隔时间 窗口随机选择
	
	$_GOOGLE['size'] = 1;//地图加载的面积 如果宽度大于200px 建议使用1 否则使用0

	$_GOOGLE['search'] = true;//是否有搜索选项
	
	//例如 'google.php?city=北京' 那么只加载住在北京的会员
	$_GOOGLE['city'] = "google.php";//默认加载的城市会员 留空不限制 如果Google.php已经被占用 可以自行更换名称
	
?>
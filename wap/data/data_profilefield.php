<?php
if(!defined('IN_UCHOME')) exit('Access Denied');
$_SGLOBAL['profilefield']=Array
	(
	1 => Array
		(
		'fieldid' => 1,
		'title' => '宿舍',
		'formtype' => 'text',
		'maxsize' => 10,
		'required' => 1,
		'invisible' => '0',
		'allowsearch' => 1,
		'choice' => ''
		),
	2 => Array
		(
		'fieldid' => 2,
		'title' => '背景音乐',
		'formtype' => 'text',
		'maxsize' => 255,
		'required' => '0',
		'invisible' => 1,
		'allowsearch' => '0',
		'choice' => ''
		)
	)
?>
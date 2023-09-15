<?php

include_once('./common.php');






#---------------------------------------------------------------设置类----------------------------------------------------------------
class Viewcfg{
	public $cfgStopLimit=array();//系统参数开关
	//构造函数
	public function Viewcfg(){
		//系统默认模版
		$this->cfgstyle=array(
		  'allframe'=>'a:2:{s:5:"dleft";a:8:{i:0;s:7:"profile";i:1;s:7:"applist";i:2;s:10:"friendlist";i:3;s:9:"footprint";i:4;s:5:"doing";i:5;s:4:"mtag";i:6;s:5:"share";i:7;s:6:"player";}s:8:"dcontent";a:7:{i:0;s:4:"feed";i:1;s:8:"albumone";i:2;s:4:"blog";i:3;s:8:"albumall";i:4;s:6:"thread";i:5;s:11:"forumstatus";i:6;s:4:"wall";}}',
                  'frame_set'=>'lc',//
                  'effectall'=>'a:0:{}',//
                  'blockname'=>'a:0:{}',//
                  'block'=>'a:0:{}',//
                  'music'=>'a:0:{}',//
                  'cursor'=>'a:0:{}',//
		  );	
		//系统默认模版
		$this->cfgblue=array(
		  'allframe'=>'a:2:{s:5:"dleft";a:8:{i:0;s:7:"profile";i:1;s:7:"applist";i:2;s:10:"friendlist";i:3;s:9:"footprint";i:4;s:5:"doing";i:5;s:4:"mtag";i:6;s:5:"share";i:7;s:6:"player";}s:8:"dcontent";a:7:{i:0;s:4:"feed";i:1;s:8:"albumone";i:2;s:4:"blog";i:3;s:8:"albumall";i:4;s:6:"thread";i:5;s:11:"forumstatus";i:6;s:4:"wall";}}',
                  'frame_set'=>'lc',//
                  'effectall'=>'a:7:{s:10:"blocktitle";a:4:{s:16:"background-image";s:60:"background-image:url(/viewspace/img/webcolor/blue_title.jpg)";s:19:"border-bottom-color";s:27:"border-bottom-color:#89C4D4";s:16:"background-color";s:24:"background-color:#FFFFFF";s:19:"border-bottom-style";s:25:"border-bottom-style:solid";}s:13:"blocktitle|h2";a:2:{s:5:"color";s:13:"color:#101010";s:9:"font-size";s:14:"font-size:12px";}s:15:"blocktitle|em a";a:2:{s:5:"color";s:13:"color:#101010";s:9:"font-size";s:14:"font-size:12px";}s:0:"";a:2:{s:12:"border-color";s:20:"border-color:#D2D2D2";s:12:"border-width";s:16:"border-width:0px";}s:12:"blockcontent";a:3:{s:16:"background-color";s:24:"background-color:#FFFFFF";s:5:"color";s:13:"color:#000000";s:16:"background-image";s:22:"background-image:url()";}s:14:"blockcontent|a";a:1:{s:5:"color";s:13:"color:#106B32";}s:20:"blockcontent|a:hover";a:1:{s:5:"color";s:13:"color:#106B32";}}',//
                  'blockname'=>'a:0:{}',//
                  'block'=>'a:7:{s:6:"banner";a:4:{s:0:"";a:4:{s:6:"height";s:12:"height:338px";s:16:"background-image";s:84:"background-image:url(/viewspace/img/webcolor/14c0c99302d78bfc7c8c644e4c08a25b_b.jpg)";s:17:"background-repeat";s:24:"background-repeat:repeat";s:19:"background-position";s:28:"background-position:left top";}s:8:"title|h1";a:1:{s:5:"color";s:13:"color:#FFFFFF";}s:7:"title|p";a:1:{s:5:"color";s:13:"color:#FFFFFF";}s:9:"title|p a";a:1:{s:5:"color";s:13:"color:#FFFFFF";}}s:5:"title";a:1:{s:0:"";a:2:{s:3:"top";s:8:"top:61px";s:4:"left";s:9:"left:67px";}}s:4:"body";a:1:{s:0:"";a:3:{s:16:"background-image";s:22:"background-image:url()";s:16:"background-color";s:24:"background-color:#014E5E";s:17:"background-repeat";s:24:"background-repeat:repeat";}}s:4:"menu";a:5:{s:8:"active|a";a:1:{s:5:"color";s:13:"color:#FFD003";}s:2:"|a";a:1:{s:5:"color";s:13:"color:#FFFFFF";}s:0:"";a:3:{s:16:"background-image";s:59:"background-image:url(/viewspace/img/webcolor/blue_menu.jpg)";s:6:"height";s:11:"height:32px";s:16:"background-color";s:24:"background-color:#C7C735";}s:3:"|ul";a:1:{s:10:"margin-top";s:14:"margin-top:0px";}s:8:"|a:hover";a:1:{s:5:"color";s:13:"color:#FFD003";}}s:13:"constellation";a:0:{}s:10:"friendlist";a:0:{}s:4:"feed";a:0:{}}',//
                  'cursor'=>'a:0:{}',//
		  );	
		//系统默认模版
		$this->cfgblack=array(
		  'allframe'=>'a:2:{s:5:"dleft";a:8:{i:0;s:7:"profile";i:1;s:7:"applist";i:2;s:10:"friendlist";i:3;s:9:"footprint";i:4;s:5:"doing";i:5;s:4:"mtag";i:6;s:5:"share";i:7;s:6:"player";}s:8:"dcontent";a:7:{i:0;s:4:"feed";i:1;s:8:"albumone";i:2;s:4:"blog";i:3;s:8:"albumall";i:4;s:6:"thread";i:5;s:11:"forumstatus";i:6;s:4:"wall";}}',
                  'frame_set'=>'lc',//
                  'effectall'=>'a:7:{s:10:"blocktitle";a:2:{s:16:"background-image";s:61:"background-image:url(/viewspace/img/webcolor/black_title.jpg)";s:19:"border-bottom-color";s:27:"border-bottom-color:#414141";}s:13:"blocktitle|h2";a:2:{s:5:"color";s:13:"color:#FFFFFF";s:9:"font-size";s:14:"font-size:12px";}s:15:"blocktitle|em a";a:2:{s:5:"color";s:13:"color:#FFFFFF";s:9:"font-size";s:14:"font-size:12px";}s:0:"";a:2:{s:12:"border-color";s:20:"border-color:#414141";s:12:"border-width";s:16:"border-width:0px";}s:12:"blockcontent";a:2:{s:16:"background-color";s:24:"background-color:#959595";s:5:"color";s:13:"color:#000000";}s:14:"blockcontent|a";a:1:{s:5:"color";s:13:"color:#00585C";}s:20:"blockcontent|a:hover";a:1:{s:5:"color";s:13:"color:#00585C";}}',//
                  'blockname'=>'a:0:{}',//
                  'block'=>'a:7:{s:6:"banner";a:4:{s:0:"";a:5:{s:6:"height";s:12:"height:322px";s:16:"background-image";s:84:"background-image:url(/viewspace/img/webcolor/d8f85699047f639e55769806a969eb80_b.jpg)";s:17:"background-repeat";s:24:"background-repeat:repeat";s:16:"background-color";s:24:"background-color:#000000";s:19:"background-position";s:28:"background-position:left top";}s:8:"title|h1";a:1:{s:5:"color";s:13:"color:#B1A8A8";}s:7:"title|p";a:1:{s:5:"color";s:13:"color:#767272";}s:9:"title|p a";a:1:{s:5:"color";s:13:"color:#767272";}}s:5:"title";a:1:{s:0:"";a:2:{s:3:"top";s:9:"top:116px";s:4:"left";s:9:"left:64px";}}s:4:"body";a:1:{s:0:"";a:3:{s:16:"background-image";s:22:"background-image:url()";s:16:"background-color";s:24:"background-color:#000000";s:17:"background-repeat";s:24:"background-repeat:repeat";}}s:4:"menu";a:4:{s:8:"active|a";a:1:{s:5:"color";s:13:"color:#FFB900";}s:2:"|a";a:1:{s:5:"color";s:13:"color:#CAC8C8";}s:0:"";a:3:{s:16:"background-image";s:60:"background-image:url(/viewspace/img/webcolor/black_menu.jpg)";s:6:"height";s:11:"height:32px";s:16:"background-color";s:24:"background-color:#C7C735";}s:3:"|ul";a:1:{s:10:"margin-top";s:14:"margin-top:0px";}}s:4:"feed";a:0:{}s:13:"constellation";a:0:{}s:10:"friendlist";a:0:{}}',//
                  'cursor'=>'a:0:{}',//
		  );	
		//系统默认模版
		$this->cfggreen=array(
		  'allframe'=>'a:2:{s:5:"dleft";a:8:{i:0;s:7:"profile";i:1;s:7:"applist";i:2;s:10:"friendlist";i:3;s:9:"footprint";i:4;s:5:"doing";i:5;s:4:"mtag";i:6;s:5:"share";i:7;s:6:"player";}s:8:"dcontent";a:7:{i:0;s:4:"feed";i:1;s:8:"albumone";i:2;s:4:"blog";i:3;s:8:"albumall";i:4;s:6:"thread";i:5;s:11:"forumstatus";i:6;s:4:"wall";}}',
                  'frame_set'=>'lc',//
                  'effectall'=>'a:7:{s:10:"blocktitle";a:2:{s:16:"background-image";s:61:"background-image:url(/viewspace/img/webcolor/green_title.jpg)";s:19:"border-bottom-color";s:27:"border-bottom-color:#A4A07A";}s:13:"blocktitle|h2";a:2:{s:5:"color";s:13:"color:#FFFFFF";s:9:"font-size";s:14:"font-size:12px";}s:15:"blocktitle|em a";a:2:{s:5:"color";s:13:"color:#FFFFFF";s:9:"font-size";s:14:"font-size:12px";}s:0:"";a:2:{s:12:"border-color";s:20:"border-color:#414141";s:12:"border-width";s:16:"border-width:0px";}s:12:"blockcontent";a:3:{s:16:"background-color";s:24:"background-color:#F3FED3";s:5:"color";s:13:"color:#000000";s:16:"background-image";s:22:"background-image:url()";}s:14:"blockcontent|a";a:1:{s:5:"color";s:13:"color:#106B32";}s:20:"blockcontent|a:hover";a:1:{s:5:"color";s:13:"color:#106B32";}}',//
                  'blockname'=>'a:0:{}',//
                  'block'=>'a:7:{s:6:"banner";a:4:{s:0:"";a:4:{s:6:"height";s:12:"height:281px";s:16:"background-image";s:84:"background-image:url(/viewspace/img/webcolor/24a0912509687cbe0d0e5c52c7dc8fe2_b.jpg)";s:17:"background-repeat";s:24:"background-repeat:repeat";s:19:"background-position";s:28:"background-position:left top";}s:8:"title|h1";a:1:{s:5:"color";s:13:"color:#030000";}s:7:"title|p";a:1:{s:5:"color";s:13:"color:#FFFFFF";}s:9:"title|p a";a:1:{s:5:"color";s:13:"color:#FFFFFF";}}s:5:"title";a:1:{s:0:"";a:2:{s:3:"top";s:8:"top:97px";s:4:"left";s:9:"left:74px";}}s:4:"body";a:1:{s:0:"";a:3:{s:16:"background-image";s:22:"background-image:url()";s:16:"background-color";s:24:"background-color:#022101";s:17:"background-repeat";s:24:"background-repeat:repeat";}}s:4:"menu";a:5:{s:8:"active|a";a:1:{s:5:"color";s:13:"color:#000000";}s:2:"|a";a:1:{s:5:"color";s:13:"color:#FFFFFF";}s:0:"";a:3:{s:16:"background-image";s:60:"background-image:url(/viewspace/img/webcolor/green_menu.jpg)";s:6:"height";s:11:"height:32px";s:16:"background-color";s:24:"background-color:#C7C735";}s:3:"|ul";a:1:{s:10:"margin-top";s:14:"margin-top:0px";}s:8:"|a:hover";a:1:{s:5:"color";s:13:"color:#000000";}}s:4:"feed";a:0:{}s:13:"constellation";a:0:{}s:10:"friendlist";a:0:{}}',//
                  'cursor'=>'a:0:{}',//
		  );	
		//系统默认模版
		$this->cfgpink=array(
		  'allframe'=>'a:2:{s:5:"dleft";a:8:{i:0;s:7:"profile";i:1;s:7:"applist";i:2;s:10:"friendlist";i:3;s:9:"footprint";i:4;s:5:"doing";i:5;s:4:"mtag";i:6;s:5:"share";i:7;s:6:"player";}s:8:"dcontent";a:7:{i:0;s:4:"feed";i:1;s:8:"albumone";i:2;s:4:"blog";i:3;s:8:"albumall";i:4;s:6:"thread";i:5;s:11:"forumstatus";i:6;s:4:"wall";}}',
                  'frame_set'=>'lc',//
                  'effectall'=>'a:7:{s:10:"blocktitle";a:2:{s:16:"background-image";s:60:"background-image:url(/viewspace/img/webcolor/pink_title.jpg)";s:19:"border-bottom-color";s:27:"border-bottom-color:#A4A07A";}s:13:"blocktitle|h2";a:2:{s:5:"color";s:13:"color:#FFFFFF";s:9:"font-size";s:14:"font-size:12px";}s:15:"blocktitle|em a";a:2:{s:5:"color";s:13:"color:#FFFFFF";s:9:"font-size";s:14:"font-size:12px";}s:0:"";a:3:{s:12:"border-color";s:20:"border-color:#414141";s:12:"border-width";s:16:"border-width:0px";s:16:"background-image";s:60:"background-image:url(/viewspace/img/webcolor/pink_title.jpg)";}s:12:"blockcontent";a:3:{s:16:"background-color";s:24:"background-color:#F3FED3";s:5:"color";s:13:"color:#000000";s:16:"background-image";s:22:"background-image:url()";}s:14:"blockcontent|a";a:1:{s:5:"color";s:13:"color:#106B32";}s:20:"blockcontent|a:hover";a:1:{s:5:"color";s:13:"color:#106B32";}}',//
                  'blockname'=>'a:0:{}',//
                  'block'=>'a:7:{s:6:"banner";a:4:{s:0:"";a:5:{s:6:"height";s:12:"height:212px";s:16:"background-image";s:84:"background-image:url(/viewspace/img/webcolor/364290cd6494595fa366a58a6a0b0d54_b.gif)";s:17:"background-repeat";s:24:"background-repeat:repeat";s:16:"background-color";s:28:"background-color:transparent";s:19:"background-position";s:28:"background-position:left top";}s:8:"title|h1";a:1:{s:5:"color";s:13:"color:#B10B6F";}s:7:"title|p";a:1:{s:5:"color";s:13:"color:#939393";}s:9:"title|p a";a:1:{s:5:"color";s:13:"color:#939393";}}s:5:"title";a:1:{s:0:"";a:2:{s:3:"top";s:8:"top:59px";s:4:"left";s:9:"left:58px";}}s:4:"body";a:1:{s:0:"";a:3:{s:16:"background-image";s:22:"background-image:url()";s:16:"background-color";s:24:"background-color:#9E0A46";s:17:"background-repeat";s:24:"background-repeat:repeat";}}s:4:"menu";a:5:{s:8:"active|a";a:1:{s:5:"color";s:13:"color:#000000";}s:2:"|a";a:1:{s:5:"color";s:13:"color:#FFFFFF";}s:0:"";a:3:{s:16:"background-image";s:59:"background-image:url(/viewspace/img/webcolor/pink_menu.jpg)";s:6:"height";s:11:"height:32px";s:16:"background-color";s:24:"background-color:#C7C735";}s:3:"|ul";a:1:{s:10:"margin-top";s:14:"margin-top:0px";}s:8:"|a:hover";a:1:{s:5:"color";s:13:"color:#000000";}}s:4:"feed";a:0:{}s:13:"constellation";a:0:{}s:10:"friendlist";a:0:{}}',//
                  'cursor'=>'a:0:{}',//
		  );	
		//系统默认模版
		$this->cfgwhite=array(
		  'allframe'=>'a:2:{s:5:"dleft";a:8:{i:0;s:7:"profile";i:1;s:7:"applist";i:2;s:10:"friendlist";i:3;s:9:"footprint";i:4;s:5:"doing";i:5;s:4:"mtag";i:6;s:5:"share";i:7;s:6:"player";}s:8:"dcontent";a:7:{i:0;s:4:"feed";i:1;s:8:"albumone";i:2;s:4:"blog";i:3;s:8:"albumall";i:4;s:6:"thread";i:5;s:11:"forumstatus";i:6;s:4:"wall";}}',
                  'frame_set'=>'lc',//
                  'effectall'=>'a:7:{s:10:"blocktitle";a:4:{s:16:"background-image";s:22:"background-image:url()";s:19:"border-bottom-color";s:27:"border-bottom-color:#D2D2D2";s:16:"background-color";s:24:"background-color:#FFFFFF";s:19:"border-bottom-style";s:26:"border-bottom-style:dashed";}s:13:"blocktitle|h2";a:2:{s:5:"color";s:13:"color:#101010";s:9:"font-size";s:14:"font-size:12px";}s:15:"blocktitle|em a";a:2:{s:5:"color";s:13:"color:#101010";s:9:"font-size";s:14:"font-size:12px";}s:0:"";a:3:{s:12:"border-color";s:20:"border-color:#D2D2D2";s:12:"border-width";s:16:"border-width:1px";s:16:"background-image";s:60:"background-image:url(/viewspace/img/webcolor/pink_title.jpg)";}s:12:"blockcontent";a:3:{s:16:"background-color";s:24:"background-color:#FFFFFF";s:5:"color";s:13:"color:#000000";s:16:"background-image";s:22:"background-image:url()";}s:14:"blockcontent|a";a:1:{s:5:"color";s:13:"color:#106B32";}s:20:"blockcontent|a:hover";a:1:{s:5:"color";s:13:"color:#106B32";}}',//
                  'blockname'=>'a:0:{}',//
                  'block'=>'a:7:{s:6:"banner";a:4:{s:0:"";a:5:{s:6:"height";s:12:"height:210px";s:16:"background-image";s:84:"background-image:url(/viewspace/img/webcolor/7c9c93c2309baf7d5f6e940104bfef29_b.jpg)";s:17:"background-repeat";s:24:"background-repeat:repeat";s:16:"background-color";s:24:"background-color:#FFFFFF";s:19:"background-position";s:28:"background-position:left top";}s:8:"title|h1";a:1:{s:5:"color";s:13:"color:#030000";}s:7:"title|p";a:1:{s:5:"color";s:13:"color:#656464";}s:9:"title|p a";a:1:{s:5:"color";s:13:"color:#656464";}}s:5:"title";a:1:{s:0:"";a:2:{s:3:"top";s:8:"top:84px";s:4:"left";s:9:"left:67px";}}s:4:"body";a:1:{s:0:"";a:3:{s:16:"background-image";s:22:"background-image:url()";s:16:"background-color";s:24:"background-color:#FFFFFF";s:17:"background-repeat";s:24:"background-repeat:repeat";}}s:4:"menu";a:5:{s:8:"active|a";a:1:{s:5:"color";s:13:"color:#CC6700";}s:2:"|a";a:1:{s:5:"color";s:13:"color:#484848";}s:0:"";a:3:{s:16:"background-image";s:60:"background-image:url(/viewspace/img/webcolor/white_menu.jpg)";s:6:"height";s:11:"height:32px";s:16:"background-color";s:24:"background-color:#C7C735";}s:3:"|ul";a:1:{s:10:"margin-top";s:14:"margin-top:0px";}s:8:"|a:hover";a:1:{s:5:"color";s:13:"color:#CC6700";}}s:4:"feed";a:0:{}s:13:"constellation";a:0:{}s:10:"friendlist";a:0:{}}',//
                  'cursor'=>'a:0:{}',//
		  );	
		//系统默认模版
		$this->cfgyellow=array(
		  'allframe'=>'a:2:{s:5:"dleft";a:8:{i:0;s:7:"profile";i:1;s:7:"applist";i:2;s:10:"friendlist";i:3;s:9:"footprint";i:4;s:5:"doing";i:5;s:4:"mtag";i:6;s:5:"share";i:7;s:6:"player";}s:8:"dcontent";a:7:{i:0;s:4:"feed";i:1;s:8:"albumone";i:2;s:4:"blog";i:3;s:8:"albumall";i:4;s:6:"thread";i:5;s:11:"forumstatus";i:6;s:4:"wall";}}',
                  'frame_set'=>'lc',//
                  'effectall'=>'a:7:{s:10:"blocktitle";a:4:{s:16:"background-image";s:62:"background-image:url(/viewspace/img/webcolor/yellow_title.jpg)";s:19:"border-bottom-color";s:27:"border-bottom-color:#AA9C79";s:16:"background-color";s:24:"background-color:#FFFFFF";s:19:"border-bottom-style";s:25:"border-bottom-style:solid";}s:13:"blocktitle|h2";a:2:{s:5:"color";s:13:"color:#FFFFFF";s:9:"font-size";s:14:"font-size:12px";}s:15:"blocktitle|em a";a:2:{s:5:"color";s:13:"color:#FFFFFF";s:9:"font-size";s:14:"font-size:12px";}s:0:"";a:3:{s:12:"border-color";s:20:"border-color:#D2D2D2";s:12:"border-width";s:16:"border-width:0px";s:16:"background-image";s:60:"background-image:url(/viewspace/img/webcolor/pink_title.jpg)";}s:12:"blockcontent";a:3:{s:16:"background-color";s:24:"background-color:#FFF5D2";s:5:"color";s:13:"color:#000000";s:16:"background-image";s:22:"background-image:url()";}s:14:"blockcontent|a";a:1:{s:5:"color";s:13:"color:#106B32";}s:20:"blockcontent|a:hover";a:1:{s:5:"color";s:13:"color:#106B32";}}',//
                  'blockname'=>'a:0:{}',//
                  'block'=>'a:7:{s:6:"banner";a:4:{s:0:"";a:4:{s:6:"height";s:12:"height:205px";s:16:"background-image";s:84:"background-image:url(/viewspace/img/webcolor/4d8ecb6e13db59de01c4d6e931084d7e_b.jpg)";s:17:"background-repeat";s:24:"background-repeat:repeat";s:19:"background-position";s:28:"background-position:left top";}s:8:"title|h1";a:1:{s:5:"color";s:13:"color:#FFFFFF";}s:7:"title|p";a:1:{s:5:"color";s:13:"color:#FFFFFF";}s:9:"title|p a";a:1:{s:5:"color";s:13:"color:#FFFFFF";}}s:5:"title";a:1:{s:0:"";a:2:{s:3:"top";s:8:"top:33px";s:4:"left";s:9:"left:59px";}}s:4:"body";a:1:{s:0:"";a:3:{s:16:"background-image";s:22:"background-image:url()";s:16:"background-color";s:24:"background-color:#735111";s:17:"background-repeat";s:24:"background-repeat:repeat";}}s:4:"menu";a:5:{s:8:"active|a";a:1:{s:5:"color";s:13:"color:#000000";}s:2:"|a";a:1:{s:5:"color";s:13:"color:#FFFFFF";}s:0:"";a:3:{s:16:"background-image";s:61:"background-image:url(/viewspace/img/webcolor/yellow_menu.jpg)";s:6:"height";s:11:"height:32px";s:16:"background-color";s:24:"background-color:#C7C733";}s:3:"|ul";a:1:{s:10:"margin-top";s:14:"margin-top:0px";}s:8:"|a:hover";a:1:{s:5:"color";s:13:"color:#000000";}}s:13:"constellation";a:0:{}s:10:"friendlist";a:0:{}s:4:"feed";a:0:{}}',//
                  'cursor'=>'a:0:{}',//
		  );	

	}
}




//返回用户名
function getUserName($uid=0)
{
$space=getspace($uid);


return $space[username];
}


#----------------------------------------------------------用户CSS类-----------------------------------------------------------------------
class  Usercssabouta{

//构造函数
public function Usercssabouta($uid,$myuid,$dii=1){
$sql="select * from ".tname('app_viewspace_suser')."  where uid=$uid and status=1";
$value=getSqlResult($sql);
$allframea=array();
$effectall=array();
$blockname=array();
$music=array();
$block=array();

if($value[allframe]=="")
{
$Cfg=new Viewcfg();
$cfgstyle=$Cfg->cfgstyle;

$frame_set=$cfgstyle[frame_set];
$allframea=unserialize($cfgstyle[allframe]);
$effectall=unserialize($cfgstyle[effectall]);
$blockname=unserialize($cfgstyle[blockname]);

$block=unserialize($cfgstyle[block]);
}
else
{
$frame_set=$value[frame_set];
$allframea=unserialize($value[allframe]);
$effectall=unserialize($value[effectall]);
$blockname=unserialize($value[blockname]);
$block=unserialize($value[block]);
}

$style=array(
                 'frame_set'=>$frame_set,
		 'allframe'=>$allframea,
		 'effectall'=>$effectall,
		 'blockname'=>$blockname,
		 'music'=>$music,
		 'block'=>$block,
                );

$this->uid=$value[uid];
$this->tempstyle=serialize($style);
$this->defaultset=$frame_set;
$this->wrap=strlen($frame_set)==2?wraptwo:wrap;
$this->date=$value[date];
}
}


#----------------------------------------------------------用户CSS类-----------------------------------------------------------------------
class  Usercssinfo{

//构造函数
public function Usercssinfo($uid,$myuid,$dii=1){
$sql="select *,(select smusic from ".tname('space')." where uid=$uid) as smusic from ".tname('app_viewspace_suser')."  where uid=$uid and status=1";
$value=getSqlResult($sql);
$allframea=array();
$effectall=array();
$blockname=array();
$music=array();
$block=array();

if($value[allframe]=="")
{
$Cfg=new Viewcfg();
$cfgstyle=$Cfg->cfgstyle;

$frame_set=$cfgstyle[frame_set];
$allframea=unserialize($cfgstyle[allframe]);
$effectall=unserialize($cfgstyle[effectall]);
$blockname=unserialize($cfgstyle[blockname]);

$block=unserialize($cfgstyle[block]);
}
else
{
$frame_set=$value[frame_set];
$allframea=unserialize($value[allframe]);
$effectall=unserialize($value[effectall]);
$blockname=unserialize($value[blockname]);
$music=unserialize($value[smusic]);
$block=unserialize($value[block]);
$cursor=unserialize($value[cursor]);
}

$style=array(
                 'frame_set'=>$frame_set,
		 'allframe'=>$allframea,
		 'effectall'=>$effectall,
		 'blockname'=>$blockname,
		 'music'=>$music,
		 'block'=>$block,
		 'cursor'=>$cursor,
                );

$this->uid=$value[uid];
$this->allframe=alloutframe($uid,$myuid,$dii,$allframea,$frame_set);
$this->tempstyle=serialize($style);
$this->defaultset=$frame_set;
$this->wrap=strlen($frame_set)==2?wraptwo:wrap;
$this->date=$value[date];
}
}



#----------------------------------------------------------用户修改CSS类-----------------------------------------------------------------------



class  Usercssinfodiy{

//构造函数
public function Usercssinfodiy($uid,$myuid,$dii=2){
$sql="select *,(select smusic from ".tname('space')." where uid=$uid) as smusic from ".tname('app_viewspace_suser')."  where uid=$uid and status=1";
$value=getSqlResult($sql);
$allframea=array();
$effectall=array();
$blockname=array();
$music=array();
$block=array();

if($value[allframe]=="")
{
$Cfg=new Viewcfg();
$cfgstyle=$Cfg->cfgstyle;

$frame_set=$cfgstyle[frame_set];
$allframea=unserialize($cfgstyle[allframe]);
$effectall=unserialize($cfgstyle[effectall]);
$blockname=unserialize($cfgstyle[blockname]);
$block=unserialize($cfgstyle[block]);
}
else
{
$frame_set=$value[frame_set];
$allframea=unserialize($value[allframe]);
$effectall=unserialize($value[effectall]);
$blockname=unserialize($value[blockname]);
if($value[smusic]=='')
{
$value[smusic]='a:0:{}';
}
$music=unserialize($value[smusic]);
$block=unserialize($value[block]);
$cursor=unserialize($value[cursor]);
}

$style=array(
                 'frame_set'=>$frame_set,
		 'allframe'=>$allframea,
		 'effectall'=>$effectall,
		 'blockname'=>$blockname,
		 'music'=>$music,
		 'block'=>$block,
		 'cursor'=>$cursor,
                );

$this->uid=$value[uid];
$this->allframe=alloutframe($uid,$myuid,$dii,$allframea,$frame_set);
$this->tempstyle=serialize($style);
$this->defaultset=$frame_set;
$this->wrap=strlen($frame_set)==2?wraptwo:wrap;
$this->date=$value[date];
}
}


#---------------------------------------------------------------------------------------------------------------------------------------------

function get_tyle($uid, $type = false)
{
	$temp = query_one("select * from ".tname('app_viewspace_suser')." where uid = $uid ");
	return $temp;
}


function get_share($id=0)
{
	$temp = query_one("select * from ".tname('app_viewspace_getshare')." where id = $id ");
	return $temp;
}
function Usercssabout($uid=0)
{
	$temp = query_one("select * from ".tname('app_viewspace_suser')." where uid=$uid and status=1 ");
	return $temp;
}
function get_suser($id)
{
	$temp = query_one("select * from ".tname('app_viewspace_suser')." where id =$id ");
	return $temp;
}

function stylecount($uid=0)
{
global $_SGLOBAL;
$sql="select count(*) as intNum from ".tname('app_viewspace_suser')." where uid='$uid' ";
$query =$_SGLOBAL['db']->query($sql);	
$intNum = $_SGLOBAL['db']->result($query,0);	  
return $intNum;
}

#----------------------------------------------------------用户修改记录CSS类-----------------------------------------------------------------------
function frame_set1($data,$level){   
      foreach($data as  $key=>$value){   
             if($key=='frame_set')
              {
              $spaceset=$value; 
              }
      }
return $spaceset;
}
function allframe1($data,$level){ 
$spaceset=array();  
      foreach($data as  $key=>$value){   
             if($key=='allframe')
              {
              $spaceset=$value; 
              }
      }
return $spaceset;
}
function effectall11($data,$level){
$spaceset=array();     
      foreach($data as  $key=>$value){   
             if($key=='effectall')
              {
              $spaceset=$value; 
              }
      }
return $spaceset;
}
function blockname1($data,$level){
$spaceset=array();    
      foreach($data as  $key=>$value){   
             if($key=='blockname')
              {
              $spaceset=$value; 
              }
      }
return $spaceset;
}
function music1($data,$level){
$spaceset=array();     
      foreach($data as  $key=>$value){   
             if($key=='music')
              {
              $spaceset=$value; 
              }
      }
return $spaceset;
}
function block11($data,$level){
$spaceset=array();     
      foreach($data as  $key=>$value){   
             if($key=='block')
              {
              $spaceset=$value; 
              }
      }
return $spaceset;
}
function cursor1($data,$level){
$spaceset=array();     
      foreach($data as  $key=>$value){   
             if($key=='cursor')
              {
              $spaceset=$value; 
              }
      }
return $spaceset;
}


#---------------------------------------------------------------------------------------------------------------------------------------------

function alloutframe($uid=0,$myuid=0,$dii=1,$style,$set)
{

switch($set) {
 case 'cl':
    $all.= outframedcontent($uid,$myuid,$style,$style,$dii,1,$set);
    $all.= outframedleft($uid,$myuid,$style,1);
    break;
 case 'lc':
    $all.= outframedleft($uid,$myuid,$style,1);
    $all.= outframedcontent($uid,$myuid,$style,$style,$dii,1,$set);
    break;
 case 'lcr':

    $all.= outframedleft($uid,$myuid,$style,1);
    $all.= outframedcontent($uid,$myuid,$style,$style,$dii,1,$set);
    $all.= outframedright($uid,$myuid,$style,1);
    break;
 case 'lrc':
    $all.= outframedleft($uid,$myuid,$style,1);
    $all.= outframedright($uid,$myuid,$style,1);
    $all.= outframedcontent($uid,$myuid,$style,$style,$dii,1,$set);
    break;
 case 'clr':
    $all.= outframedcontent($uid,$myuid,$style,$style,$dii,1,$set);
    $all.= outframedleft($uid,$myuid,$style,1);
    $all.= outframedright($uid,$myuid,$style,1);
    break;
}
return $all;

}


function outframedcontent($uid=0,$myuid=0,$data,$st,$dii,$level,$set){ 
foreach($data as  $key=>$value){   
             if($key=='dcontent')
              {
              $dcontent=dcontent($uid,$myuid,$st,$dii,$value,$set); 
              break ;
              }
              else
              {
              $ac=outframedcontent($uid,$myuid,$value,$st,$dii,$level++,$set);
              $dcontent.="$ac"; 
              }

}
return $dcontent;
}

function outframedleft($uid=0,$myuid=0,$data,$level){   
      foreach($data as  $key=>$value){   
             if($key=='dleft')
              {
              $dleft=dleft($uid,$myuid,$value); 
              break ;
              }
              else
              {
              $al=outframedleft($uid,$myuid,$value,$level++);
              $dleft.="$al"; 
              }
      }

return $dleft;
}
function outframedright($uid=0,$myuid=0,$data,$level){   
      foreach($data as  $key=>$value){   
             if($key=='dright')
              {
              $dright=dright($uid,$myuid,$value); 
              break ;
              }
              else
              {
              $ar=outframedright($uid,$myuid,$value,$level++);
              $dright.="$ar"; 
              }
      }
return $dright;
}

function dleft($uid=0,$myuid=0,$data)
{
$spacel.="<div id=\"dleft\" class=\"frame_1\">";
foreach($data as  $key=>$value)
{
$dd=$value;
$spacel.=$dd($uid,$myuid);
}
$spacel.="</div>";
return $spacel;
}

function dcontent($uid=0,$myuid=0,$st,$dii,$data,$set)
{
$frame=strlen($set)==2?frame_3:frame_2;
$spacec.="<div id=\"dcontent\" class=\"$frame\">";
$display='style="display:none"';
if($dii==2)
{
$st=serialize($st);
if(!strpos($st,"friendlist"))
{
$spacec.=friendlist($uid,$myuid,$display);
}
if(!strpos($st,"doing"))
{
$spacec.=doing($uid,$myuid,$display);
}
if(!strpos($st,"albumone"))
{
$spacec.=albumone($uid,$myuid,$display);
}
if(!strpos($st,"albumall"))
{
$spacec.=albumall($uid,$myuid,$display);
}
if(!strpos($st,"feed"))
{
$spacec.=feed($uid,$myuid,$display);
}

if(!strpos($st,"blog"))
{
$spacec.=blog($uid,$myuid,$display);
}

if(!strpos($st,"share"))
{
$spacec.=share($uid,$myuid,$display);
}
if(!strpos($st,"mtag"))
{
$spacec.=mtag($uid,$myuid,$display);
}
if(!strpos($st,"thread"))
{
$spacec.=thread($uid,$myuid,$display);
}
if(!strpos($st,"forumstatus"))
{
$spacec.=forumstatus($uid,$myuid,$display);
}

if(!strpos($st,"footprint"))
{
$spacec.=footprint($uid,$myuid,$display);
}

if(!strpos($st,"player"))
{
$spacec.=player($uid,$myuid,$display);
}














if(!strpos($st,"wall"))
{
$spacec.=wall($uid,$myuid,$display);
}
}

foreach($data as  $key=>$value)
{
$dd=$value;
$spacec.=$dd($uid,$myuid);
}
$spacec.="</div>";
return $spacec;
}

function dright($uid,$myuid,$data)
{
$spacer.="<div id=\"dright\" class=\"frame_1\">";
foreach($data as  $key=>$value)
{
$dd=$value;
$spacer.=$dd($uid,$myuid);
}
$spacer.="</div>";
return $spacer;
}


function outframeeffectall($data,$level){   
      foreach($data as  $key=>$value){   
             if($key=='effectall')
              {
              $effectall=effectall1($value); 
              }
              else
              {
              $ab=outframeeffectall($value,$level++);
              $effectall.="$ab"; 
              }
      }
return $effectall;
}

function outframeblock($data,$level){   
      foreach($data as  $key=>$value){   
             if($key=='block')
              {
              $block=block1($value); 
              }
              else
              {
              $ab=outframeblock($value,$level++);
              $block.="$ab"; 
              }
      }
return $block;
}

function outframecursor($data,$level){   
      foreach($data as  $key=>$value){   
             if($key=='cursor')
              {
              $cursor=" body {cursor:$value }"; 
              }
              else
              {
              $ab=outframecursor($value,$level++);
              $cursor.="$ab"; 
              }
      }
return $cursor;
}

function effectall1($data)
{
$spacec1=".block ";
foreach($data as $ckey=>$cvalue){ 
$ckey=strtr($ckey,"|", " ");     
if($ckey!="")
$spacec.="$spacec1 .$ckey {";
else
$spacec.="$spacec1 {";

foreach($cvalue as $cckey=>$ccvalue){  
$spacec.="$ccvalue; ";
}
$spacec.="} 
";
}
return $spacec;
}


function block1($data)
{
foreach($data as  $key=>$value){
if($key=="body")
$spaceb1="$key";
else
{
$spaceb1="#$key";
}
foreach($value as $ckey=>$cvalue){ 
$ckey=strtr($ckey,"|", " ");     
if($ckey==" a" || $ckey==" ul")
{$spaceb.="$spaceb1$ckey {";}
elseif($ckey!="")
{$spaceb.="$spaceb1 .$ckey {";}
else
{$spaceb.="$spaceb1 {";}

foreach($cvalue as $cckey=>$ccvalue){  
$spaceb.="$ccvalue; ";
}
$spaceb.="} 
";

}
}
return $spaceb;
}



function profile($uid=0,$myuid=0,$display='')
{
//是否在线
$isonline = 0;
global $_SGLOBAL;
$isonline = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT lastactivity FROM ".tname('session')." WHERE uid='$uid'"), 0);

$space = getspace($uid, 'uid', 0);

//VIP会员
if (!empty($space[uid])) {
	$uvips = ckvip($space[uid]);
}

//是否好友
$space['isfriend'] = $space['self'];
if($space['friends'] && in_array($myuid, $space['friends'])) {
	$space['isfriend'] = 1;//是好友
}

//红包道具
$space['magiccredit'] = 0;
if($_SGLOBAL['magic']['gift'] && $myuid) {
	$query = $_SGLOBAL['db']->query('SELECT * FROM '.tname('magicuselog')." WHERE uid='$uid' AND mid='gift' LIMIT 1");
	if($value = $_SGLOBAL['db']->fetch_array($query)) {
		$data = empty($value['data'])?array():unserialize($value['data']);
		if($data['left'] <= 0) {
			$_SGLOBAL['db']->query('DELETE FROM '.tname('magicuselog')." WHERE uid = '$myuid' AND mid = 'gift'");
		}
		if(!$data['receiver'] || !in_array($myuid, $data['receiver'])) {
			$space['magiccredit'] = $data['left'] >= $data['chunk'] ? $data['chunk'] : $data['left'];
		}
	}
}


$space['creditstar'] = getstar($space['credit']);
$profile.="<div id=\"profile\" class=\"block\" $display>
<div class=\"blocktitle\">
<em>\n";
if($space[self] &&$_SGLOBAL[magic][gift])
{
if($space['magiccredit'])
{
$profile.="<a id=\"a_magic_retrieve\" href=\"cp.php?ac=magic&op=retrieve\" onclick=\"ajaxmenu(event,this.id)\" title=\"回收埋下的积分\" class=\"hi\"><img src=\"viewspace/img/tool/h_y.gif\" style=\"width:15px;height:15px;margin-right:5px;vertical-align: middle;\" />回收积分</a>\n";
}
else
{
$profile.="<a id=\"a_magic_gift\" href=\"magic.php?mid=gift\" onclick=\"ajaxmenu(event,this.id,1)\" title=\"给来访者埋个红包\" class=\"hi\"><img src=\"viewspace/img/tool/h_y.gif\" style=\"width:15px;height:15px;margin-right:5px;vertical-align: middle;\" />埋个红包</a>\n";
}
}
if($myuid!=$uid)
{
$profile.="<a id=\"a_magic_viewmagiclog\" href=\"magic.php?mid=viewmagiclog&idtype=uid&id=$uid\" onclick=\"ajaxmenu(event,this.id,1)\"><img src=\"image/magic/viewmagiclog.small.gif\" title=\"八卦镜\" alt=\"八卦镜\" style=\"vertical-align: middle;\" /></a>
<a id=\"a_magic_viewmagic\" href=\"magic.php?mid=viewmagic&idtype=uid&id=$uid\" onclick=\"ajaxmenu(event,this.id,1)\"><img src=\"image/magic/viewmagic.small.gif\" title=\"透视镜\" alt=\"透视镜\"  style=\"vertical-align: middle;\" /></a>
<a id=\"a_magic_viewvisitor\" href=\"magic.php?mid=viewvisitor&idtype=uid&id=$uid\" onclick=\"ajaxmenu(event,this.id,1)\"><img src=\"image/magic/viewvisitor.small.gif\" title=\"偷窥镜\" alt=\"偷窥镜\" style=\"vertical-align: middle;\" /></a>\n";
if($space['magiccredit'])
{
$profile.="<a id=\"a_magic_gift\" href=\"cp.php?&ac=magic&op=receive&uid=$space[uid]\" onclick=\"ajaxmenu(event, this.id)\" title=\"送你 $space[magiccredit] 积分大红包\" class=\"hi\"><img src=\"viewspace/img/tool/h_y.gif\" style=\"width:15px;height:15px;margin-right:5px;vertical-align: middle;\" />送你 $space[magiccredit] 积分</a>\n";
}
}
$profile.="</em>
<h2>个人资料</h2>
</div>
<div class=\"blockcontent\">
<div class=\"userinfo\">\n";
$uimg=avatar($uid,'big');
$profile.="<a target=\"mainyinyue\" href=\"javascript:;\" onclick=\"javascript:spaceCategory('viewspace.php?uid=$space[uid]&do=aboutme', 'aboutme');\" class=\"avatar\">$uimg</a>";
if($space[spacenote]){$profile.="<center>".$space[spacenote]." <a href='space.php?uid=$space[uid]&do=doing'>&raquo;</a></center>";}
$profile.="<div class=\"username\">\n";
$profile.= $myuid==$uid?"<p class=\"avatar_op\"><a href=\"cp.php?ac=avatar\">上传头像</a></p>\n":"";
if($space[name])
{
$profile.="<p>实名：
<a href=\"space.php?uid=$space[uid]\">$space[name]</a></p>\n";
}
else
{
$profile.="<p>实名：未填写</p>\n";
}
$profile.= $isonline!=0?"<p class=\"onlineU\" title=\"在线\">昵称：<a target=\"mainyinyue\" href=\"javascript:;\" onclick=\"javascript:spaceCategory('viewspace.php?uid=$space[uid]', '');\">$space[username]</a></p>\n":"<p>昵称：<a target=\"mainyinyue\" href=\"javascript:;\" onclick=\"javascript:spaceCategory('viewspace.php?uid=$space[uid]', '');\">$space[username]</a></p>\n";
$profile.= $myuid!=$uid?"<p>等级：<a href=\"do.php?ac=ajax&op=credit&uid=$space[uid]\" id=\"a_space_view\" onclick=\"ajaxmenu(event, this.id, 99999)\">$space[creditstar]</a></p>\n":"<p>等级：<a href=\"cp.php?ac=credit\" target=\"blank\">$space[creditstar]</a></p>\n";
$profile.="<p>荣誉：\n";
if($space[videostatus])
{
$profile.="<a href=\"space.php?uid=$space[uid]&do=videophoto\" id=\"a_space_videophoto\" onclick=\"ajaxmenu(event, this.id, 1)\"><img src=\"viewspace/img/tool/video.gif\" align=\"absmiddle\" title=\"已通过视频认证\" alt=\"已通过视频认证\" /></a>\n";
}
else
{
$profile.= $myuid!=$uid?"<img src=\"viewspace/img/tool/video_off.gif\" align=\"absmiddle\" title=\"未视频认证用户\" alt=\"未视频认证用户\"/>\n":"<a href=\"cp.php?ac=videophoto\"><img src=\"viewspace/img/tool/video_off.gif\" align=\"absmiddle\" title=\"未视频认证用户\" alt=\"未视频认证用户\"/></a>\n";
}
if($space[groupid]==3){
$profile.="<a href='pay.php?ac=vip'><img src='image/pay/vip.gif' title='VIP会员:".($uvips[day]>0?$uvips[day].'天':'终身VIP')."' alt='VIP会员' border='0' align='absmiddle' /></a>\n";
}else{
$profile.="<a href='pay.php?ac=vip' target='_blank'><img src='image/pay/vip2.gif' title='未激活' alt='未激活' border='0' align='absmiddle' /></a>\n";
}
$profile.="</p>\n";
$profile.="</div>
<div class=\"tool\">\n";
if($myuid!=$uid)
{
$profile.="<a href=\"cp.php?ac=fetion&uid=$space[uid]\" id=\"a_fetion_send\" onclick=\"ajaxmenu(event, this.id, 1)\" title=\"发短信到TA手机\" class=\"fetion\">飞信短信</a>
<a href=\"cp.php?ac=pm&uid=$space[uid]\" id=\"a_pm\" onclick=\"ajaxmenu(event, this.id, 99999, '', -1)\" title=\"发送站内短消息\" class=\"message\">发消息</a>
<a href=\"cp.php?ac=poke&op=send&uid=$space[uid]\" id=\"a_poke\" onclick=\"ajaxmenu(event, this.id, 99999, '', -1)\" title=\"打个招呼\" class=\"hi\">问候</a>
<a href=\"cp.php?ac=common&op=report&idtype=uid&id=$space[uid]\" id=\"a_report\" onclick=\"ajaxmenu(event, this.id, 99999, '', -1)\" title=\"举报此人\" class=\"report\">举报</a>\n";
if($space['isfriend'])
{
$profile.="<a href=\"userapp.php?id=1027468&my_suffix=Lw%3D%3D\" title=\"赠送礼物\" class=\"sendgift\" target=\"_blank\">送礼物</a>\n";
}
else
{
$profile.="<a href=\"cp.php?ac=friend&op=add&uid=$space[uid]\" id=\"a_friend\" onclick=\"ajaxmenu(event, this.id, 99999, '', -1)\" title=\"加为好友\" class=\"friend\">加好友</a>\n";
}
$profile.="<a target=\"mainyinyue\" href=\"javascript:;\" onclick=\"javascript:spaceCategory('viewspace.php?uid=$uid&do=wall&view=me', 'wall');\" id=\"a_comment\" title=\"给TA留言\" class=\"comment\">留言板</a>\n";
}
$profile.="</div>
</div>
</div>
</div>\n";
return $profile;
}
function friendlist($uid=0,$myuid=0,$display='')
{

$friendlist.="<div id=\"friendlist\" class=\"block\" $display>
<div class=\"blocktitle\">
<em><a target=\"mainyinyue\"  href=\"javascript:;\" onclick=\"javascript:spaceCategory('viewspace.php?uid=$uid&do=friend', 'friend');\">全部</a></em>
<h2>我的好友</h2>
</div>
<div class=\"blockcontent\">\n";


$space = getspace($uid, 'uid', 0);
$oluids = array();
$list = array();
if(ckprivacy('friend')) {

        global $_SGLOBAL,$_SN;
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('friend')." WHERE uid='$uid' AND status='1' ORDER BY num DESC, dateline DESC LIMIT 0,12");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['fuid'], $value['fusername']);
                $oluids[$value['fuid']] = $value['fuid'];
		$list[] = $value;
	}

        if($list && empty($space['friendnum'])) {
		//更新好友缓存
		include_once(S_ROOT.'./source/function_cp.php');
		friend_cache($space['uid']);
	}

//是否在线
$ols = array();
if($oluids) {
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('session')." WHERE uid IN (".simplode($oluids).")");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		if(!$value['magichidden']) {
			$ols[$value['uid']] = 1;
		} elseif($visitorlist[$value['uid']]) {
			unset($visitorlist[$value['uid']]);
		}
	}
}
if(count($list))
{
$friendlist.="<div class=\"friendlist\">
<ul>\n";
foreach ($list as $key => $value) {
//是否在线
$isonline = 0;
$isonline = $ols[$value[fuid]];
$uimg=avatar($value[fuid],'small');

realname_get();

$friendlist.="<li>
<a href=\"viewspace.php?uid=$value[fuid]\" class=\"avatar\" title=\"$value[fusername]\">
$uimg</a>\n";
$friendlist.= $isonline==0?"<p><a href=\"viewspace.php?uid=$value[fuid]\">{$_SN[$value[fuid]]}</a></p>\n":"<p class=\"onlineF\" title=\"在线\"><a href=\"viewspace.php?uid=$value[fuid]\">{$_SN[$value[fuid]]}</a></p>\n";
$friendlist.="</li>\n";
}

$friendlist.="</ul>
</div>\n";
}
else
{
$friendlist.=$uid==$myuid?"<div class=\"nomessage\"><a href=\"cp.php?ac=friend&op=find\"><img src=\"image/intro_friend.gif\" class=\"noimage\"></a>你还没有好友。<br ><a href=\"cp.php?ac=friend&op=find\">点这里寻找可能认识的人</a></div>\n":"<div class=\"nomessage\">暂时还没找到好友，<a href=\"cp.php?ac=friend&op=add&uid=$uid\" id=\"a_friend\" onclick=\"ajaxmenu(event, this.id, 99999, '', -1)\" title=\"加为好友\" class=\"friend\">加为好友</a></div>\n";
}
}
else
{
$friendlist.="<div class=\"nomessage\">用户隐私设置。</div>\n";
}

$friendlist.="</div>
</div>\n";
return $friendlist;
}
function footprint($uid=0,$myuid=0,$display='')
{

//最近访客列表
$oluids = array();
$visitorlist = array();
global $_SGLOBAL,$_SN;
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('visitor')." WHERE uid='$uid' ORDER BY dateline DESC LIMIT 0,12");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
        if($value['vusername']) {
	realname_set($value['vuid'], $value['vusername']);
	}
	$value['isfriend'] = 0;
	if($space['friends'] && in_array($value['vuid'], $space['friends'])) {
		$value['isfriend'] = 1;
	}
	$oluids[$value['vuid']] = $value['vuid'];
	$visitorlist[] = $value;
}

//是否在线
$ols = array();
if($oluids) {
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('session')." WHERE uid IN (".simplode($oluids).")");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		if(!$value['magichidden']) {
			$ols[$value['uid']] = 1;
		} elseif($visitorlist[$value['uid']]) {
			unset($visitorlist[$value['uid']]);
		}
	}
}



$footprint.="<div id=\"footprint\" class=\"block\" $display>
<div class=\"blocktitle\">
<em>\n";
if($uid!=$myuid && $_SGLOBAL[magic][superstar])
{
$footprint.="<span class=\"gray\"><img title=\"$_SGLOBAL[magic][anonymous]\" src=\"image/magic/anonymous.small.gif\"/><a id=\"a_magic_anonymous\" href=\"magic.php?mid=anonymous&idtype=uid&id=$uid\" onclick=\"ajaxmenu(event,this.id,1)\">{$_SGLOBAL[magic][anonymous]}</a></span>\n";
}
$footprint.="<a href=\"space.php?uid=$uid&do=friend&view=visitor\">全部</a></em>
<h2>脚印</h2>
</div>
<div class=\"blockcontent\">\n";


if(count($visitorlist))
{
$footprint.="<div class=\"friendlist\">
<ul>\n";
foreach ($visitorlist as $key => $value) {
//是否在线
$isonline = 0;
$isonline = $ols[$value[vuid]];
$uimg=avatar($value[vuid],'small');

$dateline=date('m-d H:i:s',$value[dateline]);

realname_get();

$footprint.="<li>\n";

if($value[vusername] == '')
{
$footprint.="<a class=\"avatar\" title=\"访问时间：$dateline\">
<img src=\"image/magic/hidden.gif\" alt=\"访问时间：$dateline\" /></a>
<p>匿名</p>\n";
}
else
{
$footprint.="<a href=\"viewspace.php?uid=$value[vuid]\" class=\"avatar\" title=\"访问时间：$dateline\">
$uimg</a>\n";
$footprint.= $isonline==0?"<p><a href=\"viewspace.php?uid=$value[vuid]\">{$_SN[$value[vuid]]}</a></p>\n":"<p class=\"onlineF\" title=\"在线\"><a href=\"viewspace.php?uid=$value[vuid]\">{$_SN[$value[vuid]]}</a></p>\n";
}
$footprint.="</li>\n";
}

$footprint.="</ul>
</div>\n";
}
else
{
$footprint.="<div class=\"nomessage\">暂时还没有人留下脚印。</div>\n";
}



$footprint.="</div></div>\n";
return $footprint;
}

function applist($uid=0,$myuid=0,$display='')
{
$applist.="<div id=\"applist\" class=\"block\" $display>
<div class=\"blocktitle\">
<em><a href=\"cp.php?ac=userapp\" target=\"_blank\">全部</a></em>
<h2>小应用</h2>
</div>
<div class=\"blockcontent\">
<ul class=\"pluglist\">\n";
//应用显示

global $_SGLOBAL;
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('userapp')." WHERE uid='$uid' ORDER BY displayorder DESC");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
$userapp[] = $value;

}
foreach ($userapp as $key => $value) {
$applist.="<li>
<a href=\"userapp.php?id=$value[appid]&uid=$space[uid]\" title=\"$value[appname]\" target=\"blank\"><img src=\"http://appicon.manyou.com/icons/$value[appid]\" alt=\"$value[appname]\" target=\"blank\">$value[appname]</a>
</li>\n";
}

$applist.="</ul>
</div>
</div>\n";
return $applist;
}

function doing($uid=0,$myuid=0,$display='')
{
$doing.="<div id=\"doing\" class=\"block\" $display>
<div class=\"blocktitle\">\n";
$doing.=$uid==$myuid?"<em><a id=\"a_doing_form\" onclick=\"ajaxmenu(event, this.id, 99999, '', -1)\" href=\"viewspace.php?do=doingf\">发表点滴</a></em>\n":"<em><a href=\"space.php?uid=$uid&do=doing&view=me\">浏览更多点滴</a></em>";
$doing.="<h2>我的点滴</h2>
</div>
<div class=\"blockcontent\">\n";


$doinglist = array();
if(ckprivacy('doing')) {
        global $_SGLOBAL;
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('doing')." WHERE uid='$uid' ORDER BY dateline DESC LIMIT 0,5");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		$doinglist[] = $value;
	}
   if(count($doinglist))
   {
   $doing.="<div class=\"diandilist\"><ul>\n";
   foreach ($doinglist as $key => $value) {
   //点滴

   $doing.="<li>&#8226; $value[message] (<a href=\"space.php?uid=$value[uid]&do=doing&doid=$value[doid]&goto=yes\">";
   $doing.= $value[replynum]==0?"回复</a>)</li>\n":"$value[replynum]个回复</a>)</li>\n";
   }
   $doing.="</ul></div>\n";
   }
   else
   {
   $doing.= $uid==$myuid?"<div class=\"nomessage\"><a href=\"space.php?do=doing&view=me\"><img src=\"image/intro_doing.gif\" class=\"noimage\"></a>你还没有记录。<a id=\"a_doing_form\" onclick=\"ajaxmenu(event, this.id, 99999, '', -1)\" href=\"viewspace.php?do=doingf\">点击这里用一句记录自己生活的点点滴滴</a></div>\n":"<div class=\"nomessage\">暂时还没有点滴。</div>";
   }

}

else
{
$doing.="<div class=\"nomessage\">用户隐私设置。</div>\n";
}




$doing.="</div></div>\n";
return $doing;
}

function player($uid=0,$myuid=0,$display='')
{
$value=query_one("select smusicp from ".tname('space')." where uid=$uid");

$player.="<div id=\"player\" class=\"block\" $display>
<div class=\"blocktitle\">
<em></em>
<h2>播放器</h2>
</div>
<div class=\"blockcontent\">
<div class=\"musicbody\">
<div class=\"music\">

<object id=\"player1\" classid=\"CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6\" width=\"0\" height=\"0\" codebase=\"nsmp2inf.cab#Version=5,1,52,701standby=Loading\" tppabs=\"http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701standby=Loading\" type=\"application/x-oleobject\">
<param name=\"URL\" value=\"\">
<param name=\"UIMode\" value=\"invisible\">
<param name=\"AutoStart\" value=\"true\">
<param name=\"Enabled\" value=\"true\">
<param name=\"enableContextMenu\" value=\"false\">
<param name=\"Volume\" value=\"100\">
<param name=\"DisplayBackColor\" value=\"100\">
<param name=\"DisplayForeColor\" value=\"100\">
<param name=\"WindowlessVideo\" value=\"true\">
</object>
<object  classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0\" width=\"200\" height=\"110\" id=\"musicSwf\">
<param name=\"movie\" value=\"viewspace/flash/$value[smusicp].swf\">
<param name=\"quality\" value=\"high\">
<param name=\"wmode\" value=\"transparent\">
<param name=\"allowScriptAccess\" value=\"always\">
</object>

</div>
</div>
</div>
</div>\n";
return $player;
}



function feed($uid=0,$myuid=0,$display='')
{
$feed.="<div id=\"feed\" class=\"block\" $display>
<div class=\"blocktitle\">
<em><a href=\"space.php?uid=$uid&do=feed\">全部</a></em>
<h2>个人新鲜事</h2>
</div>
<div class=\"blockcontent\">\n";


//个人动态
$feedlist = array();
if(ckprivacy('feed')) {
        global $_SGLOBAL;
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('feed')." WHERE uid='$uid' ORDER BY dateline DESC LIMIT 0,6");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		if(ckfriend($value)) {
			realname_set($value['uid'], $value['username']);
            $dateline=getStayTime(date('Y-m-d H:i',$value[dateline]));
			$feedlist[] = $value;
		}
	}
	$feednum = count($feedlist);

    if($feednum)
    {
    $feed.="<div class=\"feedlist\"><ul>\n";
	$_GET['view'] = 'me';
    foreach ($feedlist as $key => $value) {
    $feedarr=unserialize($value['body_data']);
    $feedlist[$key] = mkfeed($value);
	
	
     $feed.="<li id=\"feed_$value[feedid]_li\"><div style=\"width:100%;overflow:hidden;\">\n";
          if($value[uid] && $value[uid]==$myuid)
           {  
             $feed.="<a href=\"cp.php?ac=feed&op=menu&feedid=$value[feedid]\" class=\"float_del\" id=\"a_feed_$value[feedid]\" onclick=\"ajaxmenu(event, this.id)\" title=\"删除\">删除</a>\n";
           }
	       elseif($value[uid] && $space[self] && $notime)
           {
	         $feed.="<a href=\"cp.php?ac=feed&op=ignore&icon=$value[icon]&uid=$value[uid]&feedid=$value[feedid]\" id=\"a_feedicon_$value[feedid]\" onclick=\"ajaxmenu(event, this.id, 99999)\" class=\"float_cancel\" title=\"屏蔽\">屏蔽</a>";
	       }
           $feed.="<a class=\"type\" href=\"space.php?uid=$_GET[uid]&do=feed&view=$_GET[view]&appid=$value[appid]&icon=$value[icon]\" title=\"只看此类动态\"><img src=\"$value[icon_image]\" /></a>{$feedlist[$key][title_template]}\n";

                $dateline=getStayTime(date('Y-m-d H:i',$value[dateline]));
                $feed.= empty($_TPL[hidden_time])?"<span class=\"time\">$dateline</span>":"";
				
		if(empty($_TPL[hidden_menu]))
		{
		if($value['idtype']=='doid')
		{
		$feed.="(<a href=\"javascript:;\" onclick=\"docomment_get('docomment_$value[id]', 1);\" id=\"do_a_op_$value[id]\">回复</a>)\n";
		}
		elseif(in_array($value['idtype'], array('blogid','picid','sid','pid','eventid')))
		{
		$feed.="(<a href=\"javascript:;\" onclick=\"feedcomment_get($value[feedid]);\" id=\"feedcomment_a_op_$value[feedid]\">评论</a>)\n";
		}
		}
				
		$feed.= "<div class=\"feed_content\">\n";
			if($value['image_1'])
                        {
			$feed.="<a target=\"mainyinyue\"  href=\"javascript:;\" onclick=\"javascript:spaceCategory('view$value[image_1_link]', '');\"><img src=\"$value[image_1]\" class=\"summaryimg\" /></a>\n";
			}
			if($value['image_2'])
                        {
			$feed.="<a target=\"mainyinyue\"  href=\"javascript:;\" onclick=\"javascript:spaceCategory('view$value[image_2_link]', '');\"><img src=\"$value[image_2]\" class=\"summaryimg\" /></a>\n";
			}
			if($value['image_3'])
                        {
			$feed.="<a target=\"mainyinyue\"  href=\"javascript:;\" onclick=\"javascript:spaceCategory('view$value[image_3_link]', '');\"><img src=\"$value[image_3]\" class=\"summaryimg\" /></a>\n";
			}
			if($value['image_4'])
                        {
			$feed.="<a target=\"mainyinyue\"  href=\"javascript:;\" onclick=\"javascript:spaceCategory('view$value[image_4_link]', '');\"><img src=\"$value[image_4]\" class=\"summaryimg\" /></a>\n";
			}
			if($value['body_template'])
                        {
                           if($value['image_3'])
                            {
                            $image3="style=\"clear: both;\"";
                            }
			$feed.="<div class=\"detail\" $image3>{$feedlist[$key][body_template]}</div>\n";
			}

			if($value['appid']==UC_APPID)
                        {
			       if(!empty($feedarr['flashvar']))
                               {
                                 $feed.="<div class=\"media\"><img src=\"image/vd.gif\" alt=\"点击播放\" onclick=\"javascript:showFlash('{$feedarr['host']}', '{$feedarr[flashvar]}', this, '{$value['feedid']}');\" style=\"cursor:pointer;\" /></div>\n";
                               }
                               elseif(!empty($feedarr['musicvar']))
                               {
			         $feed.="<div class=\"media\"><img src=\"image/music.gif\" alt=\"点击播放\" onclick=\"javascript:showFlash('music', '{$feedarr['musicvar']}', this, '{$value['feedid']}');\" style=\"cursor:pointer;\" /></div>\n";
                               }
			       elseif(!empty($feedarr['flashaddr']))
                               {
			         $feed.="<div class=\"media\"><img src=\"image/flash.gif\" alt=\"点击查看\" onclick=\"javascript:showFlash('flash', '{$feedarr['flashaddr']}', this, '{$value['feedid']}');\" style=\"cursor:pointer;\" /></div>\n";
                               }
                        }

			if($value['body_general'])
                        {
			$feed.="<div class=\"quote\"><span class=\"q\">$value[body_general]</span></div>\n";
                        }

		$feed.="</div></div>\n";
	
    if($value['idtype']=='doid')
	{
	$feed.="<div id=\"docomment_$value[id]\" style=\"display:none;\"></div>\n";
	}
	elseif($value['idtype'])
	{
	$feed.="<div id=\"feedcomment_$value[feedid]\" style=\"display:none;\"></div>\n";
	}
	
    $feed.="</li>\n";

    }
    $feed.="</ul></div>\n";
    }
    else
    {
    $doing.="<div class=\"nomessage\">暂时还没有新鲜事。</div>\n";
    }
}
else
{
$doing.="<div class=\"nomessage\">用户隐私设置。</div>\n";
}





$feed.="</div></div>\n";
return $feed;
}

function albumone($uid=0,$myuid=0,$display='')
{
$album.="<div id=\"albumone\" class=\"block\" $display><div class=\"blocktitle\">\n";
$album.=$myuid==$uid?"<em><a href=\"cp.php?ac=upload\">上传图片</a></em>\n":"<em><a href=\"viewspace.php?uid=$uid&do=album\">浏览更多图片</a></em>";
$album.="<h2>我的相片</h2></div>
<div class=\"blockcontent\">
<style type=\"text/css\">
.marqueePic {
padding: 0 3px;
}
.marqueePic a {
display:block;
width:90px;
height:72px;
overflow:hidden;
text-decoration:none; 
}
.marqueePic a:hover {
text-decoration:none; 
}
</style>
<div class=\"photolist\" id=\"albumPicList\" style=\"overflow:hidden;width:100%;color:#ffffff;\">\n";
$piclist = array();
if(ckprivacy('album')) {
global $_SGLOBAL;
$query = $_SGLOBAL['db']->query("SELECT a.* FROM ".tname('pic')." a left join ".tname('album')." b on a.albumid=b.albumid WHERE a.uid='$uid' and friend='0' ORDER BY a.dateline DESC LIMIT 0,10");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
if(ckfriend($value)) {
$value['pic'] = pic_get($value['filepath'], $value['thumb'], $value['remote']);
$piclist[] = $value;
}
}
if(count($piclist))
{
$album.="<table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">
<tr>
<td id=\"moveList\"><table cellpadding=\"0\" cellspacing=\"0\"><tr>\n";
foreach ($piclist as $key =>$value) {
$album.="<td class=\"marqueePic\">
<a target=\"mainyinyue\" href=\"javascript:;\" onclick=\"javascript:spaceCategory('viewspace.php?uid=$uid&do=album&picid=$value[picid]', 'album');\" alt=\"$value[filename]\" style=\"background-image:url($value[pic]); background-repeat:no-repeat; background-position:center;\" > </a>
</td>\n";
}
$album.="</tr></table></td>
<td id=\"moveCopyList\"></td>
</tr>
</table>\n";
}
else
{
$album.="<div class=\"nomessage\">暂时还没有图片。</div>\n";
}
}
else
{
$album.="<div class=\"nomessage\">由于该用户的隐私设置。您无权限查看此项！</div>\n";
}
$album.="</div>\n";
$album.="<script>
var speed=8; //speed
function Marquee() {
if($('moveCopyList').offsetWidth - $('albumPicList').scrollLeft <= 0) {\n";
$album.="$('albumPicList').scrollLeft -= $('moveList').offsetWidth;\n";
$album.="} else {\n";
$album.="$('albumPicList').scrollLeft++;\n";
$album.="}\n";
$album.="}\n";
$album.="if($('albumPicList').offsetWidth < $('moveList').offsetWidth) {\n";
$album.="$('moveCopyList').innerHTML = $('moveList').innerHTML;\n";
$album.="var MyMar = setInterval(Marquee, speed);\n";
$album.="}\n";
$album.="$('albumPicList').onmouseover = function() {\n";
$album.="clearInterval(MyMar);\n";
$album.="}\n";
$album.="$('albumPicList').onmouseout = function() {\n";
$album.="MyMar=setInterval(Marquee, speed);\n";
$album.="}\n";
$album.="/*\n";
$album.=" 修正IE下背景图闪烁的BUG\n";
$album.="*/\n";
$album.="try {\n";
$album.="document.execCommand(\"BackgroundImageCache\", false, true);\n";
$album.="} catch(err) {}
</script>\n";
$album.="</div></div>\n";
return $album;
}


function blog($uid=0,$myuid=0,$display='')
{
$blog.="<div id=\"blog\" class=\"block\" $display><div class=\"blocktitle\">\n";
$blog.=$myuid==$uid?"<em><a href=\"cp.php?ac=blog\">发表博文</a></em>\n":"<em><a target=\"mainyinyue\"  href=\"javascript:;\" onclick=\"javascript:spaceCategory('viewspace.php?uid=$uid&do=blog&view=me', '');\">浏览更多博文</a></em>";
$blog.="<h2>我的博文</h2></div><div class=\"blockcontent\">\n";

//日志
$bloglist = array();
if(ckprivacy('blog')) {
        global $_SGLOBAL;
	$query = $_SGLOBAL['db']->query("SELECT *
		FROM ".tname('blog')." b
		LEFT JOIN ".tname('blogfield')." bf ON bf.blogid=b.blogid
		WHERE b.uid='$uid'
		ORDER BY b.dateline DESC");	//Modify By 01
//		ORDER BY b.dateline DESC LIMIT 0,5");
	$i=0;	//Add By 01
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
//		if(ckfriend($value)) {
		if(ckfriend($value['uid'], $value['friend'], $value['target_ids'])) {	//Modify By 01

             $value[dateline]=getStayTime(date('Y-m-d H:i',$value[dateline]));
			if($value['pic']) $value['pic'] = pic_cover_get($value['pic'], $value['picflag']);
			$value['message'] = $value['friend']==4?'':getstr($value['message'], 150, 0, 0, 0, 0, -1);
			$bloglist[] = $value;
			$i++;	//Add By 01
		}
		if($i>=5){break;}	//Add By 01
	}
	$blognum = count($bloglist);
    if($blognum)
    {
        $blog.="<div class=\"bloglist\"><ul>\n";
           foreach ($bloglist as $key => $value) {
           $magiccolor="";
           if($value[magiccolor])
            {
              $magiccolor=" class=\"magiccolor$value[magiccolor]\"";
            }
            
           $blog.="<li><h3><a  target=\"mainyinyue\"  href=\"javascript:;\"  onclick=\"javascript:spaceCategory('viewspace.php?uid=$uid&do=blog&id=$value[blogid]', '');\" $magiccolor >$value[subject]</a></h3>
		   <div class=\"info\">
		   <span class=\"times\">$value[dateline]</span> | <span class=\"tool\">评论($value[replynum]) | <a  target=\"mainyinyue\"  href=\"javascript:;\" onclick=\"javascript:spaceCategory('viewspace.php?uid=$uid&do=blog&id=$value[blogid]', '');\">浏览($value[viewnum])</a></span></div>
           <div class=\"content\">
          <p>$value[message]</p>
           </div>
          </li>\n";
           }
        
        $blog.="</ul></div>\n";
    }
   elseif($uid==$myuid)
   {
   $blog.= "<div class=\"nomessage\"><a href=\"cp.php?ac=blog\"><img src=\"image/intro_blog.gif\" class=\"noimage\"></a>你还没有博文。<a href=\"cp.php?ac=blog\">点击这里可以写下自己的第一篇博文</a></div>\n";
   }
   else
   {
        $blog.="<div class=\"nomessage\">暂时还没有博文。</div>\n";
   }
}
else
{
$album.="<div class=\"nomessage\">用户隐私设置。</div>\n";
}


$blog.="</div></div>\n";
return $blog;
}


function share($uid=0,$myuid=0,$display='')
{
$giftlist.="<div id=\"share\" class=\"block\" $display>
<div class=\"blocktitle\">\n";
$giftlist.=$myuid==$uid?"<em><a id=\"a_sharef_form\" onclick=\"ajaxmenu(event, this.id, 99999, '', -1)\" href=\"viewspace.php?do=sharef\">快速分享</a></em>\n":"<em><a href=\"space.php?uid=$uid&do=share&view=me\" target=\"_blank\">全部</a></em>";
$giftlist.="<h2>我的分享</h2>
</div>
<div class=\"blockcontent\">\n";

//个人分享
$sharelist = array();
if(ckprivacy('share')) {
        global $_SGLOBAL;
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('share')." WHERE uid='$uid' ORDER BY dateline DESC LIMIT 0,5");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		$value = mkshare($value);
		$sharelist[] = $value;
	}
   if(count($sharelist))
   {
        $giftlist.="<ul class=\"post_list line_list\">\n";
           foreach ($sharelist as $key => $value) {
                $giftlist.="<li>\n";
		$giftlist.="<div class=\"title\">
	<div class=\"r_option\"><a href=\"space.php?uid=$value[uid]&do=share&id=$value[sid]\">评论</a></div>$value[title_template]</div>
	<div class=\"feed\">\n";
		if($value['image'])
                {
		$giftlist.="<a href=\"$value[image_link]\"><img src=\"$value[image]\" class=\"summaryimg image\" alt=\"\" width=\"70\" /></a>\n";
		}
		$giftlist.="<div class=\"detail\">$value[body_template]";

		$giftlist.= 'video' == $value['type']?"<br />\n<a href=\"space.php?uid=$value[uid]&do=share&id=$value[sid]\"><img src=\"image/vd.gif\" width=\"80\" alt=\"点击查看\" /></a>\n":"";

		$giftlist.= 'music' == $value['type']?"<br />\n<a href=\"space.php?uid=$value[uid]&do=share&id=$value[sid]\"><img src=\"image/music.gif\" alt=\"点击查看\" /></a>\n":"";

		$giftlist.= 'flash' == $value['type']?"<br />\n<a href=\"space.php?uid=$value[uid]&do=share&id=$value[sid]\"><img src=\"image/flash.gif\" alt=\"点击查看\" /></a>\n":"";

		$giftlist.="</div>
		<div class=\"quote\"><span id=\"quote\" class=\"q\">$value[body_general]</span></div>
		</div>
		</li>\n";
          }
        $giftlist.="</ul>\n";
   }
   elseif($uid==$myuid)
   {
   $giftlist.= "<div class=\"nomessage\"><a href=\"space.php?do=share&view=me\"><img src=\"image/intro_share.gif\" class=\"noimage\"></a>你还没有分享。<a id=\"a_sharef_form\" onclick=\"ajaxmenu(event, this.id, 99999, '', -1)\" href=\"viewspace.php?do=sharef\">点击这里可以在分享网址、视频、音乐、Flash动画</a></div>\n";
   }
   else
   {
   $giftlist.= "<div class=\"nomessage\">暂时还没有分享分享网址、视频、音乐、Flash动画。</div>";
   }

}
else
{
$giftlist.="<div class=\"nomessage\">用户隐私设置。</div>\n";
}


$giftlist.="</div></div>\n";
return $giftlist;
}


function mtag($uid=0,$myuid=0,$display='')
{
$constellation.="<div id=\"mtag\" class=\"block\" $display >
<div class=\"blocktitle\">
<h2>我的群组</h2>
</div>
<div class=\"blockcontent\">\n";



//个人群组
$mtaglist = array();
if(ckprivacy('mtag')) {
        global $_SGLOBAL;
	$query = $_SGLOBAL['db']->query("SELECT field.* FROM ".tname('tagspace')." main
		LEFT JOIN ".tname('mtag')." field ON field.tagid=main.tagid
		WHERE main.uid='$uid' LIMIT 0, 10");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		$mtaglist[$value['fieldid']][] = $value;

	}
	if($mtaglist) {
		ksort($mtaglist);
		@include_once(S_ROOT.'./data/data_profield.php');
	}

   if(count($mtaglist))
   {
     foreach ($mtaglist as $fieldid => $values) {
       $constellation.="<table cellspacing=\"0\" cellpadding=\"0\" class=\"infotable\"><tr>
       <th width=\"100\"><strong>{$_SGLOBAL[profield][$fieldid][title]}</strong></th>
       <td>";
       $dot="";
         foreach ($values as $key => $value) { 
           $constellation.="$dot<a href=\"space.php?do=mtag&tagid=$value[tagid]\" target=\"_blank\" title=\"$value[membernum]个人有同样的选择\">$value[tagname]</a>";
           $dot = '、';
         }
       $constellation.="</td>";
       $constellation.="</tr></table>";

      }

   }
   elseif($uid==$myuid)
   {
   $constellation.= "<div class=\"nomessage\"><a href=\"cp.php?ac=mtag\"><img src=\"image/intro_mtag.gif\" class=\"noimage\"></a>你还没有群组。<a href=\"cp.php?ac=mtag\">点击这里可以创建自己的群组，与志同道合朋友一起交流</a></div>\n";
   }
   else
   {
   $constellation.= "<div class=\"nomessage\">暂时还没有群组。</div>";
   }

}else
{
$constellation.="<div class=\"nomessage\">用户隐私设置。</div>\n";
}



$constellation.="</div></div>\n";
return $constellation;
}



function thread($uid=0,$myuid=0,$display='')
{
$threads.="<div id=\"thread\" class=\"block\" $display >
<div class=\"blocktitle\">
<em><a href=\"space.php?uid=$uid&do=thread&view=me\" target=\"_blank\">全部</a></em>
<h2>我的话题</h2>
</div>
<div class=\"blockcontent\">\n";




//话题
$threadlist = array();
if(ckprivacy('mtag')) {
        global $_SGLOBAL;
	$query = $_SGLOBAL['db']->query("SELECT main.* FROM ".tname('thread')." main
		WHERE main.uid='$uid'
		ORDER BY main.lastpost DESC LIMIT 0,5");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		$threadlist[] = $value;
	}
     
   if(count($threadlist))
   {
      $threads.="<ul class=\"post_list line_list\">\n";
       foreach ($threadlist as $fieldid => $value) {
           $threads.="<li><a href=\"space.php?uid=$value[uid]&do=thread&id=$value[tid]\">$value[subject]</a> <span class=\"time\"><!--{date('m-d H:i',$value[dateline],1)}--></span></li>\n";
          }
      $threads.="</ul>";
   }
   elseif($uid==$myuid)
   {
   $threads.= "<div class=\"nomessage\" id=\"nomessage\"><a href=\"cp.php?ac=thread\"><img src=\"image/intro_thread.gif\" class=\"noimage\"></a>你还没有话题。<a href=\"cp.php?ac=thread\">点击这里可以在群组中与大家讨论话题</a></div>\n";
   }
   else
   {
   $threads.="<div class=\"nomessage\" id=\"nomessage\">暂时还没有话题。</div>\n";
   }
}
else
{
$threads.="<div class=\"nomessage\" id=\"nomessage\">用户私密设置。</div>\n";
}

$threads.="</div></div>\n";
return $threads;
}


function forumstatus($uid=0,$myuid=0,$display='')
{
$forumstatus.="<div id=\"forumstatus\" class=\"block\" $display >
<div class=\"blocktitle\">\n";
$forumstatus.=$myuid==$uid?"<em><a href=\"cp.php?ac=poll\">发起投票</a></em>\n":"<em><a target=\"mainyinyue\"  href=\"javascript:;\" onclick=\"javascript:spaceCategory('viewspace.php?uid=$uid&do=poll', '');\">全部</a></em>";
$forumstatus.="<h2>我的投票</h2>
</div>
<div class=\"blockcontent\">\n";

//投票
$polllist = array();
if(ckprivacy('poll')) {
        global $_SGLOBAL;
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('poll')." WHERE uid='$uid' ORDER BY dateline DESC LIMIT 0,5");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
 $value[dateline]=getStayTime(date('Y-m-d H:i',$value[dateline]));
		$polllist[] = $value;
	}
     
   if(count($polllist))
   {
      $forumstatus.="<div class=\"bloglist\"><ul>\n";
           foreach ($polllist as $key => $value) {
     
           $forumstatus.="<li><h3><a  target=\"mainyinyue\"  href=\"javascript:;\"  onclick=\"javascript:spaceCategory('viewspace.php?uid=$[uid]&do=poll&pid=$value[pid]', '');\">$value[subject]</a></h3>
		   <div class=\"info\">
		   <span class=\"times\">$value[dateline]</span> | <span class=\"tool\">评论($value[replynum]) | <a  target=\"mainyinyue\"  href=\"javascript:;\" onclick=\"javascript:spaceCategory('viewspace.php?uid=$[uid]&do=poll&pid=$value[pid]', '');\">{$value[voternum]}人投票</a></span></div>
          </li>\n";
           }
        
        $forumstatus.="</ul></div>\n";
   }
   elseif($uid==$myuid)
   {
   $forumstatus.= "<div class=\"nomessage\" id=\"nomessage\"><a href=\"cp.php?ac=thread\"><img src=\"image/intro_thread.gif\" class=\"noimage\"></a>你还没有发起投票。<a href=\"cp.php?ac=poll\">点击这里可以发起投票</a></div>\n";
   }
   else
   {
   $forumstatus.="<div class=\"nomessage\" id=\"nomessage\">暂时还没有投票。</div>\n";
   }
}
else
{
$forumstatus.="<div class=\"nomessage\" id=\"nomessage\">用户私密设置。</div>\n";
}

$forumstatus.="</div></div>\n";
return $forumstatus;
}




function albumall($uid=0,$myuid=0,$display='')
{
$albumall.="<div id=\"albumall\" class=\"block\" $display>
<div class=\"blocktitle\">\n";
$albumall.=$myuid==$uid?"<em><a href=\"cp.php?ac=upload\">上传图片</a></em>\n":"<em><a target=\"mainyinyue\"  href=\"javascript:;\" onclick=\"javascript:spaceCategory('viewspace.php?uid=$uid&do=album', '');\">浏览更多相册</a></em>";
$albumall.="<h2>我的相册</h2>
</div>
<div class=\"blockcontent\">\n";

//相册
$albumlist = array();
if(ckprivacy('album')) {
        global $_SGLOBAL;
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('album')." WHERE uid='$uid' ORDER BY updatetime DESC LIMIT 0,4");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		if(ckfriend($value)) {
			$value['pic'] = mkpicurl($value);
			$albumlist[] = $value;
		}
	}
   if(count($albumlist))
   {
     	$albumall.="<table cellspacing=\"4\" cellpadding=\"4\">
	    <tr>\n";
         foreach ($albumlist as $key => $value) {
                $dateline=getStayTime(date('Y-m-d H:i',$value[dateline]));
		$albumall.= "<td width=\"85\" align=\"center\"><a target=\"mainyinyue\"  href=\"javascript:;\" onclick=\"javascript:spaceCategory('viewspace.php?uid=$uid&do=album&id=$value[albumid]', '');\"><img src=\"$value[pic]\" alt=\"$value[albumname]\" width=\"70\" /></a></td>
		<td width=\"165\">
		<h6><a target=\"mainyinyue\"  href=\"javascript:;\" onclick=\"javascript:spaceCategory('viewspace.php?uid=$uid&do=album&id=$value[albumid]', '');\" title=\"$value[albumname]\">$value[albumname]</a></h6>
		<p class=\"gray\">$value[picnum] 张照片</p>
               
		<p class=\"gray\">更新于 $dateline</p>
		</td>\n";
		$albumall.=$key%2==1?"</tr><tr>\n":"";
	  }
	$albumall.="</tr>
	</table>\n";
   }
   elseif($uid==$myuid)
   {
   $albumall.= "<div class=\"nomessage\"><a href=\"cp.php?ac=upload\"><img src=\"image/intro_album.gif\" align=\"absmiddle\"></a>你还没有相册。<a href=\"cp.php?ac=upload\">点击这里可以上传图片到自己的相册</a></div>\n";
   }
   else
   {
   $albumall.="<div class=\"nomessage\" >暂时还没有相册。</div>\n";
   }
}
else
{
$albumall.="<div class=\"nomessage\" >用户私密设置。</div>\n";
}

$albumall.="</div></div>\n";
return $albumall;
}


function wall($uid=0,$myuid=0,$display='')
{
$wall.="<div id=\"wall\" class=\"block\" $display>
<div class=\"blocktitle\">
<em><a target=\"mainyinyue\"  href=\"javascript:;\" onclick=\"javascript:spaceCategory('viewspace.php?uid=$uid&do=wall', '');\">全部</a></em>
<h2>留言板</h2>
</div>
<div class=\"blockcontent\">
<div class=\"commentlist\">
<ul id=\"comment_ul\">\n";

//留言板
$walllist = array();
if(ckprivacy('wall')) {

        global $_SGLOBAL,$_SN;
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('comment')." WHERE id='$uid' AND idtype='uid' ORDER BY dateline DESC LIMIT 0,5");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['authorid'], $value['author']);
		$value['message'] = strlen($value['message'])>500?getstr($value['message'], 500, 0, 0, 0, 0, -1).' ...':$value['message'];
		$walllist[] = $value;
	}
   if($walllist)
   {
       $space=getspace($uid);
       foreach ($walllist as $fieldid => $value) {
    
         if(empty($ajax_edit))
         {
         $wall.="<li id=\"comment_$value[cid]_li\">\n";
         }

        if($value[author])
        {
	  realname_get();
	  $uname=$_SN[$value[authorid]];	//getUserName($value[authorid])
          $uimg=avatar($value[authorid],'small');
	  $wall.="<div class=\"avatar48\"><a href=\"viewspace.php?uid=$value[authorid]\">$uimg</a></div>\n";
        }
        else
        {
	  $wall.="<div class=\"avatar48\"><img src=\"image/magic/hidden.gif\"  /></div>\n";
        }

       
	$wall.="<div class=\"title\">
	<div class=\"r_option\">\n";

                 if($value['authorid'] != $_SGLOBAL['supe_uid'] && $value['author'] == "" && $_SGLOBAL[magic][reveal])
                 {
			$wall.="<a id=\"a_magic_reveal_{$value[cid]}\" href=\"magic.php?mid=reveal&idtype=cid&id=$value[cid]\" onclick=\"ajaxmenu(event,this.id,1)\"><img src=\"image/magic/reveal.small.gif\" class=\"magicicon\">{$_SGLOBAL[magic][reveal]}</a>
			<span class=\"pipe\">|</span>\n";
                 }

			if($value[authorid]==$myuid)
                        {
				if($_SGLOBAL[magic][anonymous])
				{
				$wall.="<img src=\"image/magic/anonymous.small.gif\" class=\"magicicon\">
				<a id=\"a_magic_anonymous_$value[cid]\" href=\"magic.php?mid=anonymous&idtype=cid&id=$value[cid]\" onclick=\"ajaxmenu(event,this.id, 1)\">{$_SGLOBAL[magic][anonymous]}</a>
				<span class=\"pipe\">|</span>\n";
				}
				if($_SGLOBAL[magic][flicker])
				{
				$wall.="<img src=\"image/magic/flicker.small.gif\" class=\"magicicon\">\n";
					if($value[magicflicker])
				       {
				        $wall.="<a id=\"a_magic_flicker_$value[cid]\" href=\"cp.php?ac=magic&op=cancelflicker&idtype=cid&id=$value[cid]\" onclick=\"ajaxmenu(event,this.id)\">取消{$_SGLOBAL[magic][flicker]}</a>\n";
				       }
				       else
				       {
				         $wall.="<a id=\"a_magic_flicker_$value[cid]\" href=\"magic.php?mid=flicker&idtype=cid&id=$value[cid]\" onclick=\"ajaxmenu(event,this.id, 1)\">{$_SGLOBAL[magic][flicker]}</a>\n";
				       }
				$wall.="<span class=\"pipe\">|</span>\n";
				}

			$wall.="<a href=\"cp.php?ac=comment&op=edit&cid=$value[cid]\" id=\"c_$value[cid]_edit\" onclick=\"ajaxmenu(event, this.id,1)\">编辑</a>\n";
			}
			if($value[authorid]==$myuid || $value[uid]==$myuid)
                        {
			$wall.="<a href=\"cp.php?ac=comment&op=delete&cid=$value[cid]\" id=\"c_$value[cid]_delete\" onclick=\"ajaxmenu(event, this.id)\">删除</a>\n";
			}
			if($value[authorid]!=$myuid && ($value['idtype'] != 'uid' || $space[self]))
                        {
			$wall.="<a href=\"cp.php?ac=comment&op=reply&cid=$value[cid]\" id=\"c_$value[cid]_reply\" onclick=\"ajaxmenu(event, this.id, 1)\">回复</a>\n";
			}
                        $wall.="<a href=\"cp.php?ac=common&op=report&idtype=comment&id=$value[cid]\" id=\"a_report_$value[cid]\" onclick=\"ajaxmenu(event, this.id, 1)\">举报</a>\n";
		$wall.="</div>\n";

                $dateline=getStayTime(date('Y-m-d H:i',$value[dateline]));
				if($value[author])
				{
					$wall.="<a href=\"viewspace.php?uid=$value[authorid]\" id=\"author_$value[cid]\">$uname</a> <span class=\"time\">$dateline</span>\n";
				}
				else
				{
					$wall.="匿名<span class=\"time\">$dateline</span>\n";
				}

	 $wall.="</div>\n";
			//Add By 01↓
			if ( $value[hiddenreply] && $_SGLOBAL[supe_uid] != $value[uid] && $_SGLOBAL[supe_uid] != $value[authorid] ) {
				$wall.="<div class=\"detail\" id=\"comment_$value[cid]\"><font color=\"dimgray\">(此留言是悄悄话噢…)</font></div>\n";
			}else{
			//Add By 01↑
				if($value[magicflicker])
				{
					$wall.="<div class=\"detail magicflicker\" id=\"comment_$value[cid]\">$value[message]</div>\n";
				}
				else
				{
					$wall.="<div class=\"detail\" id=\"comment_$value[cid]\">$value[message]</div>\n";
				}
			}	//Add By 01
			if(empty($ajax_edit))
			{
				$wall.="</li>\n";
			}

		}
	}
   else
   {
     $wall.="<div class=\"nomessage\" >暂时还没有留言。</div>\n";
   }
}
else
{
$wall.="<div class=\"nomessage\" >用户私密设置。</div>\n";
}

$wall.="</ul>\n";





$wall.="<form action=\"cp.php?ac=comment\" id=\"quick_commentform_$uid\" name=\"quick_commentform_$uid\" method=\"post\" style=\"padding:0 0 0 5px;\">
<a href=\"###\" id=\"editface\" onclick=\"showFace(this.id, 'comment_message');\"><img src=\"image/facelist.gif\" align=\"absmiddle\" /></a>\n";
if($_SGLOBAL['magic']['doodle'])
{
$wall.="<a id=\"a_magic_doodle\" href=\"magic.php?mid=doodle&showid=comment_doodle&target=comment_message\" onclick=\"ajaxmenu(event, this.id, 1)\"><img src=\"image/magic/doodle.small.gif\" class=\"magicicon\" />涂鸦板</a>\n";
}
$wall.="<br />
<textarea name=\"message\" id=\"comment_message\" rows=\"5\" cols=\"60\" style=\"width:98%;\" onkeydown=\"ctrlEnter(event, 'commentsubmit_btn');\" onclick=\"if(this.value == '大量刷留言评论、发布广告、脏话将被处罚，严重者将被删除帐号(用户)，请勿抱侥幸心理。'){this.value='';this.style.color='#000000';}\" onkeydown=\"ctrlEnter(event, 'comment_submit');\" style=\"color:#737373\">大量刷留言评论、发布广告、脏话将被处罚，严重者将被删除帐号(用户)，请勿抱侥幸心理。</textarea><br>
<input type=\"hidden\" name=\"refer\" value=\"space.php?uid=$uid\" />
<input type=\"hidden\" name=\"id\" value=\"$uid\" />
<input type=\"hidden\" name=\"idtype\" value=\"uid\" />
<input type=\"hidden\" name=\"commentsubmit\" value=\"true\" />
<input type=\"button\" id=\"commentsubmit_btn\" name=\"commentsubmit_btn\" class=\"submit\" value=\"留言\" onclick=\"ajaxpost('quick_commentform_$uid', 'wall_add')\" />
<input type=\"checkbox\" name=\"hiddenreply\" value=\"1\"> 悄悄话	<!--Add By 01-->
<span id=\"__quick_commentform_$uid\"></span>\n";
$aa=formhash();
$wall.="<input type=\"hidden\" name=\"formhash\" value=\"$aa\" />
</form>


</div>
</div>
</div>\n";
return $wall;
}

function giftlist($uid=0,$myuid=0,$display='')
{
$giftlist.="";
return $giftlist;
}
function constellation($uid=0,$myuid=0,$display='')
{
$constellation1.="";
return $constellation1;
}
function album($uid=0,$myuid=0,$display='')
{
$album.="";
return $album;
}

function GmtToUnix($GmtDate)
{
 $DateArr = explode(' ',$GmtDate); // 分割GMT日期为 日期 | 时间

 /* 在日期中取得年,月,日 */
 $pDate = split('[/.-]',$DateArr[0]);
 $Year = $pDate[0];
 $Month = $pDate[1];
 $Day = $pDate[2];
 
 /* 在时间中取得时,分,秒 */
 $pTime = split('[:.-]',$DateArr[1]);
 $Hour = $pTime[0];
 $Minute = $pTime[1];
 $Second = $pTime[2];
 
 if($Year == '' || !is_numeric($Year))$Year = 0;
 if($Month == '' || !is_numeric($Month))$Month = 0;
 if($Day == '' || !is_numeric($Day))$Day = 0;
 if($Hour == '' || !is_numeric($Hour))$Hour = 0;
 if($Minute == '' || !is_numeric($Minute))$Minute = 0;
 if($Second == '' || !is_numeric($Second))$Second = 0;
 
 return mktime($Hour,$Minute,$Second,$Month,$Day,$Year);
}

function query_one($sql)
{
	global $_SGLOBAL;
	$query = $_SGLOBAL['db']->query( $sql );
	return $_SGLOBAL['db']->fetch_array( $query );
}
//返回表单条记录数组
function getSqlResult($sql){
global $_SGLOBAL;
$query =$_SGLOBAL['db']->query($sql);
$value =$_SGLOBAL['db']->fetch_array( $query );
return $value;
}

function susercount($uid=0)
{
global $_SGLOBAL;
$sql="select count(*) as intNum from ".tname('app_viewspace_suser')." where uid='$uid'";
$query =$_SGLOBAL['db']->query($sql);	
$intNum = $_SGLOBAL['db']->result($query,0);	  
return $intNum;
}




function getStayTime($dateParkStartTime)  //0年0月1日10时0分
{

  $nowtime=date("Y-m-d H:i:s"); 
  $time_diff=abs(strtotime($nowtime)-strtotime($dateParkStartTime));
  $diff_time = array(); 
  $diff_time["year"] = 0;
  if($time_diff > 31536000) //一年31536000秒
    $diff_time["year"] = floor($time_diff / 31536000);
  $time_diff = $time_diff - $diff_time["year"] * 31536000;
  $diff_time["month"] = 0;
  if($time_diff > 2592000) //一月2592000秒
    $diff_time["month"] = floor($time_diff / 2592000);
  $time_diff = $time_diff - $diff_time["month"] * 2592000;
  $diff_time["day"] = 0;
  if($time_diff > 86400) //一天86400秒
    $diff_time["day"] = floor($time_diff / 86400);
  $time_diff = $time_diff - $diff_time["day"] * 86400;  
  $diff_time["hour"] = 0;
  if($time_diff > 3600) //一小时3600秒
    $diff_time["hour"] = floor($time_diff / 3600);
  $time_diff = $time_diff - $diff_time["hour"] * 3600;    
  $diff_time["minute"] = 0;
  if($time_diff > 60) //一分60秒
    $diff_time["minute"] = floor($time_diff / 60);
  $time_diff = $time_diff - $diff_time["minute"] * 60;     
   $diff_time["sencond"] = floor($time_diff);
   
  if($diff_time["day"]==0) {
      $diff_time["strday"]="";
	  if($diff_time["hour"]==0){
	    $diff_time["strhour"]="";
	    if($diff_time["minute"]==0){
		   $diff_time["strminute"]="";
	       if($diff_time["second"]==0 ){
		      $diff_time["strsecond"]=""; 
			}else{
			   $diff_time["strsecond"]=$diff_time["second"].'秒前';
			} 		   
		 }else{
		   $diff_time["strminute"]=$diff_time["minute"].'分钟前'; 
		 }		
	  }else{
	    $diff_time["strhour"]=$diff_time["hour"].'小时前';
	  }
	}else {
 	  $diff_time["strday"]=date("Y-m-d H:i",strtotime($dateParkStartTime));
	}
   
  $retStr=$diff_time["strday"].$diff_time["strhour"].$diff_time["strminute"].$diff_time["strsencond"];
  if(empty($retStr)) $retStr="刚才";
  return $retStr;
} 

function get_userURL1($uid=0) {
	
$uid = abs(intval($uid));
$uid = sprintf("%09d", $uid);
$dir1 = substr($uid, 0, 3);
$dir2 = substr($uid, 3, 2);
$dir3 = substr($uid, 5, 2);

$dir11= 'viewspace/css/'.$dir1;
$dir22= 'viewspace/css/'.$dir1.'/'.$dir2;
$dir33= 'viewspace/css/'.$dir1.'/'.$dir2.'/'.$dir3;
if(!file_exists($dir11)){mkdir($dir11);}
if(!file_exists($dir22)){mkdir($dir22);}
if(!file_exists($dir33)){mkdir($dir33);}	
return  $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid,-2)."_style.css";
}

function get_userURL2($uid=0) {
	
$uid = abs(intval($uid));
$uid = sprintf("%09d", $uid);
$dir1 = substr($uid, 0, 3);
$dir2 = substr($uid, 3, 2);
$dir3 = substr($uid, 5, 2);

return  $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid,-2)."_style.css";
}

function multiviewspace($num, $perpage, $curpage, $mpurl) {
	global $_SCONFIG;
	$page = 5;
	$multipage = '';
	$mpurl .= strpos($mpurl, '?') ? '&' : '?';
	$realpages = 1;
	if($num > $perpage) {
		$offset = 2;
		$realpages = @ceil($num / $perpage);
		$pages = $_SCONFIG['maxpage'] && $_SCONFIG['maxpage'] < $realpages ? $_SCONFIG['maxpage'] : $realpages;
		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if($to - $from < $page) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}
		$multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="javascript:;" class="first" onclick="javascript:spaceCategory(\''.$mpurl.'\'page=1\', \'\');">1 ...</a>' : '').
			($curpage > 1 ? '<a href="javascript:;" onclick="javascript:spaceCategory(\''.$mpurl.'page='.($curpage - 1).'\', \'\');" class="prev">&lsaquo;&lsaquo;</a>' : '');
		for($i = $from; $i <= $to; $i++) {
			$multipage .= $i == $curpage ? '<strong>'.$i.'</strong>' :
				'<a href="javascript:;"  onclick="javascript:spaceCategory(\''.$mpurl.'page='.$i.'\', \'\');" >'.$i.'</a>';
		}
		$multipage .= ($curpage < $pages ? '<a href="javascript:;"  onclick="javascript:spaceCategory(\''.$mpurl.'page='.($curpage + 1).'\', \'\');" class="next">&rsaquo;&rsaquo;</a>' : '').
			($to < $pages ? '<a href="javascript:;" onclick="javascript:spaceCategory(\''.$mpurl.'page='.$pages.'\', \'\');"  class="last">... '.$realpages.'</a>' : '');
		$multipage = $multipage ? ('<em>&nbsp;'.$num.'&nbsp;</em>'.$multipage):'';
	}
	$maxpage = $realpages;
	return $multipage;
}


//处理上传图片连接
function mkpicurl($pic, $thumb=1) {
	global $_SCONFIG, $_SC, $space;

	$url = '';
	if(isset($pic['picnum']) && $pic['picnum'] < 1) {
		$url = 'image/nopic.gif';
	} elseif(isset($pic['picflag'])) {
		if($pic['pic']) {
			if($pic['picflag'] == 1) {
				$url = $_SC['attachurl'].$pic['pic'];
			} elseif ($pic['picflag'] == 2) {
				$url = $_SCONFIG['ftpurl'].$pic['pic'];
			} else {
				$url = $pic['pic'];
			}
		}
	} elseif(isset($pic['filepath'])) {
		$pic['pic'] = $pic['filepath'];
		if($pic['pic']) {
			if($thumb && $pic['thumb']) $pic['pic'] .= '.thumb.jpg';
			if($pic['remote']) {
				$url = $_SCONFIG['ftpurl'].$pic['pic'];
			} else {
				$url = $_SC['attachurl'].$pic['pic'];
			}
		}
	} else {
		$url = $pic['pic'];
	}
	if($url && $pic['friend']==4) {
		$url = 'image/nopublish.jpg';
	}
	return $url;
}

function get_avatar($uid, $size ='small') {
$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'middle';
$uid = abs(intval($uid));
$uid = sprintf("%09d", $uid);
$dir1 = substr($uid, 0, 3);
$dir2 = substr($uid, 3, 2);
$dir3 = substr($uid, 5, 2);
return $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2)."_avatar_$size.jpg";
}

?>
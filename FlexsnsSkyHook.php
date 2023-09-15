<?php

//通用文件
include_once('./common.php');
include_once(S_ROOT.'./source/function_cp.php');
include_once(S_ROOT.'./uc_client/client.php' );

if( intval( $_POST['appid'] ) <= 0 ) 
exit( 'Access Denied!' );

$apps = uc_app_ls();

//验证是否来自sky
$hookCheck = $apps[$_POST['appid']]['url'].'/?hook-check/hookKey-'.$_POST['hookKey'];
if( fstream( $hookCheck ) != 1 )
exit( 'Access Denied!' );

list( $uid ) = uc_user_login( urldecode( $_POST['username'] ), urldecode( $_POST['password'] ) );

if( $uid <= 0) 
exit( 'Access Denied!' );

checkclose();

if( empty( $_POST['note'] ) )
exit( 'Access Denied!' );

if( !function_exists( notification_add ) )
exit( 'notification_function_not_exists' );

notification_add( $_POST['toUid'], 'flexsns-sky', urldecode( $_POST['note'] ) );

function fstream( $url, $data = '', $cookie = '' ) 
{
	$ucUrlArray = parse_url( $url );
	$ucUrlArray['path'] = $ucUrlArray['path'] ? $ucUrlArray['path'].( $ucUrlArray['query'] ? '?'.$ucUrlArray['query'] : '' ) : '/';
	
	$out ='';
	
	if( !empty( $data ) )
	{	
		$out = "POST {$ucUrlArray['path']} HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: {$ucUrlArray['host']}\r\n";
		$out .= 'Content-Length: '.strlen($data)."\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cache-Control: no-cache\r\n";
		$out .= "Cookie: {$cookie}\r\n\r\n";
		$out .= $data;
	}
	else
	{
		$out = "GET {$ucUrlArray['path']} HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: {$ucUrlArray['host']}\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cookie: {$cookie}\r\n\r\n";
	}
	
	$errno = -1;
	$errstr = '';
	$timeOut = 30;
	
	$fh = @fsockopen( $ucUrlArray['host'], $ucUrlArray['port']?$ucUrlArray['port']:80, $errno, $errstr, $timeOut );
	
	if( $errstr )
	return false;
	
	stream_set_blocking( $fh, true );
	stream_set_timeout( $fh, $timeOut );
	@fwrite( $fh, $out );
	
	$status = stream_get_meta_data($fh);
	
	if( $status['timed_out'] )
	return false;
	
	while ( !feof( $fh ) ) 
	{
		if(( $header = @fgets( $fh )) && ( $header == "\r\n" ||  $header == "\n" ) ) 
		{
			break;
		}
	}

	$streadata = '';
	while( !feof( $fh ) ) 
	{
		$streadata .= @fread( $fh, 8192 );
	}
	
	return $streadata;
}

?>
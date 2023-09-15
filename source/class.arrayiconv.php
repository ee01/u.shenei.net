<?php
/*
	[F.S. Studio] (C) 2007-2008 E Inc.
	$Id: siconv.php 12766 2009-07-20 04:26:21Z E $
*/

class arrayiconv
{
	static protected $in;
	static protected $out;
	/**
	  * ��̬����,�÷����������鲢��������
	  *
	  * @param unknown_type $array ���������
	  * @param unknown_type $in ��������ı���
	  * @param unknown_type $out ��������ı���
	  * @return unknown ���ص�����
	  */
	static public function Conversion($array,$out,$in)
	{
		self::$in=$in;
		self::$out=$out;
		return self::arraymyicov($array);
	}
	/**
	  * �ڲ�����,ѭ������
	  *
	  * @param unknown_type $array
	  * @return unknown
	  */
	static private function arraymyicov($array)
	{
		foreach ($array as $key=>$value)
		{
			$key=self::myiconv($key);
			if (!is_array($value)) {
				$value=self::myiconv($value);
			}else {
				$value=self::arraymyicov($value);
			}
			$temparray[$key]=$value;
		}
		return $temparray;
	}
	/**
	  * �滻�������
	  *
	  * @param unknown_type $str
	  * @return unknown
	  */
	static private function myiconv($str)
	{
		return self::siconv($str,self::$out,self::$in);
	}
	/**
	  * ����ϵͳ���뺯��
	  *
	  * @param unknown_type $str
	  * @return unknown
	  */
	static private function siconv($str, $out_charset, $in_charset='') {
		$in_charset = strtoupper($in_charset);
		$out_charset = strtoupper($out_charset);
		if($in_charset != $out_charset) {
			if (function_exists('iconv') && (@$outstr = iconv("$in_charset//IGNORE", "$out_charset//IGNORE", $str))) {
				return $outstr;
			} elseif (function_exists('mb_convert_encoding') && (@$outstr = mb_convert_encoding($str, $out_charset, $in_charset))) {
				return $outstr;
			}
		}
		return $str;//ת��ʧ��
	}
}

?>
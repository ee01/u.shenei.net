<?php
class sp {
	var $html = '';
	var $CurrentBit = 0;
	var $domain = '';
	var $allowExt = array('gif', 'png', 'jpg', 'jpeg', 'bmp');
	function sp($c) {
		$this -> html = $c;
		$this->domain = $this->getDomain();
	} 

	function init() {
		return $this -> getImageLocation(stripslashes($this -> html));
	} 

	function getImageLocation($html) {
		if (preg_match_all("/\<img.+?src=[\"|\'|]{0,1}(.*?)[\"|\'|\>| ](.*?)\>/ies", $html, $regs)) {
			foreach($regs[1] as $key => $url) {
				if(strpos($url, $this->domain) !== false || substr($url, 0, 7) != 'http://') continue;
				$new_url = $this -> loadImage($url);
				if (!$new_url) {
					$html = str_replace($regs[0][$key], "", $html);
				} else {
					$html = str_replace($url, $new_url, $html);
				} 
			} 
		} 
		return $html;
	} 

	function loadImage($url) {
		global $_SGLOBAL, $_SCONFIG, $_SC;
		$hash = md5($url);
		$temp = explode("/", $url);
		$name = array_pop($temp);
		$ext = strtolower($this -> fileext($name));

		$d = $this->getfilepath($ext, true);
		$new_name = $_SC['attachdir'].$d;
		
		if (!$ext) return '';
		if (!in_array($ext, $this->allowExt)) return $url;
		$a = $this -> load($url);
		if (strlen($a) < 1024) return '';
		$this -> writetofile($new_name, $a);
		
		//缩略图
		include_once(S_ROOT.'./source/function_image.php');
		$thumbpath = makethumb($new_name);
		$thumb = empty($thumbpath)?0:1;
		//获取图片大小
		@$newfilesize = filesize($new_name);
		//水印
		if($_SCONFIG['allowwatermark']) {
			makewatermark($new_name);
		}
		
		//入库
		$setarr = array(
			'albumid' => '0',
			'uid' => $_SGLOBAL['supe_uid'],
			'username' => $_SGLOBAL['supe_username'],
			'dateline' => $_SGLOBAL['timestamp'],
			'filename' => addslashes($name),
			'postip' => getonlineip(),
			'title' => $title,
			'type' => addslashes($ext),
			'size' => $newfilesize,
			'filepath' => $d,
			'thumb' => $thumb,
			'remote' => '0',
			'topicid' => '0'
		);
		inserttable('pic', $setarr);
		//统计
		updatestat('pic');
		
		
		if ($ext == 'bmp') {
			$file = $new_name;
			$source = $this -> imagecreatefrombmp($file);
			$w = imagesx($source);
			$h = imagesy($source);
			$im = @imagecreatetruecolor($w, $h);
			imagecopy ($im, $source, 0, 0, 0, 0, $w, $h);

			imagepng($im, str_replace(".bmp", ".png", $file));
			imagedestroy($im);
			imagedestroy($source);
			@unlink($file);
			$new_name = str_replace(".bmp", ".png", $new_name);
		} 

		return $new_name;
	} 

	function getDomain(){
		return "http://" . $_SERVER['HTTP_HOST'];
	}

	function createdir($dir) {
		return is_dir($dir) || ($this -> createdir(dirname($dir)) && @mkdir($dir));
	} 

	function writetofile($file_name, $data, $method = "w") {
		if ($filenum = fopen($file_name, $method)) {
			flock($filenum, LOCK_EX);
			$status = fwrite($filenum, $data);
			fclose($filenum);
			return $status;
		} else {
			return false;
		} 
	} 

	function load($url, $limit = 0, $post = '', $cookie = '', $bysocket = false, $ip = '', $timeout = 15, $block = true) {
		$return = '';
		$matches = parse_url($url);
		!isset($matches['host']) && $matches['host'] = '';
		!isset($matches['path']) && $matches['path'] = '';
		!isset($matches['query']) && $matches['query'] = '';
		!isset($matches['port']) && $matches['port'] = '';
		$host = $matches['host'];
		$path = $matches['path'] ? $matches['path'] . ($matches['query'] ? '?' . $matches['query'] : '') : '/';
		$port = !empty($matches['port']) ? $matches['port'] : 80;
		if ($post) {
			$out = "POST $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Referer: http://{$host}\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= 'Content-Length: ' . strlen($post) . "\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cache-Control: no-cache\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
			$out .= $post;
		} else {
			$out = "GET $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Referer: http://{$host}\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
		} 
		$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		if (!$fp) {
			return ''; //note $errstr : $errno \r\n
		} else {
			stream_set_blocking($fp, $block);
			stream_set_timeout($fp, $timeout);
			@fwrite($fp, $out);
			$status = stream_get_meta_data($fp);
			if (!$status['timed_out']) {
				while (!feof($fp)) {
					if (($header = @fgets($fp)) && ($header == "\r\n" || $header == "\n")) {
						break;
					} 
				} 
				$stop = false;
				while (!feof($fp) && !$stop) {
					$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
					$return .= $data;
					if ($limit) {
						$limit -= strlen($data);
						$stop = $limit <= 0;
					} 
				} 
			} 
			@fclose($fp);
			return $return;
		} 
	} 

	function fileext($filename) {
		return trim(substr(strrchr($filename, '.'), 1));
	} 

	function imagecreatefrombmp($file) {
		$this -> CurrentBit = 0;
		$f = fopen($file, "r");
		$Header = fread($f, 2);

		if ($Header == "BM") {
			$Size = $this -> freaddword($f);
			$Reserved1 = $this -> freadword($f);
			$Reserved2 = $this -> freadword($f);
			$FirstByteOfImage = $this -> freaddword($f);

			$SizeBITMAPINFOHEADER = $this -> freaddword($f);
			$Width = $this -> freaddword($f);
			$Height = $this -> freaddword($f);
			$biPlanes = $this -> freadword($f);
			$biBitCount = $this -> freadword($f);
			$RLECompression = $this -> freaddword($f);
			$WidthxHeight = $this -> freaddword($f);
			$biXPelsPerMeter = $this -> freaddword($f);
			$biYPelsPerMeter = $this -> freaddword($f);
			$NumberOfPalettesUsed = $this -> freaddword($f);
			$NumberOfImportantColors = $this -> freaddword($f);

			if ($biBitCount < 24) {
				$img = imagecreate($Width, $Height);
				$Colors = pow(2, $biBitCount);
				for ($p = 0; $p < $Colors; $p++) {
					$B = $this -> freadbyte($f);
					$G = $this -> freadbyte($f);
					$R = $this -> freadbyte($f);
					$Reserved = $this -> freadbyte($f);
					$Palette[] = imagecolorallocate($img, $R, $G, $B);
				} 

				if ($RLECompression == 0) {
					$Zbytek = (4 - ceil(($Width / (8 / $biBitCount))) % 4) % 4;

					for ($y = $Height -1; $y >= 0; $y--) {
						$this -> CurrentBit = 0;
						for ($x = 0; $x < $Width; $x++) {
							$C = $this -> freadbits($f, $biBitCount);
							imagesetpixel($img, $x, $y, $Palette[$C]);
						} 
						if ($this -> CurrentBit != 0) {
							$this -> freadbyte($f);
						} 
						for ($g = 0; $g < $Zbytek; $g++)
						$this -> freadbyte($f);
					} 
				} 
			} 

			if ($RLECompression == 1) { // $BI_RLE8
				$y = $Height;
				$pocetb = 0;
				while (true) {
					$y--;
					$prefix = $this -> freadbyte($f);
					$suffix = $this -> freadbyte($f);
					$pocetb += 2;

					$echoit = false;

					if ($echoit)
						echo "Prefix: $prefix Suffix: $suffix<BR>";
					if (($prefix == 0) and ($suffix == 1))
						break;
					if (feof($f))
						break;

					while (!(($prefix == 0) and ($suffix == 0))) {
						if ($prefix == 0) {
							$pocet = $suffix;
							$Data .= fread($f, $pocet);
							$pocetb += $pocet;
							if ($pocetb % 2 == 1) {
								$this -> freadbyte($f);
								$pocetb++;
							} 
						} 
						if ($prefix > 0) {
							$pocet = $prefix;
							for ($r = 0; $r < $pocet; $r++)
							$Data .= chr($suffix);
						} 
						$prefix = $this -> freadbyte($f);
						$suffix = $this -> freadbyte($f);
						$pocetb += 2;
						if ($echoit)
							echo "Prefix: $prefix Suffix: $suffix<BR>";
					} 

					for ($x = 0; $x < strlen($Data); $x++) {
						imagesetpixel($img, $x, $y, $Palette[ord($Data[$x])]);
					} 
					$Data = "";
				} 
			} 

			if ($RLECompression == 2) { // $BI_RLE4
				$y = $Height;
				$pocetb = 0;
				while (true) {
					$y--;
					$prefix = $this -> freadbyte($f);
					$suffix = $this -> freadbyte($f);
					$pocetb += 2;

					$echoit = false;

					if ($echoit)
						echo "Prefix: $prefix Suffix: $suffix<BR>";
					if (($prefix == 0) and ($suffix == 1))
						break;
					if (feof($f))
						break;

					while (!(($prefix == 0) and ($suffix == 0))) {
						if ($prefix == 0) {
							$pocet = $suffix;

							$this -> CurrentBit = 0;
							for ($h = 0; $h < $pocet; $h++)
							$Data .= chr($this -> freadbits($f, 4));
							if ($this -> CurrentBit != 0)
								$this -> freadbits($f, 4);
							$pocetb += ceil(($pocet / 2));
							if ($pocetb % 2 == 1) {
								$this -> freadbyte($f);
								$pocetb++;
							} 
						} 
						if ($prefix > 0) {
							$pocet = $prefix;
							$i = 0;
							for ($r = 0; $r < $pocet; $r++) {
								if ($i % 2 == 0) {
									$Data .= chr($suffix % 16);
								} else {
									$Data .= chr(floor($suffix / 16));
								} ;
								$i++;
							} ;
						} ;
						$prefix = $this -> freadbyte($f);
						$suffix = $this -> freadbyte($f);
						$pocetb += 2;
						if ($echoit)
							echo "Prefix: $prefix Suffix: $suffix<BR>";
					} 
					for ($x = 0; $x < strlen($Data); $x++) {
						imagesetpixel($img, $x, $y, $Palette[ord($Data[$x])]);
					} 
					$Data = "";
				} 
			} 

			if ($biBitCount == 24) {
				$img = imagecreatetruecolor($Width, $Height);
				$Zbytek = $Width % 4;

				for ($y = $Height -1; $y >= 0; $y--) {
					for ($x = 0; $x < $Width; $x++) {
						$B = $this -> freadbyte($f);
						$G = $this -> freadbyte($f);
						$R = $this -> freadbyte($f);
						$color = imagecolorexact($img, $R, $G, $B);
						if ($color == -1)
							$color = imagecolorallocate($img, $R, $G, $B);
						imagesetpixel($img, $x, $y, $color);
					} 
					for ($z = 0; $z < $Zbytek; $z++)
					$this -> freadbyte($f);
				} 
			} 
			return $img;
		} 

		fclose($f);
	} 

	function freadbyte($f) {
		return ord(fread($f, 1));
	} 

	function freadword($f) {
		$b1 = $this -> freadbyte($f);
		$b2 = $this -> freadbyte($f);
		return $b2 * 256 + $b1;
	} 

	function freadlngint($f) {
		return $this -> freaddword($f);
	} 

	function freaddword($f) {
		$b1 = $this -> freadword($f);
		$b2 = $this -> freadword($f);
		return $b2 * 65536 + $b1;
	} 

	function RetBits($byte, $start, $len) {
		$bin = $this -> decbin8($byte);
		$r = bindec(substr($bin, $start, $len));
		return $r;
	} 

	function freadbits($f, $count) {
		$Byte = $this -> freadbyte($f);
		$LastCBit = $this -> CurrentBit;
		$this -> CurrentBit += $count;
		if ($this -> CurrentBit == 8) {
			$this -> CurrentBit = 0;
		} else {
			fseek($f, ftell($f) - 1);
		} 
		return $this -> RetBits($Byte, $LastCBit, $count);
	} 

	function RGBToHex($Red, $Green, $Blue) {
		$hRed = dechex($Red);
		if (strlen($hRed) == 1)
			$hRed = "0$hRed";
		$hGreen = dechex($Green);
		if (strlen($hGreen) == 1)
			$hGreen = "0$hGreen";
		$hBlue = dechex($Blue);
		if (strlen($hBlue) == 1)
			$hBlue = "0$hBlue";
		return ($hRed . $hGreen . $hBlue);
	} 

	function int_to_dword($n) {
		return chr($n &255) . chr(($n >> 8) &255) . chr(($n >> 16) &255) . chr(($n >> 24) &255);
	} 
	function int_to_word($n) {
		return chr($n &255) . chr(($n >> 8) &255);
	} 

	function decbin8($d) {
		return $this -> decbinx($d, 8);
	} 

	function decbinx($d, $n) {
		$bin = decbin($d);
		$sbin = strlen($bin);
		for ($j = 0; $j < $n - $sbin; $j++)
		$bin = "0$bin";
		return $bin;
	} 

	function inttobyte($n) {
		return chr($n);
	} 

	function formaturl($l1, $l2) {
		if (preg_match_all("/(<img[^>]+src=\"([^\"]+)\"[^>]*>)|(<a[^>]+href=\"([^\"]+)\"[^>]*>)|(<img[^>]+src='([^']+)'[^>]*>)|(<a[^>]+href='([^']+)'[^>]*>)/i", $l1, $regs)) {
			foreach($regs[0] as $num => $url) {
				$l1 = str_replace($url, $this -> linkAbs($url, $l2), $l1);
			} 
		} 
		return $l1;
	} 

	function linkAbs($l1, $l2) {
		if (preg_match("/(.*)(href|src)\=(.+?)( |\/\>|\>).*/i", $l1, $regs)) {
			$I2 = $regs[3];
		} 
		if (strlen($I2) > 0) {
			$I1 = str_replace(chr(34), "", $I2);
			$I1 = str_replace(chr(39), "", $I1);
		} else {
			return $l1;
		} 
		$url_parsed = parse_url($l2);
		$scheme = $url_parsed["scheme"];
		if ($scheme != "") {
			$scheme = $scheme . "://";
		} 
		$host = $url_parsed["host"];
		$l3 = $scheme . $host;
		if (strlen($l3) == 0) {
			return $l1;
		} 
		$path = dirname($url_parsed["path"]);
		if ($path[0] == "\\") {
			$path = "";
		} 
		$pos = strpos($I1, "#");
		if ($pos > 0) $I1 = substr($I1, 0, $pos);
		if (preg_match("/^(http|https|ftp):(\/\/|\\\\)(([\w\/\\\+\-~`@:%])+\.)+([\w\/\\\.\=\?\+\-~`@\':!%#]|(&amp;)|&)+/i", $I1)) {
			return $l1;
		} elseif ($I1[0] == "/") {
			$I1 = $l3 . $I1;
		} elseif (substr($I1, 0, 3) == "../") {
			while (substr($I1, 0, 3) == "../") {
				$I1 = substr($I1, strlen($I1) - (strlen($I1)-3), strlen($I1)-3);
				if (strlen($path) > 0) {
					$path = dirname($path);
				} 
			} 
			$I1 = $l3 . $path . "/" . $I1;
		} elseif (substr($I1, 0, 2) == "./") {
			$I1 = $l3 . $path . substr($I1, strlen($I1) - (strlen($I1)-1), strlen($I1)-1);
		} elseif (strtolower(substr($I1, 0, 7)) == "mailto:" || strtolower(substr($I1, 0, 11)) == "javascript:") {
			return $l1;
		} else {
			$I1 = $l3 . $path . "/" . $I1;
		} 
		return str_replace($I2, "\"$I1\"", $l1);
	} 
	
	
	//获取上传路径
	function getfilepath($fileext, $mkdir=false) {
		global $_SGLOBAL, $_SC;

		$filepath = "{$_SGLOBAL['supe_uid']}_{$_SGLOBAL['timestamp']}".random(4).".$fileext";
		$name1 = gmdate('Ym');
		$name2 = gmdate('j');

		if($mkdir) {
			$newfilename = $_SC['attachdir'].'./'.$name1;
			if(!is_dir($newfilename)) {
				if(!@mkdir($newfilename)) {
					runlog('error', "DIR: $newfilename can not make");
					return $filepath;
				}
			}
			$newfilename .= '/'.$name2;
			if(!is_dir($newfilename)) {
				if(!@mkdir($newfilename)) {
					runlog('error', "DIR: $newfilename can not make");
					return $name1.'/'.$filepath;
				}
			}
		}
		return $name1.'/'.$name2.'/'.$filepath;
	}
} 

?>
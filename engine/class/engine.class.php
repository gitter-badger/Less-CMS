<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

class engine
{
	function clean($in, $object=false)
	{
		if(is_object($in))
		{
			$in = (array)$in;
		}
		$blackList = array ("\x60","\t","\n","\r","\\","В¬","--","GROUP","--%","UNION","","eval","INTO","OUTFILE","LOAD_FILE","SELECT","-1"," AND ","information_schema","columns","select" ,"null," ,"OR ",".ppp",'</script>','<script>','</html>','<html>','</body>','<body>','</head>','<head>');

		if(is_array($in))
		{
			foreach($in as $key => $value)
			{
				if(is_array($value))
				{
					$out[$key] = self::clean($value);
				}
				else
				{
					$out[$key] = str_replace($blackList, '', $value);
				}
			}
		}
		else
		{
			$out = str_replace($blackList, '', $in);
		}
<<<<<<< HEAD

=======
		if(empty($out))
		{
			return false;
		}
>>>>>>> origin/master
		if($object)
		{
			return (object)$out;
		}
		return $out;
	}
<<<<<<< HEAD

	function signIn($name, $value)
=======
	
	function addSkey($name, $value)
>>>>>>> origin/master
	{
		session_start();
		$_SESSION[$name] = $value;
		session_write_close();
		return true;
	}
<<<<<<< HEAD

	function signOut($name)
=======
	
	function rmSkey($name)
>>>>>>> origin/master
	{
		session_start();
		unset($_SESSION[$name]);
		session_write_close();
		return true;
	}

	function load($data)
	{
		$data = basename($data, ".php");
		if(preg_match('/[a-zA-Z0-9_-]*/i', $data))
		{
			return $data;
		}
		return $this->_error();
	}

	function encode($str)
	{
		global $config;

		if(empty($config->lkey))
		{
			$passw = '{security_code}';
		}
		else
		{
			$passw = $config->lkey;
		}
		$salt = "Dn8*#2n!9j";
		$len = strlen($str);
		$gamma = '';
		$n = $len>100 ? 8 : 2;
		while( strlen($gamma)<$len )
		{
			$gamma .= substr(pack('H*', sha1($passw.$gamma.$salt)), 0, $n);
		}
		return base64_encode($str^$gamma);
	}

	function getMs($data)
	{
		$date = new DateTime($data);
		return $date->format('U');
	}

	function random_string($length, $chartypes)
	{

	   $chartypes_array=explode(",", $chartypes);
	   $lower = 'abcdefghijklmnopqrstuvwxyz';
	   $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	   $numbers = '1234567890';
	   $special = '^@*+-+%()!?';
	   $chars = "";

	   if(in_array('all', $chartypes_array))
	   {
			 $chars = $lower.$upper.$numbers.$special;
	   }
	   else
	   {
		  if(in_array('lower', $chartypes_array))
				  $chars = $lower;
		  if(in_array('upper', $chartypes_array))
				  $chars .= $upper;
		  if(in_array('numbers', $chartypes_array))
				  $chars .= $numbers;
		  if(in_array('special', $chartypes_array))
				  $chars .= $special;
	   }

	   $chars_length = (strlen($chars) - 1);

	   $string = $chars{rand(0, $chars_length)};

	   for ($i = 1; $i < $length; $i = strlen($string))
	   {
	   $random = $chars{rand(0, $chars_length)};

	   if ($random != $string{$i - 1}) $string .= $random;
	   }

	   return $string;
	}

	function showExtension($file)
	{
		return pathinfo($file, PATHINFO_EXTENSION);
	}

	function paginator($rows, $current, $per, $link)
	{
		$pages = round($rows / $per, 0);
		$backward = $current - 1;
		$forward = $current + 1;
		$last = $pages - 1;

		for($i=1; $i<=$num; $i++)
		{
			if($i > 1)
			{
				$bw = $link.$backward;
			}

			if($current == 1)
			{
				$back = '<li><a><span aria-hidden="true">«</span><span class="sr-only">Previous</span></a></li>';
			}
			else
			{
				$back = '<li><a href="'.$bw.'"><span aria-hidden="true">«</span><span class="sr-only">Previous</span></a></li>';
			}

			if($i == $page)
			{
				$pages .= "<li class=\"active\"><a>{$init}{$i}</a></li>";
			}
			else
			{
				$pages .= "<li><a href=\"{$pre_link}{$prepage}'\">{$init}{$i}</a></li>";
			}

			if($i <= $last)
			{
				$forw = "<li><a href=\"{$pre_link}{$foward}\"><span aria-hidden=\"true\">»</span><span class=\"sr-only\">Next</span></a></li>";
			}
			else
			{
				$forw = "<li><a><span aria-hidden=\"true\">»</span><span class=\"sr-only\">Next</span></a></li>";
			}
		}
		return "<ul class=\"pagination\">{$back}{$pages}{$forw}</ul>";
	}
}
$engine = new engine();

class connection
{
	function cPOST($url, $params, $parse = true)
	{
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        curl_close($curl);

        if ($parse)
		{
            $result = json_decode($result, true);
        }
        if($result) return $result;
		return $this->_error();
	}

	function cGET($url, $params, $parse = true)
	{
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url . '?' . urldecode(http_build_query($params)));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        curl_close($curl);

        if ($parse)
		{
            $result = json_decode($result, true);
        }

        if($result) return $result;
		return $this->_error();
	}
}
$connect = new connection();

class admin
{
	protected $lkey;
	protected $server;

	function __construct($key)
	{
		$this->lkey = $key;
		$this->server = $_SERVER['SERVER_NAME'];
	}

	public function _error($reason)
	{
		return (object)array("error" => $reason);
	}
<<<<<<< HEAD

	function encode($str)
	{
		global $config;

		if(empty($config['lkey']))
		{
			$passw = '{security_code}';
		}
		else
		{
			$passw = $config['lkey'];
		}
		$salt = "Dn8*#2n!9j";
		$len = strlen($str);
		$gamma = '';
		$n = $len>100 ? 8 : 2;
		while( strlen($gamma)<$len )
		{
			$gamma .= substr(pack('H*', sha1($passw.$gamma.$salt)), 0, $n);
		}
		return base64_encode($str^$gamma);
	}

	function getFile($request)
	{
		$archive = @fopen($_SERVER['DOCUMENT_ROOT'] . '/uploads/temp/' . $request['extension'] . '.zip', "w");
		$init = curl_init();
		curl_setopt_array($init, array(
			CURLOPT_URL => "http://api.codeburger.it/?method={$request['method']}&action={$request['action']}&key={$this->lkey}&domain={$this->server}&extension=" . $request['extension'],
			CURLOPT_HEADER => 0,
			CURLOPT_FILE => $archive
		));
		$return = curl_exec($init);
		$json = (object)json_decode($return);
		if($json->error)
		{
			return $json;
		}
			return true;

		curl_close($init);
	}

=======
	
>>>>>>> origin/master
	function unzip($archive, $dest_dir, $files='')
	{
		$zip = new ZipArchive;
		if ($zip->open($archive))
		{
			if(!$files)
			{
				$zip->extractTo($dest_dir);
			}
			else
			{
				$zip->extractTo($dest_dir, $files);
			}
			$zip->close();
			return true;
		}
		return $this->_error();
	}
	function translit($input, $param)
	{
		$arr_rus = array('а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ь', 'ы', 'ъ', 'э', 'ю', 'я', ' ');
		$arr_eng = array('a', 'b', 'v', 'g', 'd', 'e', 'jo', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', '', 'y', '', 'e', 'ju', 'ja', '_');
		if($param == 'lat2cyr')
		{
			return str_replace($arr_eng, $arr_rus, mb_strtolower($input));
		}
		return str_replace($arr_rus, $arr_eng, mb_strtolower($input));
	}

	function perms($file, $perm = 644)
	{
		if(substr(sprintf('%o', fileperms($file)), -3) != $perm)
		{
			return false;
		}
		return true;
	}
}
$admin = new admin($config->lkey);

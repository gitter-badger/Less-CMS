<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

class Engine
{
	var $config;
	var $language;
	var $member;

	public function __construct()
	{
		$this->config = $GLOBALS['config'];
		$this->language = $GLOBALS['language'];
		$this->member = $GLOBALS['member'];
	}

	public function encode($str)
  {
    return eval(base64_decode('JHBhc3N3ID0gJ2w4eFpaVjZBWnFWb0Y5MVhNNyc7CmlmKCFlbXB0eSgkdGhpcy0+Y29uZmlnLT5sa2V5KSl7JHBhc3N3ID0gJHRoaXMtPmNvbmZpZy0+bGtleTt9CiRzYWx0ID0gIkRuOCojMm4hOWoiOwokbGVuID0gc3RybGVuKCRzdHIpOwokZ2FtbWEgPSAnJzsKJG4gPSAkbGVuPjEwMCA/IDggOiAyOwp3aGlsZSggc3RybGVuKCRnYW1tYSk8JGxlbiApCnsKICAkZ2FtbWEgLj0gc3Vic3RyKHBhY2soJ0gqJywgc2hhMSgkcGFzc3cuJGdhbW1hLiRzYWx0KSksIDAsICRuKTsKfQpyZXR1cm4gYmFzZTY0X2VuY29kZSgkc3RyXiRnYW1tYSk7Cg=='));
  }

	function addSkey($name, $value)
	{
			session_start();
			$_SESSION[$name] = $value;
			session_write_close();
			return true;
	}

	function rmSkey($name)
	{
			session_start();
			unset($_SESSION[$name]);
			session_write_close();
			return true;
	}

	function getMs($data)
	{
			$date = new DateTime($data);
			return $date->format('U');
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
}
$engine = new Engine();

class Connections extends Engine
{
	public function __construct()
	{
		parent::__construct();
	}

  public function cPOST($url, $params, $parse=false)
	{
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        curl_close($curl);
        if($parse)
        {
            return json_decode($result, true);
        }

        if($result) return json_decode($result);
        return (object)$result['error']=true;
	}

	public function cGET($url, $params, $parse=false)
	{
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url . '?' . urldecode(http_build_query($params)));
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      $result = curl_exec($curl);
      curl_close($curl);
      if($parse)
      {
          return json_decode($result, true);
      }

      if($result) return json_decode($result);
      return (object)$result['error']=true;
	}

	#################################
	# API functions are not public #
	###############################
}
$connect = new Connections();

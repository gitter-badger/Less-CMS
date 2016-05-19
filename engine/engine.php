<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;}

include_once (CORE    . "core.php");

include_once (DRIVERS . "database.php");

include_once (DRIVERS . "language.php");

include_once (DRIVERS . "auth.php");

include_once (ENGINE  . "vendor/load.php");

include_once (DRIVERS . "templates.php");

include_once (DRIVERS . "connections.php");

/*/
 * Engine function class
/*/
class Engine extends Core
{
  var $config;

  function __construct()
  {
    $this->config = json_decode(file_get_contents(CONFIG . "config.json"));
  }

  public function setConfig()
  {
    return $this->config;
  }

  public function setMember()
  {
    if($_COOKIE['de8f93a28b7d5a9be6b042630fb716fd'])
    {
      $GLOBALS['db']->select("members","id='".intval($_COOKIE['de8f93a28b7d5a9be6b042630fb716fd'])."'");
      $member = $GLOBALS['db']->getObject();
      if($_COOKIE['12d38208eda733ca66a6e9089502d8fc'] == md5($member->login.$GLOBALS['secure_key'].$member->login.$GLOBALS['secure_key'].$member->email))
      {
        return $member;
      }
    }
    return array();
  }

  public function clean($in)
  {
    if (is_array($in) || is_object($in))
    {
      foreach($in as $k => $v)
      {
        if (is_array($v)) $out[basename($k) ] = self::clean($v);
        else $out[basename($k) ] = basename($v, ".php");
      }

      return $out;
    }
    return $in;
  }

  public static function encode($str)
  {
     return eval(base64_decode('JHBhc3N3ID0gJ2xlc3NjbXMtZGVtbyc7IGlmICghZW1wdHkoJEdMT0JBTFNbJ2NvbmZpZyddLT5zZWN1cmVfa2V5KSkgeyAkcGFzc3cgPSAkR0xPQkFMU1snY29uZmlnJ10tPnNlY3VyZV9rZXk7IH0gJHNhbHQgPSAiRG44KiMybiE5aiI7ICRsZW4gPSBzdHJsZW4oJHN0cik7ICRnYW1tYSA9ICcnOyAkbiA9ICRsZW4gPiAxMDAgPyA4IDogMjsgd2hpbGUgKHN0cmxlbigkZ2FtbWEpIDwgJGxlbikgeyAkZ2FtbWEuPSBzdWJzdHIocGFjaygnSConLCBzaGExKCRwYXNzdyAuICRnYW1tYSAuICRzYWx0KSkgLCAwLCAkbik7IH0gcmV0dXJuIGJhc2U2NF9lbmNvZGUoJHN0ciBeICRnYW1tYSk7DQo='));
  }

  public function random_string($length, $chartypes)
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
  public function config($add)
  {
    $config = $GLOBALS['config'];
    foreach ($add as $key => $value)
    {
      $config->$key = $value;
    }
    $handler = @fopen(CONFIG . "config.json", "w");
    fwrite($handler, json_encode($config, JSON_PRETTY_PRINT));
    fclose($handler);
  }

  public static function userName($arr)
  {
    if(empty($arr->fname) && empty($arr->lname))
		{
			return $arr->login;
		}
		elseif (!empty($arr->fname) && empty($arr->lname))
		{
			return $arr->fname . " " . $arr->login;
		}
		else
		{
			return $arr->fname . " " . $arr->lname;
		}
  }
  public function bc()
  {
    $path = $_SERVER['QUERY_STRING'];
    foreach ($_GET as $key => $value)
    {
      $x = explode("$key=$value",$path);
      $link = str_ireplace(array("&&","?&"),array("&","?"),'?'.$x[0].'&'.$key.'='.$value);
      if($key == "user")
      {
        $GLOBALS['db']->select("members", "id='".intval($value)."'");
        $rw = $GLOBALS['db']->getObject();
        $curr .= '<a href="'.$link.'" class="breadcrumb">'.$rw->login.'</a>';
      }
      elseif($key != "id")
      {
        $curr .= '<a href="'.$link.'" class="breadcrumb">'.$GLOBALS['lang']->$value.'</a>';
      }
    }
    return $curr;
  }
  public function cutStr($str, $length=50, $postfix='...')
  {
    if ( strlen($str) <= $length) return $str;

    $temp = substr($str, 0, $length);
    return substr($temp, 0, strrpos($temp, ' ') ) . $postfix;
  }
  public function checkPerm($param)
  {
    $db = $GLOBALS['db'];
    $db->select("members", "id='".$_SESSION['member_id']."'","","","group_id");
    $gid = $db->getObject()->group_id;
    $db->select("member_groups", "id='{$gid}'");
    $dids = explode(";", $db->getObject()->perms);
    if(in_array($param, $dids) || in_array("supersu", $dids)) return true;
    return false;
  }
  public function intSub($value=0)
  {
    if($value < 10000) return $value;
    if($value >= 10000 && $value < 100000) return substr($value, 0, 2)."K";
    if($value >= 100000 && $value < 1000000) return substr($value, 0, 3)."K";
    if($value >= 1000000 && $value < 10000000) return substr($value, 0, 1)."M";
    if($value >= 10000000 && $value < 100000000) return substr($value, 0, 2)."M";
    if($value >= 100000000 && $value < 1000000000) return substr($value, 0, 3)."M";
    if($value >= 1000000000 && $value < 10000000000) return substr($value, 0, 1)."Mld";
    if($value >= 10000000000 && $value < 100000000000) return substr($value, 0, 2)."Mld";
    if($value >= 100000000000 && $value < 1000000000000) return substr($value, 0, 3)."Mld";
    if($value >= 1000000000000) return $value;
  }
  public static function imgExist($src)
  {
    if(!file_exists(ROOT.$src) || is_dir(ROOT.$src)) return "/uploads/system/noimage.jpg";
    return $src;
  }
  public static function mimes()
  {
    $exts['txt'] = "text/plain";
    $exts['html'] = "text/html";
    $exts['htm'] = "text/html";
    $exts['php'] = "text/plain";
    $exts['css'] = "text/css";
    $exts['js'] = "application/x-javascript";
    $exts['jpg'] = "image/jpeg";
    $exts['jpeg'] = "image/jpeg";
    $exts['gif'] = "image/gif";
    $exts['png'] = "image/png";
    $exts['bmp'] = "image/bmp";
    $exts['tif'] = "image/tiff";
    $exts['tiff'] = "image/tiff";
    $exts['doc'] = "application/msword";
    $exts['docx'] = "application/msword";
    $exts['xls'] = "application/excel";
    $exts['xlsx'] = "application/excel";
    $exts['ppt'] = "application/powerpoint";
    $exts['pptx'] = "application/powerpoint";
    $exts['pdf'] = "application/pdf";
    $exts['wmv'] = "application/octet-stream";
    $exts['mpg'] = "video/mpeg";
    $exts['mov'] = "video/quicktime";
    $exts['mp4'] = "video/quicktime";
    $exts['zip'] = "multipart/x-zip,application/zip,application/x-zip-compressed,	application/x-compressed";
    $exts['rar'] = "application/x-rar-compressed";
    $exts['dmg'] = "application/x-apple-diskimage";
    $exts['exe'] = "application/octet-stream";
    $exts['json'] = "application/json";
    $exts['ico'] = "image/ico";
    return $exts;
  }
  public function mime($data)
  {
    $d = explode(",", $data);
    $exts = self::mimes();
    $rt = "";
    foreach ($d as $v)
    {
      if(!empty($v)) $rt[] = $exts[$v];
    }
    return implode(",", $rt);
  }
  function sendFile($file)
  {
    if (file_exists($file))
    {
      if (ob_get_level())
      {
        ob_end_clean();
      }
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename=' . basename($file));
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($file));

      if ($fd = fopen($file, 'rb'))
      {
        while (!feof($fd))
        {
          print fread($fd, 1024);
        }
        fclose($fd);
      }
      exit;
    }
    return false;
  }
  public function unZip($archive, $dest="uploads/temp/")
  {
    $zip = new ZipArchive;
    if ($zip->open($archive) === TRUE)
    {
      $zip->extractTo(ROOT.$dest);
      $zip->close();
      return true;
    }
    return false;
  }
}
$engine   = new Engine();
$config   = $engine->setConfig();
$db       = new $config->database;
$member   = $engine->setMember();
$language = new Language();
$lang     = $language->langPack();
$tpl      = new Template();
if($config->status_captcha) $recaptcha = new \ReCaptcha\ReCaptcha($config->s_key);

include_once (ENGINE . "extensions/autoload.php");
?>

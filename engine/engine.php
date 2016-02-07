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
    $this->config = (object)parse_ini_file(CONFIG . "config.ini");
    if (!is_null($config->scalar))
    {
      echo 'Config file not found';
      exit;
    }
  }

  public function setConfig()
  {
    return $this->config;
  }

  public function setMember()
  {
    if($_SESSION['logged'])
    {
      $GLOBALS['db']->select("members","id={$_SESSION['member_id']}");
      $member = $GLOBALS['db']->getObject();
      $GLOBALS['db']->free();
      return $member;
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

	public function encode($str)
  {
    return eval(base64_decode('JHBhc3N3ID0gJ2w4eFpaVjZBWnFWb0Y5MVhNNyc7CmlmKCFlbXB0eSgkR0xPQkFMU1snY29uZmlnJ10tPmxrZXkpKXskcGFzc3cgPSAkR0xPQkFMU1snY29uZmlnJ10tPmxrZXk7fQokc2FsdCA9ICJEbjgqIzJuITlqIjsKJGxlbiA9IHN0cmxlbigkc3RyKTsKJGdhbW1hID0gJyc7CiRuID0gJGxlbj4xMDAgPyA4IDogMjsKd2hpbGUoIHN0cmxlbigkZ2FtbWEpPCRsZW4gKQp7CiAgJGdhbW1hIC49IHN1YnN0cihwYWNrKCdIKicsIHNoYTEoJHBhc3N3LiRnYW1tYS4kc2FsdCkpLCAwLCAkbik7Cn0KcmV0dXJuIGJhc2U2NF9lbmNvZGUoJHN0cl4kZ2FtbWEpOw=='));
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
  function paginator($rows, $current, $per, $link)
  {
    $pages = ceil($rows / $per) + 1;
    $page = $_GET['page'];
    if(!$page) $page = 1;
    $backward = $page - 1;
    $forward = $page + 1;
    $last = $pages - 1;
    if(AREA == "adminSide") $nuf = "&page=";
    for ($i = 1; $i < $pages; $i++)
    {
      if ($pages > 1)
      {
        $bw = $link.$nuf.$backward;
        if($backward == 1) $bw = $link;
      }
      if ($pages == 1)
      {
        $back = '<li><a class="link_disable"><span aria-hidden="true">«</span><span class="sr-only">Previous</span></a></li>';
      }
      else
      {
        $back = '<li><a href="' . $bw . '"><span aria-hidden="true">«</span><span class="sr-only">Previous</span></a></li>';
      }
      if ($i == $page)
      {
        $list_pages.= "<li><a class=\"current\">{$i}</a></li>";
      }
      else
      {
        if ($i == 1)
        {
          $list_pages.= "<li><a href=\"{$link}\">{$i}</a></li>";
        }
        else
        {
          $list_pages.= "<li><a href=\"{$link}{$nuf}{$i}\">{$i}</a></li>";
        }
      }
      if ($forward <= $last)
      {
        $forw = "<li><a href=\"{$link}{$nuf}{$forward}\"><span aria-hidden=\"true\">»</span><span class=\"sr-only\">Next</span></a></li>";
      }
      else
      {
        $forw = "<li><a class=\"link_disable\"><span aria-hidden=\"true\">»</span><span class=\"sr-only\">Next</span></a></li>";
      }
    }
    return "<ul class=\"pagination paginator\">{$back}{$list_pages}{$forw}</ul>";
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

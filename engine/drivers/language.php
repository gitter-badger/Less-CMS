<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;}
/*
 * Language engine
 */
class Language
{
  var $db;
  var $data;
  var $language;

  function __construct()
  {
    $this->data = new stdClass();
    $this->db   = new MySQL();
  }

  function detect()
  {
    if(empty($_COOKIE['language']))
      return tabgeo($_SERVER['REMOTE_ADDR']);
    return $_COOKIE['language'];
  }

  function getData()
  {
    $this->db->select("extensions", "lang_code = '" . self::detect() . "' AND type = 'langpack'");
    if(!$this->db->numRows())
 		{
 			$this->data->lang = 'English';
 			$this->data->code = 'en';
 		}
 		else
 		{
 			$langParam = $this->db->getObject();
 			$this->data->lang = $langParam->title;
 			$this->data->code = mb_strtolower($langParam->lang_code);
 		}
 		$this->db->free();
 		return $this->data;
  }

  function langPack()
 	{
    $main   = array();
    $custom = array();
    $module = array();

    if(AREA == 'frontSide')
 		{
      $dir = scandir(ROOT . "/languages/" . self::getData()->lang."/");
      foreach($dir as $file)
      {
      	if(!is_dir($file) && !preg_match("/#/", $file) && $file != 'admin.lang')
      	{
          $main = $main + json_decode(file_get_contents(ROOT . "languages/" . self::getData()->lang."/".$file),true);
      	}
      }
 		}
    elseif(AREA == 'adminSide')
 		{
      $json = "";
      $dir = scandir(ROOT . "languages/" . self::getData()->lang."/");

      foreach($dir as $file)
      {
      	if(!is_dir($file) && !preg_match("/#/", $file) && $file != 'site.lang')
      	{
          $main = $main + json_decode(file_get_contents(ROOT . "languages/" . self::getData()->lang."/".$file),true);
      	}
      }
 		}

    $main = (object)$main;
    $this->language = $main;
    return $main;
  }

  function makeList()
 	{
    $langCode = self::getData()->code;
 		if(!empty($_GET))
 		{
 			$backLink = "&backLink=" . str_ireplace(array("=","&"),array("/","-"), $_SERVER['QUERY_STRING']);
 		}
 		else
 		{
 			$backLink = '';
 		}
 		$this->db->select("extensions", "type='langpack'");

 		while($row = $this->db->getObject())
 		{
 			if(mb_strtolower($row->lang_code) == $langCode)
 			{
 				$result .= '<li class="active">
 								 <a>
 								  <i class="mi">label</i>
 									<span>'.$row->title.'</span>
 								 </a>
 							  </li>';
 			}
 			else
 			{
 				if(AREA == 'frontSide')
 				{
 					$result .= '<li>
 						<a href="/index.php?setLang='.$row->lang_code.$backLink.'">
 							<i class="mi">label</i>
 							<span>'.$row->title.'</span>
 						</a>
 					</li>
 					';
 				}
 				elseif(AREA == 'adminSide')
 				{
 					$result .= '<li>
 						<a href="/admin.php?setLang='.$row->lang_code.$backLink.'">
 							<i class="mi">label</i>
 							<span>'.$row->title.'</span>
 						</a>
 					</li>
 					';
 				}
 			}
 		}
 		$this->db->free();
 		return $result;
 	}
}
?>

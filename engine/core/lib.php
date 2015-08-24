<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

class language
{
	var $data;
	var $config;

	function __construct($config)
	{
		$this->db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_NAME);
		$this->db->set_charset("utf8");
		$this->data = new stdClass();
		$this->config = $config;
	}

	function detect()
	{
		require_once ENGINE . "/class/tabgeo_country_v4.php";

		if(empty($_COOKIE['sLang']))
		{
			return tabgeo_country_v4($_SERVER['REMOTE_ADDR']);
		}
		return $_COOKIE['sLang'];
	}

	function getData()
	{
		mysql::select( "extensions", "lang_code = '" . self::detect() . "' AND type = 'langpack'" );

		if( !mysql::numRows() )
		{
			$this->data->lang = 'English';
			$this->data->code = 'en';
		}
		else
		{
			$langParam = mysql::getObject();
			$this->data->lang = $langParam->title;
			$this->data->code = mb_strtolower($langParam->lang_code);
		}
		mysql::free();
		return $this->data;
	}

	function langPack()
	{
		$main = array();
		$custom = array();
		$module = array();

		if(AREA == 'frontSide')
		{
			if(!$mainLang = file_get_contents(ROOT . "/languages/" . self::getData()->lang . "/site.lang"))
			{
				$mainLang = file_get_contents(ROOT . "/languages/English/site.lang");
			}
			$main = json_decode($mainLang, true);
		}
		elseif(AREA == 'adminSide')
		{
			if(!$mainLang = file_get_contents(ROOT . "/languages/" . self::getData()->lang . "/admin.lang"))
			{
				$mainLang = file_get_contents(ROOT . "/languages/English/admin.lang");
			}
			$main = json_decode($mainLang, true);
		}

		if(!$customLang = file_get_contents(ROOT . "/languages/" . self::getData()->lang . "/custom.lang"))
		{
			$customLang = file_get_contents(ROOT . "/languages/English/custom.lang");
		}
		$custom = json_decode($customLang, true);

		if($_GET['action'])
		{
			if(!$moduleLang = file_get_contents(ROOT . "/languages/" . self::getData()->lang . "/" . basename($_GET['action']).".lang"))
			{
				$moduleLang = file_get_contents(ROOT . "/languages/English/" . basename($_GET['action']).".lang");
			}
			if($moduleLang)	$module = json_decode($moduleLang, true);
		}
		return $main + $custom + $module;
	}

	function makeList()
	{
		$langCode = self::getData()->code;
		if(!empty($_GET))
		{
			$backLink = "&backLink=" . implode('/', $_GET);
		}
		else
		{
			$backLink = '';
		}

		mysql::select("extensions", "type='langpack'");
		while($row = mysql::getObject())
		{
			if(mb_strtolower($row->lang_code) == $langCode)
			{
				$result .= '<li class="active">
								 <a href="#">
								  <img src="/templates/' . $this->config->template.'/images/flags/'.mb_strtolower($row->lang_code).'.png">
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
							<img src="/templates/' . $this->config->template.'/images/flags/'.mb_strtolower($row->lang_code).'.png">
							<span>'.$row->title.'</span>
						</a>
					</li>
					';
				}
				elseif(AREA == 'adminSide')
				{
					$result .= '<li>
						<a href="/admin.php?setLang='.$row->lang_code.$backLink.'">
							<img src="/templates/' . $this->config->template.'/images/flags/'.mb_strtolower($row->lang_code).'.png">
							<span>'.$row->title.'</span>
						</a>
					</li>
					';
				}
			}
		}
		mysql::free();

		return $result;
	}
}
$language = new language($config);

require_once ENGINE . "/class/template.class.php";

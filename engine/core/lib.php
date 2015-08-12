<?php
if (!defined('l8xZZV6AZqVoF91XM7')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

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
		if(AREA == 'frontSide')
		{
			if(!include ROOT . "/languages/" . self::getData()->lang . "/site.lng")
			{
				include ROOT . "/languages/English/site.lng";
			}
		}
		elseif(AREA == 'adminSide')
		{
			if(!include ROOT . "/languages/" . self::getData()->lang . "/admin.lng")
			{
				include ROOT . "/languages/English/admin.lng";
			}
		}
		
		if(!include ROOT . "/languages/" . self::getData()->lang . "/custom.lng")
		{
			include ROOT . "/languages/English/custom.lng";
		}
		
		if($_GET['action'])
		{
			if(!include ROOT . "/languages/" . self::getData()->lang . "/" . basename($_GET['action'],'.lng') . ".lng")
			{
				include ROOT . "/languages/English/" . basename($_GET['action'],'.lng') . ".lng";
			}
		}
		
		return $lang;
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
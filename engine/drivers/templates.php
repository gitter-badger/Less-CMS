<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;}
/*
 * Template engine v2.0
 */
class Template
{
	var $dir;
	var $content;
	var $template;
	var $tags = array();
	var $result = array('info'=>'','content'=>'');

	public function __construct()
	{
		switch(AREA)
		{
			case "frontSide":
				$this->dir = ROOT . 'templates/' . $GLOBALS['config']->template . '/';
			break;

			case "adminSide":
				$this->dir = ADMIN . 'template/';
			break;
		}
	}

	public function load($tpl)
	{
		$load = pathinfo($tpl);
		$tpl = basename($tpl,'.'.$load['extension']);
		$tpl = $tpl . '.tpl';
		$tpl_path = $load['dirname'];
		if($load['dirname'] == '.') $tpl_path = '';
		$this->content = file_get_contents($this->dir . $tpl_path . $tpl);

		if (strpos($this->content, "[is=") !== false)
		{
			$this->content = preg_replace_callback("#\\[(is)=(.+?)\\](.*?)\\[/is\\]#is", array(&$this,'check_module'), $this->content);
		}

		if (strpos($this->content, "[not=") !== false)
		{
			$this->content = preg_replace_callback("#\\[(not)=(.+?)\\](.*?)\\[/not\\]#is", array(&$this,'check_module'), $this->content);
		}

		if (strpos($this->content, "[logged=") !== false)
		{
			$this->content = preg_replace_callback("#\\[(logged)=(.+?)\\](.*?)\\[/logged\\]#is", array(&$this,'check_login'), $this->content);
		}

		if (strpos($this->content, "[iscat=") !== false)
		{
			$this->content = preg_replace_callback("#\\[(iscat)=(.+?)\\](.*?)\\[/iscat\\]#is", array(&$this,'check_md_cat'), $this->content);
		}

		if (strpos($this->content, "[notcat=") !== false)
		{
			$this->content = preg_replace_callback("#\\[(notcat)=(.+?)\\](.*?)\\[/notcat\\]#is", array(&$this,'check_md_cat'), $this->content);
		}

		if (strpos($this->content, "[nocats]") !== false)
		{
			$this->content = preg_replace_callback("#\\[nocats\\](.*?)\\[/nocats\\]#is", array(&$this,'nocats'), $this->content);
		}

		if (strpos($this->content, "[error=") !== false)
		{
			$this->content = preg_replace_callback("#\\[(error)=(.+?)\\](.*?)\\[/error\\]#is", array(&$this,'check_error'), $this->content);
		}

		self::setMember();
		self::setLang();
		$this->template = $this->content;
	}

	public function set($tag, $data)
	{
		$this->tags[$tag] = $data;
	}

	private function setLang()
	{
		foreach ($GLOBALS['lang'] as $key => $value)
		{
			self::set('{lang-'.$key.'}', $value);
		}
	}

	private function setMember()
	{
		foreach ($GLOBALS['member'] as $key => $value)
		{
			self::set('{member-'.$key.'}', $value);
		}
	}

	private function nocats($matches = array())
	{
		if(isset($_GET['action']) && !isset($_GET['sub']) && !isset($_GET['param']))
		{
			return $matches[1];
		}
	}

	private function check_md_cat($matches = array())
	{
		global $mod_cat;

		$is 		 = $matches[2];
		$block   = $matches[3];

		if ($matches[1] == "iscat")
		{
			$action = true;
		}
		else
		{
			$action = false;
		}

		$is = explode('|', $is);

		if($action)
		{
			if (in_array($mod_cat, $is))
			{
				return $block;
			}
			return false;
		}
		else
		{
			if (!in_array($mod_cat, $is))
			{
				return $block;
			}
			return false;
		}
	}

	private function check_module($matches = array())
	{
		$module = "";
		$module = $_GET['do'] ?: $_GET['system'];
		$module = $module ?: "main";

		$is 		 = $matches[2];
		$block   = $matches[3];

		if ($matches[1] == "is")
		{
			$action = true;
		}
		else
		{
			$action = false;
		}

		$is = explode('|', $is);

		if($action)
		{
			if (in_array($module, $is))
			{
				return $block;
			}
			return false;
		}
		else
		{
			if (!in_array($module, $is))
			{
				return $block;
			}
			return false;
		}
	}

	private function check_login($matches = array())
	{
		if ($matches[2] == "true")
		{
			$show = true;
		}
		elseif ($matches[2] == "false")
		{
			$show = false;
		}
		if(empty($_SESSION['logged'])) $_SESSION['logged'] = false;

		if ($show === $_SESSION['logged'])
		{
			return $matches[3];
		}
		else
		{
			return " ";
		}
	}

	private function check_error($matches = array())
	{
		if ($matches[2] == "true")
		{
			$show = true;
		}
		elseif ($matches[2] == "false")
		{
			$show = false;
		}
		if(empty($GLOBALS['syserr'])) $GLOBALS['syserr'] = false;

		if ($show === $GLOBALS['syserr'])
		{
			return $matches[3];
		}
		else
		{
			return " ";
		}
	}

	public function compile($tpl)
	{
		foreach($this->tags as $key => $value)
		{
				$find[]    = $key;
				$replace[] = $value;
		}
		$this->template = str_replace($find, $replace, $this->template);
		$this->result[$tpl] .= $this->template;
		self::clear();
	}

	protected function clear()
	{
		$this->tags    = array();
		$this->content = $this->template;
	}
}
?>

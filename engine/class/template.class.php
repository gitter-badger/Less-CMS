<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

class template
{
	var $dir='';
	var $template='';
	var $lang='';
	var $member='';
	var $copy_template='';
	var $data = array();
	var $copy = null;
	var $result = array ('info' => '', 'content' => '', 'plugin' => '');
	
	public function __construct($dir)
	{	
		global $lang;
		global $member;	
		$this->lang = $lang;
		$this->member = $member;
		$this->dir = $dir;
	}
	
	public function load($tpl)
	{
		$tpl_name 	   = basename($tpl);
		$template_name = $tpl_name;
		$tpl_name 	   = explode('.', $tpl_name);
		$extension	   = strtolower(end($tpl_name));
		
		if ($extension != "tpl")
		{            
            self::report("Extension \"{$extension}\" is not allowed!");           
        }
		
		$path = @parse_url($tpl);
		$path = preg_replace('/' . $template_name . '/', '', $path['path']);
		
		if ($path AND $path != ".")
		{
            $tpl_load = $path . "/" . $template_name;
		}
		/*
		if (empty($template_name) or !file_exists($this->dir . "/" . $template_name))
		{
            self::report("Template not found: " . str_replace(ROOT, '', $this->dir) . $template_name);
        }*/
		$this->template = file_get_contents($this->dir . $template_name);
		
		if (strpos($this->template, "[is=") !== false)
		{
            $this->template = preg_replace_callback("#\\[(is)=(.+?)\\](.*?)\\[/is\\]#is", array(&$this,'check_module'), $this->template);
        }
		
		if (strpos($this->template, "[not=") !== false)
		{
            $this->template = preg_replace_callback("#\\[(not)=(.+?)\\](.*?)\\[/not\\]#is", array(&$this,'check_module'), $this->template);
        }
		
		if (strpos($this->template, "[logged=") !== false)
		{
            $this->template = preg_replace_callback("#\\[(logged)=(.+?)\\](.*?)\\[/logged\\]#is", array(&$this,'check_login'), $this->template);
        }
		
		$this->copy_template = self::setLang();
		$this->copy_template = self::setMember();
		$this->copy_template = $this->template;
				
		return $this->template;
	}
	
	function setLang()
	{
		foreach ($this->lang as $key => $value)
		{
			self::set('{lang-'.$key.'}', $value);
		}	
	}
	
	function setMember()
	{
		foreach ($this->member as $key => $value)
		{
			self::set('{member-'.$key.'}', $value);
		}
	}
	
	function check_module($matches = array())
    {
        global $module;
        
        $is = $matches[2];
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
	
	function check_login($matches = array())
    {
        global $logged;
        
        if ($matches[2] == "true")
		{
            $show = true;
        }
		elseif ($matches[2] == "false")
		{
            $show = false;
        }
        if(empty($logged)) $logged = false; elseif($logged == 'true') $logged = true;
					
        if ($show === $logged)
		{
            return $matches[3];
        }
		else
		{
            return " ";          
        }
    }
	
	function set($tag, $var)
    {
		if(is_array($var) && !empty($var))
		{
			foreach($var as $key => $value)
			{
				self::set($key, $value);
			}
		}
		else
		{
			$this->data[$tag] = str_ireplace("{include", "&#123;include", $var);
		}        
    }
	
	function compile($tpl)
    {		
		foreach ($this->data as $key => $value)
		{
			$find[]    = $key;
			$replace[] = $value;
		}
		
		$this->copy_template = str_replace($find, $replace, $this->copy_template);
		
		if (isset($this->result[$tpl]))
		{
            $this->result[$tpl] .= $this->copy_template;
		}
		else
		{
            $this->result[$tpl] = $this->copy_template;
		}
		
		self::_clear();
	}
	
	function _clear()
    {
        $this->data          = array();
        $this->block_data    = array();
        $this->copy_template = $this->template;
    }
    
    function clear()
    {
        $this->data          = array();
        $this->copy_template = null;
        $this->template      = null;
    }
    
    function global_clear()
    {
        $this->data          = array();
        $this->result        = array();
        $this->copy_template = null;
        $this->template      = null;
    }
	
	private function report($reason)
	{
		$title = 'Template error';
		$err_type = $reason;
		
		if(!include $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php")
		{
			echo '<b>Error</b> script not found!';
		}
		exit;
	}
}



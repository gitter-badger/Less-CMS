<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

if(!isset($_POST['config']))
{
    $sDir = ROOT . '/templates/';
	$scan = scandir($sDir);
	foreach($scan as $dir)
	{
		if(!in_array($dir,array(".","..")) && is_dir($sDir.$dir))
		{
			if($dir == $config->template)
				$tList .= '<option value="'.$dir.'" selected>'.$dir.'</option>';
			else
				$tList .= '<option value="'.$dir.'">'.$dir.'</option>';
		}
	}
	$title = $lang['system_config'];
	$tpl->load('settings.tpl');
		$tpl->set("{cf_title}", $config->title);
		$tpl->set("{cf_description}", $config->description);
		$tpl->set("{cf_keywords}", $config->keywords);
		$tpl->set("{cf_domain}", $config->domain);
		$tpl->set("{cf_lkey}", $config->lkey);
		$tpl->set("{cf_templates_list}", $tList);
		$tpl->set("{captcha_pkey}", $config->p_key);
		$tpl->set("{captcha_skey}", $config->s_key);
		$tpl->set("{smtp_host}", $config->smtp_host);
		$tpl->set("{smtp_port}", $config->smtp_port);
		$tpl->set("{smtp_user}", $config->smtp_user);
		$tpl->set("{smtp_pass}", $config->smtp_pass);
		$tpl->set("{custom_link}", $settingsCustomLink);
		$tpl->set("{custom}", $settingsCustom);
		
		$tpl->set("{vk-app_id}", $config->vk['app_id']);
		$tpl->set("{vk-secur_key}", $config->vk['secur_key']);
		$tpl->set("{fb-app_id}", $config->fb['app_id']);
		$tpl->set("{fb-secur_key}", $config->fb['secur_key']);
		
	$tpl->compile('content');
}
elseif(isset($_POST['config']))
{
	$_POST['config'] = $engine->clean($_POST['config']);
	
	
	$handler = @fopen(CONFIG . "config.ini", "w");
		
	fwrite($handler, "version = \"{$config->version}\"\n\n");
	
	foreach ($_POST['config'] as $key => $value)
	{
		if(!is_array($value))
		{
			fwrite($handler, "{$key} = \"{$value}\"\n\n");
		}
		else
		{
			foreach ($value as $sec_key => $sec_value)
			{
				fwrite($handler, "{$key}[{$sec_key}] = \"{$sec_value}\"\n\n");
			}
		}
	}
		
	fclose($handler);
	
	$core->mess($lang['success'], $lang['config_saved']);
	
}
else
{
	$core->mess($lang['error'], $lang['404']);
}
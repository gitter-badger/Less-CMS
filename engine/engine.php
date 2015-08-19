<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

require_once CORE 	. 'core.php';
require_once CORE 	. 'lib.php';
require_once ENGINE . 'class/mail.class.php';
#$recaptcha = new \ReCaptcha\ReCaptcha($config->s_key);
$db = new mysql(); // Default database used in the script
$lang = $language->langPack();
$core = new Core();
$member = $core->setUser();
$tpl = new template(ROOT . '/templates/' . $config->template . '/');

if(isset($_GET['setLang']))
{	
	if(setcookie("sLang", $_GET['setLang']))
	{
		header("Location: /" . $_GET['backLink']);
	}
}

if($_POST['language'] == 'getList')
{
	echo json_encode($lang);	
}

$dir = scandir(ENGINE . 'extensions/');
foreach($dir as $file)
{
	if(!is_dir($file) && !preg_match("/#/", $file))
	{
		include ENGINE . 'extensions/' . $file;
	}
}

if(!isset($_GET))
{
	$siteTitle = $config->title;	
	$module = 'main';
}
else
{
	$action = $core->load($_GET['action']);
		
	switch($action)
	{
		case 'signout':
			include ENGINE . '/core/membership/signout.php';
			$module = 'signout';
		break;
			
		case 'signin':
			include ENGINE . '/core/membership/signin.php';	
			$module = 'signin';		
		break;
			
		case 'signup':
			include ENGINE . '/core/membership/signup.php';	
			$module = 'signup';		
		break;
			
		case 'signup':
			include ENGINE . '/core/membership/signup.php';	
			$module = 'signup';		
		break;
		
		default:
			
			$db->select("extensions", "link = '{$action}' AND public = '1' ");
		
			if($db->numRows())
			{
				$obj = $db->getObject();	
				$module = $obj->link;
				if(include ENGINE . "modules/{$obj->link}.php")
				{
					$siteTitle = $title . ' - ' . $config->title;
					$module = $obj->link;
				}
				$db->free();
			}
			else
			{
				if(include ENGINE . "plugins/{$action}.php")
				{
					$siteTitle = $title . ' - ' . $config->title;	
					$module = $action;
				}
				$core->mess($lang['error'], $lang['404']);
				$siteTitle = $lang['error'] . ' - ' . $config->title;
			}
			
		break;
	}
}

$headers  = $core->headers('description', $config->description);
$headers .= $core->headers('keywords', $config->keywords);
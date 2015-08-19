<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

require_once ENGINE . 'core/core.php';
require_once CORE 	. 'lib.php';
require_once ENGINE . '/class/mail.class.php';
require_once ENGINE . '/class/tabgeo_country_v4.php';
$db = new mysql(); // Default database used in the script
$lang = $language->langPack();
$core = new Core();
$member = $core->setUser();
$tpl = new template(ADMIN . 'template/');



if($_POST['language'] == 'getList')
{
	echo json_encode($lang);	
}

$dir = scandir(ADMIN . 'extensions/');
foreach($dir as $file)
{
	if(!is_dir($file) && !preg_match("/#/", $file))
	{
		include "extensions/{$file}";
	}
}

if(isset($_GET['setLang']))
{
	if(!empty($_GET['backLink']))
	{
		$bLink = explode('/', $_GET['backLink']);
		$bLink = implode('&', $bLink);
		$bLink = explode('^', $bLink);
		$bLink = '?' . implode('=', $bLink);
	}
	
	if(setcookie("sLang", $_GET['setLang']))
	{
		header("Location: /admin.php" . $bLink);
	}
}
elseif(isset($_GET['action']))
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
			
			$db->select("extensions", "`link` = '{$action}' AND `type` = 'module' ");
			
			if($db->numRows())
			{
				$obj = $db->getObject();
				$module = $obj->link;
				if(include ENGINE . "/admin/modules/{$obj->link}.php")
				{
					$module = $isModule->link;
					$siteTitle = $title . ' - ' . $config->title;
				}
				else
				{
					$core->mess($lang['error'], $lang['m_file_404']);
					$siteTitle = $lang['error'] . ' - ' . $config->title;
				}
				$db->free();
			}
			else
			{
				$core->mess($lang['error'], $lang['404']);
				$siteTitle = $lang['error'] . ' - ' . $config->title;
			}
			
		break;
	}
}
elseif(isset($_GET['system']))
{
	
	$system = $core->load($_GET['system']);
		
	switch($system)
	{
		case 'settings':
		
			include ENGINE . "/admin/system/settings.php";
			$siteTitle = $title . ' - ' . $config->title;
			
		break;
		
		case 'updateavatar':
		
			include ENGINE . "/admin/system/up_avatar.php";
			$siteTitle = $title . ' - ' . $config->title;
			
		break;
		
		case 'status':
		
			include ENGINE . "/admin/system/status.php";
			$siteTitle = $title . ' - ' . $config->title;
			
		break;
		
		case 'cherryHub':
		
			include ENGINE . "/admin/system/cherryHub.php";
			$siteTitle = $title . ' - ' . $config->title;
			
		break;
		
		case 'update':
		
			include ENGINE . "/admin/system/engineUpdate.php";
			$siteTitle = $title . ' - ' . $config->title;
			
		break;
		
		default:
		
			$core->mess($lang['error'], $lang['404']);
			$siteTitle = $lang['error'] . ' - ' . $lang['404'] . ' - ' . $config->title;
			
		break;
	}
}
elseif(isset($_GET['user']))
{
	include ENGINE . '/admin/modules/user_manager.php';	
	$siteTitle = $title . ' - ' . $config->title;	
}
else
{
	$siteTitle = $config->title;	
	$module = 'main';
}

if(!$admin->perms(ROOT . '/.htaccess'))
{
	$perms_error[] = ROOT . '/.htaccess';
}

/*Notification*/

foreach($perms_error as $value)
{
	$notif .= $core->notif('error', sprintf($lang['perm_problems'], $value, 644));
}
if(empty($notif)) $notif=$core->notif('', $lang['no_notif']);
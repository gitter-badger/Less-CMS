<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

require_once CORE 	. 'core.php';
require_once CORE 	. 'lib.php';
require_once CORE 	. 'auth.php';
#$recaptcha = new \ReCaptcha\ReCaptcha($config->s_key);
$db = new mysql(); // Default database used in the script
$lang = $language->langPack();
$core = new Core();
$member = $core->setUser();
$tpl = new Template();

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

if(!$_GET)
{
	$siteTitle = $config->title;
	$module = 'main';
}
else
{
	$action = $core->load($_GET['action']);

	switch($action)
	{
		case 'signin':
			$module = 'signin';
			$auth = new Auth($module);
		break;

		case 'signout':
			$module = 'signout';
			$auth = new Auth($module);
		break;

		case 'signup':
			$module = 'signup';
			$auth = new Auth($module);
		break;

		default:

			$db->select("extensions", "link = '{$action}' AND public = '1' ");

			if($db->numRows())
			{
				$row = $db->getObject();
				$db->free();

				$module = $row->link;
				if(include ENGINE . "modules/{$row->link}.php")
				{
					$siteTitle = $title . ' - ' . $config->title;
					$module = $row->link;
				}
				else
				{
					$core->mess($lang['error'], $lang['404']);
					$siteTitle = $lang['error'] . ' - ' . $config->title;
				}

			}
			else
			{
				if(include ENGINE . "plugins/{$action}.php")
				{
					$siteTitle = $title . ' - ' . $config->title;
					$module = $action;
				}
				else
				{
					$core->mess($lang['error'], $lang['404']);
					$siteTitle = $lang['error'] . ' - ' . $config->title;
				}
			}

		break;
	}
}

$headers  = $core->headers('description', $config->description);
$headers .= $core->headers('keywords', $config->keywords);

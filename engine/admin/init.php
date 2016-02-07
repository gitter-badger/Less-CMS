<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;}

// Admin side init script
$module = "";

include_once (ENGINE  . "engine.php");

if(isset($_GET['setLang']))
{
	if(!empty($_GET['backLink']))
	{
		$bLink = '?' . str_ireplace(array("/","-"),array("=","&"), $_GET['backLink']);
	}

	if(setcookie("language", $_GET['setLang']))
	{
		header("Location: /admin.php" . $bLink);
	}
}

if($_POST['language'] == 'getList')
 echo json_encode($lang);


if(!$_GET || empty($_GET))
{
	$site_title = $config->title;
	$module = 'main';
}
else
{
  if (isset($_GET['do']))
  {
    switch (basename($_GET['do'], ".php"))
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

				$module = $db->real_escape_string($_GET['do']);
				$db->select("extensions", "link = '{$db->real_escape_string($_GET['do'])}' AND public = '1' ");

				if($db->numRows())
				{
					$row = $db->getObject();
					$db->free();
					if(include ADMIN . "modules/{$row->link}.php")
					{
						$site_title = $title . ' - ' . $config->title;
					}
					else
					{
						$core->mess($lang->error, $lang->not_found, "/admin.php");
						$site_title = $lang->error . ' - ' . $config->title;
					}

				}
				else
				{
					$core->mess($lang->error, $lang->not_found, "/admin.php");
				}

			break;
    }
  }

	if (isset($_GET['system']))
  {
    switch (basename($_GET['system'], ".php"))
    {
			case 'settings':

				$module = "settings";
				include ENGINE . "/admin/system/settings.php";
				$site_title = $title . ' - ' . $config->title;

			break;

			case 'status':

				$module = "status";
				include ENGINE . "/admin/system/status.php";
				$site_title = $title . ' - ' . $config->title;

			break;

			case 'modules':

				$module = "modules";
				include ENGINE . "/admin/system/modules.php";
				$site_title = $title . ' - ' . $config->title;

			break;

			default:

				$core->mess($lang->error, $lang->not_found, "/admin.php");
				$site_title = $lang->error . ' - ' . $lang->not_found . ' - ' . $config->title;

			break;
    }
  }
}

$db->select("extensions","type='module'");
while ($md = $db->getObject())
{
	$t = $md->link;
	if($module == $md->link)
		$main_modules .= '<li class="active"><a href="?do='.$md->link.'"><i class="mi">label</i>'.$lang->$t.'</a></li>';
	else
		$main_modules .= '<li><a href="?do='.$md->link.'"><i class="mi">label</i>'.$lang->$t.'</a></li>';
}
?>

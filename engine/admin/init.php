<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;}

// Admin side init script
$module = "";

include_once (ENGINE  . "engine.php");

$diff = time() - $_SESSION['last_updcheck'];
if($diff >= 3600)
{
	$_SESSION['last_updcheck'] = time();
	unset($_SESSION['upd_av']);
  $request = $connect->api();
	if($request->version > $config->version)
	{
		$_SESSION['upd_av'] = $request->version;
		$dashboard .= '
		  <div class="col s12">
		    <div class="col-md-12 tile notif"><i class="mi">system_update_alt</i>'.$lang->update_available.' '.$request->version.' <a href="?system=status">'.$lang->update.'</a></div>
		  </div>';
	}
}
elseif(isset($_SESSION['upd_av']))
{
	if($_SESSION['upd_av'] > $config->version)
	{
		$dashboard .= '
		  <div class="col s12">
		    <div class="col-md-12 tile notif"><i class="mi">system_update_alt</i>'.$lang->update_available.' '.$_SESSION['upd_av'].' <a href="?system=sys_status">'.$lang->update.'</a></div>
		  </div>';
	}
}
if(!empty($_GET))
{
	$breadcrumbs = '
	<nav class="breadcrumbs hide_sm">
	  <div class="nav-wrapper">
	    <div class="col s12">
			<a href="/admin.php" class="breadcrumb">'.$lang->home.'</a>
	      '.$engine->bc().'
	    </div>
	  </div>
	</nav>';
}

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

				$module = $_GET['do'];
				$db->select("extensions", "link = '{$_GET['do']}' AND public = '1' ");

				if($db->numRows())
				{
					$row = $db->getObject();
					
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

			case 'sys_status':

				$module = "sys_status";
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
		$main_modules .= '<li class="active"><a href="?do='.$md->link.'">'.$lang->$t.'</a></li>';
	else
		$main_modules .= '<li><a href="?do='.$md->link.'">'.$lang->$t.'</a></li>';
}
?>

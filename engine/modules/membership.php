<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

define("EXT", dirname(__FILE__) . "/membership/ext/" . basename($_GET['case'],".php") . ".php"); // Update later
$db->select("members", "login='{$db->real_escape_string($_GET['case'])}'");
$user = $db->getObject();
$nRows = $db->numRows();
$db->free();

$dir = scandir(ENGINE . 'modules/membership/components/');
foreach($dir as $file)
{
	if(!is_dir($file) && !preg_match("/#/", $file))
	{
		include_once ENGINE . 'modules/membership/components/' . $file;
	}
}

switch($_GET['case'])
{
	case "settings":
		include 'membership/settings.php';
	break;

	default:
		if($nRows == 1)
		{
			require_once "membership/profile.php";
		}
		else
		{
			if(!file_exists(EXT))
			{
				$title = $lang->error;
				$core->mess($lang->error, $lang->not_found);
			}
			else
			{
				include EXT;
			}
		}
	break;
}
if(!$_SESSION['logged'])
{
	header("Location: /");
	exit;
}
if(empty($_GET['case']))
{
	header("Location: /membership/" . $member->login);
}

<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

define ( 'PHOTO', ROOT . '/uploads/avatars/' . $member->login . '/' );

switch($_GET['case'])
{
	case "new":
		include 'membership/add.php';
	break;

	case "block":
		$data['status'] = "-1";
		$db->update("members", $data, "id='{$db->real_escape_string($_GET['user'])}'");
		header("Location: ?do=membership");
	break;

	case "unblock":
		$data['status'] = "1";
		$db->update("members", $data, "id='{$db->real_escape_string($_GET['user'])}'");
		header("Location: ?do=membership");
	break;

	case "remove":
		include 'membership/remove.php';
	break;
}

if(!isset($_GET['user']) && !$_POST)
{
	include 'membership/list.php';
}
else
{
	if(!is_numeric($_GET['user'])){$core->mess($lang->error, $lang->no_member,"?do=membership");$_GET['user'] = '';}
	$id = $_GET['user'];
	$db->select("members", "id = '{$_GET['user']}'");
	$user = $db->getObject();

	if(!$db->numRows())
	{
		$db->free();
		$core->mess($lang->error, $lang->no_member,"?do=membership");
	}
	elseif(!$_GET['sub'])
	{
	$db->free();
			require_once "membership/profile.php";
	}
}

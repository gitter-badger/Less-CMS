<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}


if($logged != 'true' && !$_GET['sub'])
{
	$email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
	$password = $engine->encode($_POST['password']);
	$db->select("members", "email = '{$email}' AND password = '{$password}'");
	if($db->numRows())
	{
		$login_member = $db->getObject();
		if($engine->addSkey('logged','true') && $engine->addSkey('member_id',$login_member->id))
		{
			$engine->addSkey('social','false');
			header("Location: /");
		}
	}
	else
	{
		$core->mess($lang['error'], $lang['nomember']);
	}
	$db->free();
}
elseif($logged != 'true' && !empty($_GET['sub']))
{
	switch ($_GET['sub'])
	{
		case vk:
			include "platforms/vk.php";
		break;

		case facebook:
			include "platforms/facebook.php";
		break;
	}
}
else
{
	$core->mess($lang['error'], $lang['no_member']);
}

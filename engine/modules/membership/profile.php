<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

if(!isset($_GET['param']))
{
	$tpl->load("profile.tpl");
}
else
{
	if(!file_exists(EXT))
	{
		$core->mess($lang['error'], $lang['404']);
	}
	else
	{
		include EXT;
	}
}
$title = $user->login;
foreach($user as $key => $value)
{
	$tpl->set('{user-' . $key . '}', $value);
}
foreach($mdata as $key => $value)
{
	$tpl->set('{mdata-' . $key . '}', $value);
}

$tpl->compile('content');

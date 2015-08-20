<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

$email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
	
$password = $engine->encode($_POST['password']);

$db->select("members", "email = '{$email}' and password = '{$password}'");

if(!$db->numRows())
{
	$db->free();
	$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
	if ($resp->isSuccess())
	{
		if($db->insert("members", "`group_id`, `password`, `email`, `status`", "'0','{$password}','{$email}','0'"))
		{
			$db->free();
			$db->select("members", "email = '{$email}' and password = '{$password}'");
			$loginMember = $db->getObject();
			$db->free();
			//sendSuccess();
			if($engine->addSkey('logged','true') === true && $engine->addSkey('member_id',$loginMember['id']) === true)
			{
				header("Location: /");
			}
		}
	}
	
	
	
}
else
{
	$main = $core->mess($lang['error'], $lang['member_registered']);
}
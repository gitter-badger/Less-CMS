<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}


if(!$_POST)
{
	$siteTitle = $lang['signup'] . ' - ' . $config->title;
	$moduleTitle = $lang['signup'];
	
	$tpl->load("signup.tpl");
	$tpl->set("{fname}", '');
	$tpl->set("{lname}", '');
	$tpl->set("{email}", '');
	$tpl->set("{social_datas}", '');
	$tpl->compile('content');	
}
else
{
	if($logged != 'true')
	{
		if($_POST['uid'])
		{
			include 'signup/social.php';
		}
		else
		{
			include 'signup/default.php';			
		}
	}
}
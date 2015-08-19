<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

if(!$_POST)
{
	msgDel();
}
else
{
	$db->select("adm_modules","");
	include ROOT . '/engine/admin/uninstall/uni-'.$_POST['link'].'.php';
	
	if($step = 'last')
	{
		if(unlink($_SERVER['DOCUMENT_ROOT'] . '/uninstall/uni-'.$to_dell['link'].'.php'))
			echo '{"stat":"true"}';
		else
			echo '{"stat":"false"}';
	}
}
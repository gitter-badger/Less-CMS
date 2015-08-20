<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

if($_SESSION['logged'] == true)
{
	if($engine->rmSkey('logged') && $engine->rmSkey('member_id'))
	{
		header("Location: /");
	}
}
else
{
	header("Location: /");	
}
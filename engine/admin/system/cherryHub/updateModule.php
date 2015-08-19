<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

$unzip = $admin->unzip(ROOT . '/uploads/temp/' . $ext . '.zip', ROOT);
if($unzip->error)
{
	echo $unzip->error;
	exit;	
}
else
{
	require_once ROOT . '/update.php';
}
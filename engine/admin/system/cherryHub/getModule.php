<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

$ext = $_POST['extension'];
$params = array(
				"method" => "cherryPie",
				"action" => "getExtension",
				"key" => $config->lkey,
				"domain" => $_SERVER['SERVER_NAME'],
				"extension" => $ext
			);
			
$data = $admin->getFile($params);
if($data->error)
{
	echo $data->error;
	exit;
}
else
{
	require_once 'installModule.php';
}
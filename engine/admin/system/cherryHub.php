<?php
if (!defined('l8xZZV6AZqVoF91XM7')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

if(!$_GET['sub'] && !$_POST['sub'])
{
	require_once 'cherryHub/showMain.php';
}
elseif($_POST['sub'])
{
	switch($_POST['sub'])
	{
		case get:
			require_once 'cherryHub/getModule.php';
		break;
		case update:
			require_once 'cherryHub/updateModule.php';
		break;
	}
}
elseif($_GET['sub'])
{
	switch($_GET['sub'])
	{
		case remove:
			require_once 'cherryHub/removeModule.php';
		break;
	}
}
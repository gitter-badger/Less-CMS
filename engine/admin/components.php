<?php
if (!defined('l8xZZV6AZqVoF91XM7')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}


$dir = scandir(ENGINE.'/admin/components/');

foreach($dir as $file)
{
	if(!is_dir($file) && !preg_match("/#/", $file))
	{
		include ENGINE . "/admin/components/{$file}";
	}
}
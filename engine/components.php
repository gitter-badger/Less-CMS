<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

$dir = scandir(ENGINE . 'components/');

foreach($dir as $file)
{
	if(!is_dir($file) && !preg_match("/#/", $file))
	{
		include "components/{$file}";
	}
}
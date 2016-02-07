<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;}

$dir = scandir(dirname(__FILE__));
foreach($dir as $file)
{
  if(!is_dir($file) && !preg_match("/#/", $file) && $file !== 'autoload.php' && $file !== '.htaccess')
  {
    include basename($file);
  }
}

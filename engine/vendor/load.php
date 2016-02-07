<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;}

$dir = scandir(ENGINE . 'vendor');
class Vendors
{
  var $loading;

  function load($dir)
  {
    $indir = ENGINE . 'vendor';
    $this->loading = new stdClass();
    foreach($dir as $file)
    {
      $path = pathinfo($indir."/".$file);
      if($file !== "." && $file !== ".." && $file !== "load.php")
      {
        if(is_dir($indir."/".$file))
        {
          $dir = scandir($indir."/".$file);
          foreach ($dir as $inc)
          {
            $filename = basename($inc,".php");
            if($filename === "autoload")
            {
              if(include($indir."/".$file."/".$inc))
              {
                $this->loading->$filename = "OK";
              }
              else
              {
                $this->loading->$filename = "ERROR";
              }
            }
          }
        }
      }
    }
    if(empty((array)$this->loading)) $this->loading->error = "Not found vendor plugins";
  }
}
$vendor = new Vendors();
$vendor->load($dir);
?>

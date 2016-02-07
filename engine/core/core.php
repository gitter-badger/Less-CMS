<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;}
/*
 * Engine core class
 */
class Core
{

  public function __call($name, $arguments)
  {
    echo "Function '$name' with parameters '" . implode(', ', $arguments). "' not found\n";
  }

  function __construct()
  {

  }

  protected function getDatabases()
  {
    $dbs= new stdClass();
    $dir = scandir(DRIVERS . 'database/');
    foreach($dir as $file)
    {
    	if(!is_dir($file) && !preg_match("/#/", $file))
    	{
        $filename = basename($file,".php");
        $dbs->$filename = DRIVERS . "database/".basename($file);
    	}
    }
    return $dbs;
  }

  public function mess($title, $text, $back_link=false)
	{
		if (!class_exists('template')){return false;}
		$tpl 	                = $GLOBALS['tpl'];
		$lang 	              = $GLOBALS['lang'];
		$member 	            = $GLOBALS['member'];
		$config 	            = $GLOBALS['config'];
		$GLOBALS['siteTitle'] = $title . ' - ' . $config->title;

		$back_link = $back_link ? $back_link : '/';

		$tpl->load( 'mess.tpl' );

		$tpl->set( '{title}', $title );
		$tpl->set( '{text}', $text );
		$tpl->set( '{back_link}', $back_link );

		$tpl->compile( 'info' );
	}
}
$core = new Core;
?>

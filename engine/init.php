<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;}

// Front side init script

include_once (ENGINE  . "engine.php");

if(isset($_GET['setLang']))
{
	if(setcookie("language", $_GET['setLang']))
	{
		header("Location: /" . $_GET['backLink']);
	}
}
if($_POST['language'] == 'getList')
{
	echo json_encode($lang);
}

if(!$_GET)
{
	$siteTitle = $config->title;
	$module = 'main';
}
else
{
  if (isset($_GET['do']))
  {
		$action = basename($_GET['do']);

    switch ($action)
    {
      case 'signin':
        $module = 'signin';
        $auth = new Auth($module);
      break;

      case 'signout':
        $module = 'signout';
        $auth = new Auth($module);
      break;

      default:
        $db->select("extensions", "link = '{$action}' AND public = '1' ");

        if ($db->numRows())
        {
          $row = $db->getObject();
          
          $module = $row->link;
          if (include ENGINE . "modules/{$row->link}.php")

          {
            $siteTitle = $title . ' - ' . $config->title;
            $module = $row->link;
          }
          else
          {
            $core->mess($lang->error, $lang->not_found);
						$syserr = true;
          }
        }
        else
        {
          if (include ENGINE . "plugins/{$action}.php")

          {
            $siteTitle = $title . ' - ' . $config->title;
            $module = $action;
          }
          else
          {
            $core->mess($lang->error, $lang->not_found);
						$syserr = true;
          }
        }
      break;
    }
  }
}
?>

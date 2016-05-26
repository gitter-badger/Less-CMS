<?php
if (!defined('E_DEPRECATED'))
{
  @error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
  @ini_set('error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE);
}
else
{
  @error_reporting(E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE);
  @ini_set('error_reporting', E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE);
}
@ini_set('display_errors', true);
@ini_set('html_errors', false);
define('LessCMS-Secure', true);
define('AREA', 'adminSide');
define('ROOT', dirname(__FILE__)."/");
define('ENGINE', ROOT . 'engine/');
define('ADMIN', ENGINE."admin/");
define('CONFIG', ENGINE . 'configs/');
define('CORE', ENGINE . 'core/');
define('DRIVERS', ENGINE . 'drivers/');
define('MODULES', ADMIN . 'modules/');
define('EXT', ADMIN . 'extensions/');
define('UPL', ROOT . 'uploads/');
if(!file_exists(ROOT."engine/configs/config.json") || !file_exists(ROOT."engine/configs/drivers/MySql.json"))
 header("Location: /install/install.php");
session_start();
$dashboard = '';
$siteTitle = '';
$main_modules = '';
$syserr = false;
include ADMIN . "init.php";
date_default_timezone_set($config->timezone);
$idmember = $_SESSION['member_id'];
$logged   = $_SESSION['logged'];
$_GET = $engine->clean($_GET);
include_once ADMIN."extensions/autoload.php";

if(!$_SESSION['logged'])
{
  if(!$_POST['AJAX'] && $_GET['do'] != "signin")
  {
    $tpl->load('login.tpl');

    foreach($extions as $key => $value)
    {
      $tpl->set('{ext-' . $key . '}', $value);
    }
    $mode_on = "";
    if($config->debug) $mode_on = '<span>Debug mode</span>';
    if($config->offline) $mode_on = '<span>Offline mode</span>';
    if($config->debug && $config->offline) $mode_on = '<span>Offline & debug</span>';

    $tpl->set('{title}', $site_title);
    $tpl->set('{debug_mod_notice}', $config->debug ? $lang->debug_mode_active:"");
    $tpl->set('{page_title}', $title ?: $lang->dashboard);
    $tpl->set('{lang}', $language->getData()->code);
    $tpl->set('{langs_list}', $language->makeList());
    $tpl->set('{THEME}', '/engine/admin/template');
    $tpl->set('{plugin}', $tpl->result['plugin']);
    $tpl->set('{info}', $tpl->result['info']);
    $tpl->set('{content}', $tpl->result['content']);
    $tpl->set('{upl}', '/uploads/');
    $tpl->set('{photos}', '/uploads/photos/');
    $tpl->set('{loading_time}', time() - $_SERVER['REQUEST_TIME']);
    $tpl->set('{dashboard}', $dashboard);
    $tpl->set('{main_modules}', $main_modules);
    $tpl->set('{mode_on}', $mode_on);
    $tpl->set('{error}', $_SESSION["last_err"]);
    if(isset($_SESSION["last_err"]) )unset($_SESSION["last_err"]);
    $tpl->compile('main');
    echo $tpl->result['main'];
  }
  elseif (!$_POST['AJAX'] && $_GET['do'] == "signin")
  {
    header("Location: /admin.php");
  }
  if(isset($_POST['two_steps']))
  {
    $auth = new Auth();
    if(isset($_POST['login']) && !empty($_POST['login']))
    {
      echo json_encode($auth->getMemData());
    }
    elseif (isset($_POST['email']) && !empty($_POST['email']))
    {
      echo json_encode($auth->getMemData());
    }
  }
}
else
{
  if($engine->checkPerm(array("all","moder")))
  {
    if(!$_POST['AJAX'])
    {
      $tpl->load('main.tpl');

      foreach($extions as $key => $value)
      {
        $tpl->set('{ext-' . $key . '}', $value);
      }
      $mode_on = "";
      if($config->debug) $mode_on = '<span>Debug mode</span>';
      if($config->offline) $mode_on = '<span>Offline mode</span>';
      if($config->debug && $config->offline) $mode_on = '<span>Offline & debug</span>';

      $tpl->set('{title}', $site_title);
      $tpl->set('{debug_mod_notice}', $config->debug ? $lang->debug_mode_active:"");
      $tpl->set('{page_title}', $title ?: $lang->dashboard);
      $tpl->set('{lang}', $language->getData()->code);
      $tpl->set('{langs_list}', $language->makeList());
      $tpl->set('{THEME}', '/engine/admin/template');
      $tpl->set('{plugin}', $tpl->result['plugin']);
      $tpl->set('{info}', $tpl->result['info']);
      $tpl->set('{content}', $tpl->result['content']);
      $tpl->set('{upl}', '/uploads/');
      $tpl->set('{photos}', '/uploads/photos/');
      $tpl->set('{loading_time}', time() - $_SERVER['REQUEST_TIME']);
      $tpl->set('{breadcrumbs}', $breadcrumbs);
      $tpl->set('{dashboard}', $dashboard);
      $tpl->set('{main_modules}', $main_modules);
      $tpl->set('{mode_on}', $mode_on);
      $tpl->compile('main');
      echo $tpl->result['main'];
    }
  }
  else
  {
    header("location: /");
  }
}

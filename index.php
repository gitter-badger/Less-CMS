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
define('AREA', 'frontSide');
define('ROOT', dirname(__FILE__)."/");
define('ENGINE', ROOT . 'engine/');
define('CONFIG', ENGINE . 'configs/');
define('CORE', ENGINE . 'core/');
define('DRIVERS', ENGINE . 'drivers/');
define('UPL', ROOT . 'uploads/');
session_start();
if(!file_exists(ROOT."engine/configs/config.ini") || !file_exists(ROOT."engine/configs/drivers/MySql.json"))
 header("Location: /install/install.php");
$exts = new stdClass();
include_once ENGINE . "init.php";
$idmember = $_SESSION['member_id'];
$logged   = $_SESSION['logged'];
$_GET = $engine->clean($_GET);

if(!$_POST['AJAX'])
{
  if(!$config->offline)
  {
    $tpl->load('main.tpl');

    foreach($exts as $key => $value)
    {
      $tpl->set('{ext-' . $key . '}', $value);
    }
    $tpl->set('{lang}', $language->getData()->code);
    $tpl->set('{langs_list}', $language->makeList());
    $tpl->set('{headers}', $headers);
    $tpl->set('{title}', $siteTitle);
    $tpl->set('{loginButton}', $login_b);
    $tpl->set('{keywords}', $config->keywords);
    $tpl->set('{THEME}', '/templates/' . $config->template);
    $tpl->set('{plugin}', $tpl->result['plugin']);
    $tpl->set('{info}', $tpl->result['info']);
    $tpl->set('{content}', $tpl->result['content']);
    $tpl->set('{login}', $tpl->result['content']);
    $tpl->set('{server_name}', $_SERVER['SERVER_NAME']);
    $tpl->set('{fb_appid}', $config->fb['app_id']);
    $tpl->set('{vk_appid}', $config->vk['app_id']);
    $tpl->set('{upl}', '/uploads/');
    $tpl->set('{photos}', '/uploads/photos/');
    $tpl->set('{loading_time}', time() - $_SERVER['REQUEST_TIME']);
    if($config->m_login)
      $tpl->set('{login_mode_inp}', '<input type="text" name="login" placeholder="Username">');
    else
      $tpl->set('{login_mode_inp}', '<input type="email" name="email" placeholder="E-Mail">');
    $tpl->set('{year}', date("Y"));
    $tpl->compile('main');
    echo $tpl->result['main'];
  }
  else
  {
    $tpl->load('offline.tpl');

    foreach($exts as $key => $value)
    {
      $tpl->set('{ext-' . $key . '}', $value);
    }
    $tpl->set('{lang}', $language->getData()->code);
    $tpl->set('{langs_list}', $language->makeList());
    $tpl->set('{title}', $siteTitle);
    $tpl->set('{keywords}', $config->keywords);
    $tpl->set('{THEME}', '/templates/' . $config->template);
    $tpl->set('{content}', $tpl->result['content']);
    $tpl->set('{upl}', '/uploads/');
    $tpl->set('{photos}', '/uploads/photos/');
    $tpl->set('{loading_time}', time() - $_SERVER['REQUEST_TIME']);
    $tpl->compile('main');
    echo $tpl->result['main'];
  }
}

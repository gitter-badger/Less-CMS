<?php
if(!defined('E_DEPRECATED'))
{
    @error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
	@ini_set ('error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE);
}
else
{
	@error_reporting (E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE);
	@ini_set ('error_reporting', E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE);
}
@ini_set ('display_errors', true);
@ini_set ('html_errors', false);

define ('{security_code}', true);
define ('AREA', 'frontSide');
define ('ROOT', dirname(__FILE__));
define ('ENGINE', ROOT . '/engine/');
define ('CONFIG', ENGINE . 'confs/');
define ('CORE', ENGINE . 'core/');
define ('ERROR', ENGINE . 'errors.php');
date_default_timezone_set('Europe/Rome');
session_start();

$idmember = $_SESSION['member_id'];
$logged = $_SESSION['logged'];

$config = (object)parse_ini_file(CONFIG . "config.ini");
if(!is_null($config->scalar))
{
    echo 'Config file <strong>"config.ini"</strong> not found in: <strong>' . CONFIG . '</strong>';
	exit;
}

include_once ENGINE . "class/engine.class.php";
$_GET = $engine->clean($_GET);
include_once ENGINE . "engine.php";

if(!$_POST['AJAX'])
{
	$tpl->load ( 'main.tpl' );
	include_once ENGINE . 'components.php';
	$tpl->set ( '{lang}', $language->getData()->code );
	$tpl->set ( '{langs_list}', $language->makeList() );
	$tpl->set ( '{headers}', $headers );
	$tpl->set ( '{title}', $siteTitle );
	$tpl->set ( '{loginButton}', $login_b );
	$tpl->set ( '{keywords}', $config->keywords );
	$tpl->set ( '{THEME}', '/templates/' . $config->template );
	$tpl->set ( '{plugin}',  $tpl->result['plugin'] );
	$tpl->set ( '{info}',  $tpl->result['info'] );
	$tpl->set ( '{content}', $tpl->result['content'] );
	$tpl->set ( '{login}', $tpl->result['content'] );
	$tpl->set ( '{server_name}', $_SERVER['SERVER_NAME'] );
	$tpl->set ( '{fb_appid}', $config->fb['app_id'] );
	$tpl->set ( '{vk_appid}', $config->vk['app_id'] );
	$tpl->compile ( 'main' );
	echo $tpl->result['main'];
}
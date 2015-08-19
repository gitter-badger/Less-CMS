<?php
if( !defined( 'E_DEPRECATED' ) )
{
    @error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
    @ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );
}
else
{
    @error_reporting ( E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE );
    @ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE );
}
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );

define ( '{security_code}', true );
define ('AREA', 'adminSide');
define ( 'ROOT', dirname ( __FILE__ ) );
define ( 'ENGINE', ROOT . '/engine/' );
define ( 'CONFIG', ENGINE . 'confs/' );
define ( 'CORE', ENGINE . 'core/' );
define ( 'ERROR', ENGINE . 'errors.php' );
define ( 'ADMIN', ENGINE . 'admin/' );

session_start();

$idmember = $_SESSION['member_id'];
$logged = $_SESSION['logged'];

$config = parse_ini_file(CONFIG . "config.ini");
if(!$config)
{
    echo 'Config file <strong>"config.ini"</strong> not found in: <strong>' . CONFIG . '</strong>';
    exit;
}
else
{
	$config = (object)$config;
}
include_once ENGINE . "class/engine.class.php";
$_GET = $engine->clean($_GET);
include_once ADMIN . 'main.php';

if(!$allert)
{
	if($logged == 'false' or empty($logged))
	{
		header("Location: /unknown");
	}
	if(!$_POST['AJAX'])
	{
		if($_SESSION['social'] != 'true')
		{
				$tpl->load ( 'main.tpl' );
					include ADMIN . 'components.php';
					$tpl->set ( '{modules_list}', $rList );
					$tpl->set ( '{check_version}', $checkVersion );
					$tpl->set ( '{dashboard}', $dashboard );
					$tpl->set ( '{loginButton}', $login_b );
					$tpl->set ( '{notifications}', $notif );
		}
		else
		{
			$tpl->load ( 'login.tpl' );
		}

		$tpl->set ( '{title}', $siteTitle );
		$tpl->set ( '{lang}', $language->getData()->code );
		$tpl->set ( '{langs_list}', $language->makeList() );
		$tpl->set ( '{active_lang_title}', $language->getData()->lang );
		$tpl->set ( '{THEME}', '/engine/admin/template' );
		$tpl->set ( '{info}',  $tpl->result['info'] );
		$tpl->set ( '{content}', $tpl->result['content'] );
		$tpl->compile ( 'main' );
		echo $tpl->result['main'];
	}
}
elseif($allert)
{
    header("Location: /unknown");
}

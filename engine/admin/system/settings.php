<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;}
$additional_settings = "";
$additional_security = "";
$additional_social   = "";
$settings_modules    = "";
if (!isset($_POST['config']))
{
  $dir = scandir(dirname(__FILE__).'/extensions/');
  foreach($dir as $file)
  {
  	if(!is_dir($file) && !preg_match("/#/", $file))
  	{
  		include dirname(__FILE__).'/extensions/' . $file;
  	}
  }
  $tpl_dir = ROOT . '/templates/';
  $scan = scandir($tpl_dir);
  foreach($scan as $dir)
  {
    if (!in_array($dir, array(
      ".",
      ".."
    )) && is_dir($tpl_dir . $dir))
    {
      if ($dir == $config->template) $tList.= '<option value="' . $dir . '" selected>' . $dir . '</option>';
      else $tList.= '<option value="' . $dir . '">' . $dir . '</option>';
    }
  }
  /* Databases */
  $db_dir = DRIVERS . 'database/';
  $scan = scandir($db_dir);
  foreach($scan as $dbp)
  {
    if(!is_dir($db_dir.$dbp))
    {
      if(basename($dbp,".php") == $config->database)
        $cf_database_list .= '<option value="' . basename($dbp,".php") . '" selected>' . basename($dbp,".php") . '</option>';
      else
        $cf_database_list .= '<option value="' . basename($dbp,".php") . '">' . basename($dbp,".php") . '</option>';
    }
  }
  /* Databases END */

  if(!$settings_modules)
   $settings_modules = "<b>{$lang->md_sett_nfound}</b>";

  $offline_status   = '';
  if($config->offline)
    $offline_status = ' checked';

   $debug_status   = '';
   if($config->debug)
     $debug_status = ' checked';

  $recapthca_status   = '';
  if($config->status_captcha)
    $recapthca_status = ' checked';

  $social_plug_status   = '';
  if($config->social_api)
    $social_plug_status = ' checked';

  switch($config->smtp_secure)
  {
    case 'tls':
      $tls = ' selected';
    break;

    case 'ssl':
      $ssl = ' selected';
    break;

    default:
      $off = ' selected';
    break;
  }

  $smtp_secure_opts = '
    <option'.$off.' value="off">Off</option>
    <option'.$tls.' value="tls">TLS</option>
    <option'.$ssl.' value="ssl">SSL</option>';

  switch($config->smtp_secure)
  {
    case 'true':
      $html = ' selected';
    break;

    default:
      $text = ' selected';
    break;
  }

  $smtp_html_opts = '
    <option value="false"'.$text.'>Text</option>
    <option value="true"'.$html.'>HTML</option>';

  $title = $lang->system_config;
  $tpl->load('settings.tpl');

  $tpl->set("{offline_status}",      $offline_status);
  $tpl->set("{debug_status}",        $debug_status);
  $tpl->set("{recapthca_status}",    $recapthca_status);
  $tpl->set("{social_plug_status}",  $social_plug_status);
  $tpl->set("{smtp_secure_opts}",    $smtp_secure_opts);
  $tpl->set("{smtp_html_opts}",      $smtp_html_opts);

  $tpl->set("{cf_title}",            $config->title);
  $tpl->set("{cf_description}",      $config->description);
  $tpl->set("{cf_keywords}",         $config->keywords);
  $tpl->set("{cf_domain}",           $config->domain);
  $tpl->set("{cf_lkey}",             $config->lkey);
  $tpl->set("{cf_templates_list}",   $tList);
  $tpl->set("{cf_database_list}",   $cf_database_list);
  $tpl->set("{captcha_pkey}",        $config->p_key);
  $tpl->set("{captcha_skey}",        $config->s_key);
  $tpl->set("{smtp_host}",           $config->smtp_host);
  $tpl->set("{smtp_port}",           $config->smtp_port);
  $tpl->set("{smtp_user}",           $config->smtp_user);
  $tpl->set("{smtp_pass}",           $config->smtp_pass);
  $tpl->set("{smtp_from}",           $config->smtp_from);
  $tpl->set("{vk-app_id}",           $config->vk['app_id']);
  $tpl->set("{vk-secur_key}",        $config->vk['secur_key']);
  $tpl->set("{fb-app_id}",           $config->fb['app_id']);
  $tpl->set("{fb-secur_key}",        $config->fb['secur_key']);
  $tpl->set('{additional_settings}', $additional_settings);
  $tpl->set('{additional_security}', $additional_security);
  $tpl->set('{additional_social}',   $additional_social);
  $tpl->set('{settings_modules}',    $settings_modules);
  $tpl->compile('content');
}
elseif (isset($_POST['config']))
{
  $_POST['config'] = $engine->clean($_POST['config']);
  $handler = @fopen(CONFIG . "config.ini", "w");
  fwrite($handler, "version = \"{$config->version}\"\n\n");
  fwrite($handler, "lkey = \"{$config->lkey}\"\n\n");
  foreach($_POST['config'] as $key => $value)
  {
    if (!is_array($value))
    {
      fwrite($handler, "{$key} = \"{$value}\"\n\n");
    }
    else
    {
      foreach($value as $sec_key => $sec_value)
      {
        fwrite($handler, "{$key}[{$sec_key}] = \"{$sec_value}\"\n\n");
      }
    }
  }
  fclose($handler);
  $title = $lang->success;
  $core->mess($lang->success, $lang->config_saved,"/admin.php?system=settings");
}
else
{
  $title = $lang->error;
  $core->mess($lang->error, $lang->not_found,"/admin.php");
}

<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;}
if($engine->checkPerm())
{
  if (!$_POST)
  {
    $title = $lang->sys_status;
    $request = $connect->api();
    $curl_st = $lang->active;
    $vst = "";
    $curl_err = "";
    $upd_btn = "";
    if(!function_exists('curl_init'))
    {
      $version_inform = "<div>$lang->curl_disabled</div>";
      $curl_err = "error";
      $curl_st = $lang->disabled;
    }
    else
    {
      if ($request->version == $config->version)
      {
        $version_inform = "<div>$lang->latest_installed</div>";
      }
      elseif ($request->version > $config->version)
      {
        $version_inform = "$lang->update_available $request->version";
        $upd_btn = '<a class="btn-floating btn-large waves-effect waves-light light-green right"><i class="mi">system_update_alt</i></a>';
        $vst = "warning";
      }
      else
      {
        $version_inform = "<div>$lang->noconnect</div>";
      }
    }

    $path = "";
    $files = "";
    $filelist = "";
    if(!empty($request->changelog))
    {
      foreach($request->changelog as $path => $files)
      {
        foreach($files as $file)
        {
          $filelist.= "<span><i class=\"mi\">code</i>{$file}</span>";
        }

        $changelog.= "<strong><i class=\"mi\">folder</i>{$path}</strong>{$filelist}<br />";
      }
    }
    else
    {
      $changelog = '<div class="comment">'.$lang->changelog_empty.'</div>';
    }

    $tpl->load('status.tpl');

    $tpl->set("{version}", $config->version);
    $tpl->set("{php_os}", PHP_OS);
    $tpl->set("{php_os_name}", php_uname());
    $tpl->set("{php_version}", phpversion());
    $tpl->set("{version_inform}", $version_inform);
    $tpl->set("{curl_err}", $curl_err);
    $tpl->set("{curl_st}", $curl_st);
    $tpl->set("{vst}", $vst);
    $tpl->set("{upd_btn}", $upd_btn);

    $tpl->set("{change_log_descr}", $descr);
    $tpl->set("{change_log}", $changelog);
    $tpl->compile("content");
  }
  else
  {
    if (isset($_POST['action']))
    {
      if(!$data = $connect->update())
      {
        echo '{"error":true}';
        exit;
      }
      $exec = $connect->api();
      $engine->unZip(ROOT . "/uploads/temp/$data.zip", ROOT);
      unlink(ROOT . "/uploads/temp/$data.zip");
      $engine->config($exec);
      echo '{"success":true, "version":"'.$exec->version.'"}';
    }
  }
}
else
{
	$title = $lang->perm_denied;
	$core->mess($lang->perm_denied, $lang->perm_denied_m,"/admin.php");
}

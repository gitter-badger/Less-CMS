<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;}

if (!$_POST)
{
  $title = $lang->sys_status;
  $request = $connect->api(array(
    "method" => "cherryHub",
    "action" => "controllUpdate",
    "key" => $config->lkey
  ));

  //  print_r($request);

  $hubVersion = str_replace(".", ',', $request->version);
  $localVersion = str_replace(".", ',', $config->version);
  if ($hubVersion == $localVersion)
  {
    $descr = $lang->updated;
  }
  elseif ($hubVersion > $localVersion)
  {
    $descr = $lang->tupdate;
    $button = '<a type="button" id="update_engine" class="update_button" title="' . $lang->update_to . ' ' . $request->version . '"><i class="mi">arrow_upward</i></a>';
  }
  else
  {
    $descr = $lang->locked;
  }

  $path = "";
  $files = "";
  $filelist = "";
  foreach($request->changelog as $path => $files)
  {
    foreach($files as $file)
    {
      $filelist.= "<span><i class=\"mi\">code</i>{$file}</span>";
    }

    $changelog.= "<strong><i class=\"mi\">folder</i>{$path}</strong>{$filelist}<br />";
  }

  $tpl->load('status.tpl');
  /*Show stats*/
  $tpl->set("{version}", $config->version);
  $tpl->set("{php_os}", PHP_OS);
  $tpl->set("{php_os_name}", php_uname());
  $tpl->set("{php_version}", phpversion());
  $tpl->set("{mysql_version}", $db->version());
  $tpl->set("{button}", $button);

  $tpl->set("{change_log_descr}", $descr);
  $tpl->set("{change_log}", $changelog);
  $tpl->compile("content");
}
else
{
  if (isset($_POST['action']))
  {
    $data = $connect->getPackage(array(
      "action" => "updateEngine"
    ),"engine");
    if ($data->error) exit;
    else
    {
      $request = $connect->api(array(
        "method" => "cherryHub",
        "action" => "controllUpdate"
      ));
      var_dump($request);
      $admin->unzip(ROOT . "/uploads/temp/engine.zip", ROOT);
      $handler = @fopen(CONFIG . "config.ini", "w");
      foreach($config as $key => $value)
      {
        if ($key == 'version')
        {
          fwrite($handler, "{$key} = \"{$request->version}\"\n\n");
        }
        else
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
      }

      fclose($handler);
    }
  }
}

<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;}
if($engine->checkPerm("modules"))
{
  if (!$_POST)
  {
    $title = $lang->modules_manager;

    $paginator = "";
    $end = 10;
    if(!isset($_GET["page"]) or !is_numeric($_GET["page"])){
    	$page = 1;
    }else{
    	$page = $_GET['page'];
    }
    $start = ($page-1) * $end;

    $db->select("extensions");
    if($db->numRows() > 10)
    {
    	$paginator = $engine->pages($db->numRows(),$page,$end,'/admin.php?system=modules&page=');
    }
    $db->select("extensions", "", "", "{$start},{$end}");
    while($row = $db->getObject())
    {
      $md_list .= '
        <tr>
          <td>'.$row->title.'</td>
          <td class="text-center">'.$row->type.'</td>
          <td class="text-center">'.$row->version .'</td>
          <td class="text-right">
            <a class="waves-effect waves-light btn red" onClick="removeExt(\''.$row->id.'\');">'.$lang->remove.'</a>
          </td>
        </tr>';
    }

    $tpl->load('modules.tpl');
    /*Show stats*/
    $tpl->set("{md_list}", $md_list);
  	$tpl->set('{paginator}', $paginator);
    $tpl->compile("content");
  }
  else
  {
    switch ($_POST['action'])
    {
      case 'install_new':
        if($engine->checkPerm("modules_install"))
        {
          $reason = "";
          $mrk = md5(time());
          if($_FILES)
          {
            $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            if(in_array(strtolower($extension), array('zip')))
            {
              $filename = $_FILES['file']['name'];
              if(move_uploaded_file($_FILES['file']['tmp_name'], UPL . "temp/" . $filename))
              {
                $engine->unzip(UPL . "/temp/" . $filename, ROOT);
                if(file_exists(ROOT."/silent_$filename.json"))
                {
                  $json = json_decode(file_get_contents(ROOT."/silent_$filename.json"));
                  eval($json->silent);
                }
              }
              else
              {
                echo '{"error":true,"reason":"'.$lang->fue.'"}';
              }
              unlink($_FILES['file']['tmp_name']);
              unlink(UPL . "/temp/" . $filename);
                echo '{"success":true}';
            }
            else
            {
              echo '{"error":true,"reason":"'.$lang->ena.': '.$extension.'"}';
            }
          }
        }
        else
        {
          echo '{"success":false, "reason":"'.$lang->perm_denied.'"}';
        }
      break;

      case 'remove_md':
        if($engine->checkPerm("modules_remove"))
        {
          $db->select("extensions","id='".$_POST['id']."'");
          $row = $db->getObject();
          $db->delete("extensions","id='".$_POST['id']."'");
          $json = json_decode(file_get_contents(ADMIN."uninstall/".$row->link.".php"));
          foreach ($json as $v)
          {
            unlink(ROOT.$v);
          }
        }
        else
        {
          echo '{"success":false, "reason":"'.$lang->perm_denied.'"}';
        }
      break;
    }
  }
}
else
{
	$title = $lang->perm_denied;
	$core->mess($lang->perm_denied, $lang->perm_denied_m,"/admin.php");
}

<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;}

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
  $db->free();

  $db->select("extensions", "", "", "{$start},{$end}");
  while($row = $db->getObject())
  {
    $md_list .= '<tr>
				<td>'.$row->title.'</td>
				<td class="text-center">'.$row->type.'</td>
				<td class="text-center df">'.$row->version .'</td>
				<td class="text-right" id="buttons_'.$rowID.'"><button type="button" id="remove_md" class="button red" data-id="'.$row->id.'">'.$lang->remove.'</button></td>
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
  if ($_POST['action'] == "remove_md")
  {
    $db->delete("extensions","id='".$_POST['id']."'");
  }
}

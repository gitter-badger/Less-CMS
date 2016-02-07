<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

$db->select("licenses", "user_id='{$user->id}'");
$havel = $db->numRows();
while($row = $db->getobject())
{
  $licenses .= '<li><i class="mi">verified_user</i> <span class="domain"><a href="http://'.$row->domain.'/" target="_blank">'.$row->domain.'</a></span> - <span class="lkey">'.$row->lKey.'</span> </li>';
  if($row->ltype == 1) $count[] = $row->ltype;
}
$db->free();
$mdata->licenses = $licenses;
$mdata->havel = $havel;
$mdata->totl = 5;
if(count($count))
{
  $mdata->totl = '<i class="fa fa-lemon-o"></i>';
}

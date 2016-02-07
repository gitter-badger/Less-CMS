<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

$end = 10;
if(!isset($_GET["page"]) or !is_numeric($_GET["page"])){
	$page = 1;
}else{
	$page = $_GET['page'];
}
$start = ($page-1) * $end;

$db->select("members");
if($db->numRows() > 10)
{
	$paginator = $engine->pages($db->numRows(),$page,$end,'/admin.php?action=activity&page=');
}
$db->free();

$title = $lang->members;

$db->select("members", "", "", "{$start},{$end}");

while($row = $db->getObject())
{
	$block_btn = "";
	if($row->status == 1)
	{
		$status = '<i class="fa fa-check" style="color:green" title="'.$lang->verified.'"></i>';
	}
	else
	{
		$status = '<i class="fa fa-times" style="color:red" title="'.$lang->notverified.'"></i>';
	}

	$block_btn = '<a href="?do=membership&case=block&user='.$row->id.'" title="'.$lang->ban_user.'" id="action" data-action="ban" data-id="'.$row->id.'"><i class="mi">lock_outline</i></a>';
	if($row->status == "-1")
	{
		$block_btn = '<a href="?do=membership&case=unblock&user='.$row->id.'" title="'.$lang->unban_user.'" id="action" data-action="unban" data-id="'.$row->id.'" class="unban"><i class="mi">lock_open</i></a>';
	}
	$actList .= '<div class="tiles">
		<div class="profile_head">
			<div class="p_photo">
				<img src="/uploads/photos/'.$row->avatar.'">
			</div>
			<div class="ps_info">
				<div class="p_name" title="@'.$row->login.'"><a href="?do=membership&user='.$row->id.'">'.$row->fname.' '.$row->lname.'</a></div>
				<div class="p_description">'.$row->aboutme.'</div>
			</div>
			<div class="action_btns">
				<a href="?do=membership&user='.$row->id.'" title="'.$lang->edit_user.'"><i class="mi">mode_edit</i></a>
				'.$block_btn.'
			</div>
		</div>
	</div>';
}

$db->free();

$tpl->load('members.tpl');
	$tpl->set("{title}", $title);
	$tpl->set("{content}", $actList);
	$tpl->set("{paginator}", $paginator);
	$tpl->set("{add_button}", '<a href="/admin.php?action=' . $_GET['action'] . '&sub=new" class="button grey">' . $lang->add . '</a>');
$tpl->compile('content');
?>

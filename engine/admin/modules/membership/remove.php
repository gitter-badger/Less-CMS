<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

if(!$_POST)
{
	msgDel($_GET['action']);
}
else
{
	if($db->delete("members", "id = '{$engine->clean($_GET['id'])}' "))
	{
		msg($lang['success'], $lang['movie_deleted'] . '<br><a href="/admin.php?action=' . $_GET['action'] . '">' . $lang['goback'] . '</a>');
	}
}
?>

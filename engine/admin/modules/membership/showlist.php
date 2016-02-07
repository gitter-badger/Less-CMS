<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}


$title = $lang['users'];

if(!$_POST)
{
	$tpl->load('um_showlist.tpl');
$db->select("members", '', "10");
while($members = $db->getObject()){

	$list .= '<div class="col-sm-4 col-md-4 col-lg-3" onClick="location.href=\'/admin.php?user='.$members->id.'\'">
					<div class="block-flat users">
						<div class="content">
							<img src="/uploads/avatars/'.$members->avatar.'" />
                            <h4>'.$members->fname.' '.$members->lname.'</h4>
						</div>
					</div>
				</div>';

}
	$tpl->compile('content');

}elseif($_POST['action'] === 'add_user'){
	if($_POST['isadmin']) $isadmin = 1; else $isadmin = 0;
	$password = pass_enc($_POST['password']);
	$db->query("INSERT INTO members (`group_id`, `login`, `password`, `email`, `fname`, `lname`) VALUES ('{$isadmin}','".$_POST['login']."','{$password}','".$_POST['email']."','".$_POST['f_name']."','".$_POST['l_name']."') ");
	$db->free();
	$db->query("SELECT * FROM members WHERE login = '".$_POST['login']."' ");
	$refresh = $db->get_row(); $db->free();
	echo '{"login":"'.$refresh['login'].'", "photo":"'.$refresh['avatar'].'", "fname":"'.$refresh['fname'].'", "lname":"'.$refresh['lname'].'"}';
}

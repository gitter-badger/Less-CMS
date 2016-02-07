<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}


$db->select("members","id='".intval($_GET['user'])."'");
$user = $db->getObject();
$db->free();

if(!$_POST)
{
	$tpl->load("profile.tpl");
	$title = $user->fname . ' ' . $user->lname;

	foreach($user as $key => $value)
	{
		$tpl->set('{user-'.$key.'}', $value);
	}

	$tpl->compile('content');
}
else
{
	if(isset($_POST['check']))
	{
		$filename = $user->avatar;
		if($_FILES)
		{
			$extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);

			if(in_array(strtolower($extension), array('png', 'jpg', 'jpeg')))
			{
				$filename = $user->id . '.' . $extension;
				if(move_uploaded_file($_FILES['photo']['tmp_name'], UPL . "photos/" . $filename))
				{
					$image = AcImage::createImage(UPL . "photos/" . $filename);
					unlink(UPL . "photos/" . $filename);
					$image
						->cropCenter("4pr", "4pr")
						->save(UPL . "photos/" . $filename);
				}
				unlink($_FILES['photo']['tmp_name']);
				unlink(UPL . "photos/" . $user->avatar);
			}
		}

		$data['fname']  = $db->real_escape_string($_POST['fname']);
		$data['lname']  = $db->real_escape_string($_POST['lname']);
		$data['login']  = $db->real_escape_string($_POST['login']);
		$data['email']  = $db->real_escape_string($_POST['email']);
		$data['avatar'] = $filename;
		if(!empty($_POST['old_psw']))
		{
			if(empty($_POST['new_psw']) || empty($_POST['rpt_psw']))
			{
				$core->mess($lang->error,$lang->new_pass_cnt_empty);
			}
			else
			{
				if($_POST['new_psw'] !== $_POST['rpt_psw'])
				{
					$title = $lang->error;
					$core->mess($lang->error,$lang->not_identical);
				}
				else
				{
					if($engine->encode($_POST['old_psw']) !== $user->password)
					{
						$title = $lang->error;
						$core->mess($lang->error,$lang->old_wrong);
					}
					else
					{
						$data['password'] = $engine->encode($_POST['new_psw']);
					}
				}
			}
		}
		$db->update("members",$data,"id='{$user->id}'");
		$title = $lang->success;
		$core->mess($lang->success,$lang->config_saved,"/admin.php?do=membership&user=".$user->id);
	}
}

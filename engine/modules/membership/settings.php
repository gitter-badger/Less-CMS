<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

if(!$_POST)
{
	$title = $lang->member_settings;
	$tpl->load('settings.tpl');
	foreach($sdata as $key => $value)
	{
		$tpl->set('{sdata-' . $key . '}', $value);
	}
	$tpl->compile('content');
}
else
{
	$avatar = '';
	if(!empty($_FILES))
	{
		if(!empty($_FILES['avatar']['name']))
		{
			if(!$config->upload_allowed)
			{
				$allowed = array('png', 'jpg', 'jpeg');
			}
			else
			{
				$allowed = $config->upload_allowed;
			}

			if(!in_array(strtolower($engine->showExtension($_FILES['avatar']['name'])), $allowed))
			{
				echo 'error '.$extension;
				exit;
			}
			$filename = $engine->random_string(25, 'lower,upper,numbers') . '.' . $engine->showExtension($_FILES['avatar']['name']);

			if(move_uploaded_file($_FILES['avatar']['tmp_name'], ROOT . "/uploads/photos/" . $member->login . "/" . $filename))
			{
				$avatar = $filename;
				unlink(ROOT . "/uploads/photos/" . $member->login . "/" . $member->avatar);
			}
			unlink($_FILES['avatar']['tmp_name']);
		}
	}
	$title = $lang['success'];

	$data['fname'] 		= $db->real_escape_string($_POST['fname']);
	$data['lname'] 		= $db->real_escape_string($_POST['lname']);
	$data['aboutme'] 	= $db->real_escape_string($_POST['about']);
	if(!empty($avatar))	$data['avatar'] 	= $avatar;

	$db->update("members", $data, "id = '{$member->id}' ");

	$core->mess($lang['success'], $lang['changes_saved']);
}

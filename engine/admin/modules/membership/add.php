<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

if(!$_POST && !$_FILES)
{
	$db->select("movies", "id = '{$engine->quote($_GET['id'])}'");
	$row = $db->getObject();
	$db->free();
	$title = $lang['movie_add'];

	$db->select("genres");
	while($option = $db->getObject())
	{
		if($option->id == $row->type)
		{
			$options .= '<option value="'.$option->id.'" selected>'.$option->title.'</option>';
		}
		else
		{
			$options .= '<option value="'.$option->id.'">'.$option->title.'</option>';
		}
	}
	$db->free();

	$time = $engine->timeGen();

	$tpl->load('movies_manage.tpl');
		$tpl->set("{head}", $title);
		$tpl->set("{title}", '');
		$tpl->set("{descr}", '');
		$tpl->set("{options}", $options);
		$tpl->set("{small}", '/engine/admin/template/images/noimg.png');
		$tpl->set("{large}", '/engine/admin/template/images/placeholder.png');
		$tpl->set("{media}", '<input type="text" name="film" placeholder="' . $lang['filelink'] . '">');
		$tpl->set("{btn_movie}", '');
		$tpl->set("{btn_series}", 'text');
	$tpl->compile('content');
}
else
{
	if(!empty($_FILES))
	{
		if(isset($_FILES['small']))
		{
			if(!$config->upload_allowed)
			{
				$allowed = array('png', 'jpg', 'jpeg');
			}
			else
			{
				$allowed = $config->upload_allowed;
			}

			if(!in_array(strtolower($engine->showExtension($_FILES['small']['name'])), $allowed))
			{
				echo 'error '.$extension;
				exit;
			}
			$filename = $engine->random_string(25, 'lower,upper,numbers') . '.' . $engine->showExtension($_FILES['small']['name']);
			if(!move_uploaded_file($_FILES['small']['tmp_name'], FOTO . $filename))
			{
				$small = $filename;
			}

			unlink($_FILES['small']['tmp_name']);
		}
		if(isset($_FILES['large']))
		{
			if(!$config->upload_allowed)
			{
				$allowed = array('png', 'jpg', 'jpeg');
			}
			else
			{
				$allowed = $config->upload_allowed;
			}

			if(!in_array(strtolower($engine->showExtension($_FILES['large']['name'])), $allowed))
			{
				echo 'error '.$extension;
				exit;
			}

			$filename = $engine->random_string(25, 'lower,upper,numbers') . '.' . $engine->showExtension($_FILES['large']['name']);

			if(move_uploaded_file($_FILES['large']['tmp_name'], FOTO . $filename))
			{
				$large = $filename;
			}

			unlink($_FILES['large']['tmp_name']);
		}
	}

	$title = $lang['movie_added'];
	$pattern = array('/[\s]/','/[\W]/','/__/','/___/');
	$replace = array('_','','_','_');

	$link = preg_replace($pattern, $replace, $admin->translit($_POST['title']));

	$data = array(
		"title" => $_POST['title'],
		"link" => $link,
		"genres" => $_POST['genres'],
		"description" => $_POST['descr'],
		"poster" => $posterSmall,
		"posterlarge" => $posterLarge,
		"file" => $lat,
		"playlist" => $lng
		);

	$db->insert("movies", $data);
	msg($lang['success'], $lang['movie_added'] . '<br><a href="/admin.php?action=movies">' . $lang['goback'] . '</a>');
	#print_r($_POST);
}

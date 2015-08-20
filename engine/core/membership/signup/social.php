<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

$allowed = array('jpg','jpeg','png');
$extension = $engine->showExtension($_POST['photo']);
if(in_array(strtolower($extension), $allowed))
{
	$dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/avatars/' . $_POST['login'];
	$photo = $engine->random_string(15, 'lower,upper,numbers') . '.' . $extension;
	mkdir($dir);
	copy($_POST['photo'], $dir . '/' . $photo);
}
else
{
	$photo = '';
}
$password = $engine->encode($_POST['password']);

$db->query("INSERT INTO `members`(`group_id`, `login`, `password`, `email`, `fname`, `lname`, `avatar`, `status`, `bdate`) VALUES ('0','".$_POST['login']."','".$password."','".$_POST['email']."','".$_POST['fname']."','".$_POST['lname']."','".$photo."','1','".$_POST['bday']."')");
$db->free();
	$db->query("SELECT * FROM `members` WHERE login='" . $_POST['login'] . "' ");
	$user = $db->get_row();
	$db->free();
$db->query("INSERT INTO `members_social`(`user_id`, `social_id`) VALUES ('" . $user['id'] . "','" . $_POST['uid'] . "')");
$db->free();
header("Location: /");
<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}


if(!isset($_SESSION['logged']) && $_SESSION['logged'] != true)
{
	$login_b = '<a href="#" id="logIn" class="button blue bg" data-toggle="modal" data-target="#loginModal" data-toggle="tooltip" data-placement="right" title="'.$lang['customize_login'].'">'.$lang['signin'].'</a>';
}
else
{
	$login_b = '<a href="/signout" id="logIn" class="button blue bg" data-toggle="tooltip" data-placement="right" title="'.$lang['customize_login'].'">'.$lang['signout'].'</a>';
}

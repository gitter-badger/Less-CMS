<?php
if($_GET['code'] && $_GET['code'] != ''){
	header("Location: http://" . $_SERVER['SERVER_NAME'] . "/signin/vk/" . $_GET['code']);
}
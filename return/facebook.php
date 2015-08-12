<?php
if($_GET['code'] && $_GET['code'] != ''){
	header("Location: http://" . $_SERVER['SERVER_NAME'] . "/signin/facebook/" . $_GET['code']);
}
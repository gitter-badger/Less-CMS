<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

class Admin extends Engine
{
	public function __construct()
	{
		parent::__construct();
	}
}
$admin = new Admin();

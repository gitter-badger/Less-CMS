<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

$params['action'] = 'getExtension';
$params['extension'] = $_POST['extension'];

$data = $admin->getPackage($params);
if($data->error){echo $data->error;exit;}

require_once 'installModule.php';

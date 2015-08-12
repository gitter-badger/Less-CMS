<?php
if (!defined('l8xZZV6AZqVoF91XM7')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

if($_POST['update'])
{
    $params = array(
    			"method" => "cherryPie",
				"action" => "getUpdate",
				"extension" => 'engine'
			);
	$data = $admin->getFile($params);
	if($data->error)
	{
		msg($lang['error'], $data->error);
	}
	else
	{
		$request = $admin->api(
					array(
						"method" => "cherryPie",
						"action" => "cmsUpdate",
						"key" => $config->lkey,
						"domain" => $_SERVER['SERVER_NAME']
					)
				);
		
		$admin->unzip(ROOT . "/uploads/temp/engine.zip", ROOT);
		echo $request->version;
		
		$handler = @fopen(ENGINE . 'confs/config.php', "w");
		fwrite ($handler, "<?php
							 \n\$config = (object)array(\n\n");
		foreach ($config as $key => $value)
		{
			if($key == 'version')
			{
				fwrite($handler, "'{$key}' => \"{$request->version}\",\n\n");
			}
			else
			{
				fwrite($handler, "'{$key}' => \"{$value}\",\n\n");
			}
		}
		fwrite($handler, ");\n?>");
		fclose($handler);
	}
}
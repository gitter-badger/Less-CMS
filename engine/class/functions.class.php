<?php
if (!defined('l8xZZV6AZqVoF91XM7')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

class Functions
{
	public function __construct()
	{

	}

  public function clean($in, $object=false)
	{
		if(is_object($in))
		{
			$in = (array)$in;
		}
		$blackList = array ("\x60","\t","\n","\r","\\","В¬","--","GROUP","--%","UNION","","eval","INTO","OUTFILE","LOAD_FILE","SELECT","-1"," AND ","information_schema","columns","select" ,"null," ,"OR ",".ppp",'</script>','<script>','</html>','<html>','</body>','<body>','</head>','<head>');

		if(is_array($in))
		{
			foreach($in as $key => $value)
			{
				if(is_array($value))
				{
					$out[$key] = self::clean($value);
				}
				else
				{
					$out[$key] = str_replace($blackList, '', $value);
				}
			}
		}
		else
		{
			$out = str_replace($blackList, '', $in);
		}
		if(empty($out))
		{
			return false;
		}
		if($object)
		{
			return (object)$out;
		}
		return $out;
	}

  public function perms($file, $perm = 644)
	{
		if(substr(sprintf('%o', fileperms($file)), -3) != $perm)
		{
			return false;
		}
		return true;
	}
}
$functions = new Functions();

<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

/*Databases*/
interface database
{
    /*Connect ecc.*/
	public function __construct();
	/*Request query*/
	public function  query($query);
	/*Select (use query)*/
	public function  select($table='undefined', $where=false, $order=false, $limit=false, $cols=false);
	/*Insert (use query)*/
	public function  insert($table, $data);
	/*Update (use query)*/
	public function  update($table, $params, $where);
	/*Delete (use query)*/
	public function  delete($table, $where);
	/*Num rows (use query)*/
	public function  numRows();
	/*Get data (use query)*/
	public function  getObject($object=true);
	/*Get data (use query)*/
	public function  version();
	/*Close (use query)*/
	public function  free();
}

abstract class dbConfig
{
	protected $db;
	protected $request;
	protected $db_id;
	protected $query_id;
	protected $erorrs;

    public function error($data)
		{
        $title = 'MySQL Error';
		$err_type = "MySQL Error " . $data;

		require_once ERROR;

		exit();
    }

    public function success()
		{
        return (object)'success';
    }

    public function warning()
		{
        return (object)'warning';
    }

    public function sqlError($query, $message)
	{
		if($query)
		{
			$query = preg_replace("/([0-9a-f]){32}/", "********************************", $query);
		}
		$query = htmlspecialchars($query, ENT_QUOTES, 'ISO-8859');
		$message = htmlspecialchars($message, ENT_QUOTES, 'ISO-8859');
		$trace = debug_backtrace();

		$level = 0;
		if ($trace[1]['function'] == "query" ) $level = 2;
		if ($trace[2]['function'] == "super_query" ) $level = 3;

		$trace[$level]['file'] = str_replace(ROOT, "", $trace[$level]['file']);

		#print_r($trace);
		$title = 'MySQL Error';
		$err_type = "MySQL Error ({$this->db_id->errno})\n\n{$query}\n{$message}. \n\n In: {$trace[$level]['file']} on line: {$trace[$level]['line']}";

		if(!include $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php")
		{
			$mysql_err['error'] = "Database error";
			$mysql_err['code'] = "1";
			echo json_encode($mysql_err);
		}

		exit();
    }

}

spl_autoload_register(function ($name)
{
	include CONFIG . 'plugins/' . $name . '.conf.php';
    include 'drivers/' . $name . '.driver.php';
});
$mysql = new mysql();
/*Databases END*/

class Core extends Functions
{
	var $action;
	var $lang;
	var $member;
	var $config;

	public function __construct()
	{
		global $config;
		global $lang;
		global $member;

		$this->db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_NAME);
		$this->db->set_charset("utf8");

		$this->config = $config;
		$this->lang = $lang;
		$this->member = $member;

		if (!empty($arguments))
		{
				foreach ($arguments as $property => $argument)
				{
						$this->{$property} = $argument;
        }
    }
	}

	public function __call($method, $arguments)
	{
		$arguments = array_merge(array("MySQL Object" => $this), $arguments);
		if (isset($this->{$method}) && is_callable($this->{$method}))
		{
			return call_user_func_array($this->{$method}, $arguments);
		}
		else
		{
			throw new Exception("Fatal error: Call to undefined method MySQL Object::{$method}()");
		}
	}

	public function notif($type='', $mess)
	{
		switch($type)
		{
			case "error":
				$icon = 'exclamation';
				$nType = 'error';
			break;

			case "notif":
				$icon = 'info';
				$nType = 'notif';
			break;

			case "db":
				$icon = 'database';
				$nType = 'database';
			break;

			case "license":
				$icon = 'key';
				$nType = 'license';
			break;

			case "system":
				$icon = 'hand-peace-o';
				$nType = 'system';
			break;

			default:
				$icon = 'asterisk';
				$nType = '';
			break;
		}

		return '<li><div><i class="fa fa-' . $icon . '"></i></div><div>
		<h4>' . $this->lang[$nType] . '</h4>
		' . $mess . '
		</div></li>';
	}

	public function mess($title, $text)
	{
		global $tpl;
		global $lang;
		global $member;
		global $config;

		$title = $title;

		if (!class_exists('template')){return false;}

		$tpl->load( 'mess.tpl' );

		$tpl->set( '{title}', $title );
		$tpl->set( '{text}', $text );

		$tpl->compile( 'info' );
	}

	public function load($data)
	{
		$data = basename($data, ".php");
		if(preg_match('/[a-zA-Z0-9_-]*/i', $data))
		{
			return $data;
		}
		return false;
	}

	public function headers($name,$value)
	{
		return "<meta name=\"{$name}\" content=\"{$value}\">";
	}

	public function setUser()
	{
		mysql::select("members","id = '{$_SESSION['member_id']}'");
		$member = mysql::getObject();
		mysql::free();
		if($member->group_id != 1)
		{
			return false;
		}
		return $member;
	}
}

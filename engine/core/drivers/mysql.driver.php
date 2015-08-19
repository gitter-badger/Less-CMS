<?php
if (!defined('{security_code}')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

class MySQL extends dbConfig implements database
{
	function __construct(array $arguments = array())
	{
		$this->db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_NAME);
		$this->db->set_charset("utf8");
		
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
	
	public function query($request)
	{
		if ($this->db->connect_errno)
		{
			self::sqlError($this->db->connect_error);
		}
		if(!$this->query_id = $this->db->query( $request ))
		{
			self::sqlError($request, $this->db->error);
		}
		return $this->query_id;		
	}
	
	public function select($table='undefined', $where='', $order='', $limit='', $cols=false)
	{
		if(!empty($where))
		{
			$where = "WHERE " . $where;
		}
		if(!empty($order))
		{
			$order = "ORDER BY " . $order;
		}
		if(!empty($limit))
		{
			$limit = "LIMIT " . $limit;
		}
		if(!$cols)
		{
			$cols = '*';
		}
		return self::query( "SELECT {$cols} FROM `{$table}` {$where} {$order} {$limit}" );
	}
	
	public function insert($table='undefined', $data)
	{
		if(!is_array($data))
		{
			self::error('Only array allowed');
		}
		$request = self::build($data, 'insert');
		$cols = $request['cols'];
		$values = $request['values'];
			
		self::query( "INSERT INTO `{$table}`({$cols}) VALUES ({$values})" );
		return true;
	}
	
	public function update($table='undefined', $params, $where)
	{
		if(!is_array($params))
		{
			self::error('Only array allowed');
		}
		self::query( "UPDATE `{$table}` SET " . self::build($params, 'update') . " WHERE {$where}" );
		return true;
	}
	
	public function delete($table='undefined', $where)
	{
		self::query( "DELETE FROM `{$table}` WHERE {$where}" );
		return true;
	}
	
	public function numRows()
	{
		return $this->query_id->num_rows;
	}
	
	public function getObject($object=true)
	{
		if(!$object)
		{
			return $this->query_id->fetch_row();
		}
		else
		{
			return $this->query_id->fetch_object();
		}
	}
	
	public function version()
	{
		return $this->db->server_info;		
	}
	
	public function free()
	{
		$this->query_id->close();
	}
	
	public function build($array, $template)
	{
		if($template == 'update')
		{
			foreach($array as $key => $value)
			{
				$set .= "`{$key}` = '{$value}'|";
			}
			$set = explode('|', $set);
			$set = array_diff($set, array(''));
			$params = implode(',', $set);
		}
		elseif($template == 'insert')
		{
			foreach($array as $key => $value)
			{
				$col[] .= "`{$key}`";
				$val[] .= "'{$value}'";				
			}
			$cols .= implode(',', $col);
			$values .= implode(',', $val);
			$params = array("cols" => $cols, "values" => $values);
		}
		return $params;
	}
}
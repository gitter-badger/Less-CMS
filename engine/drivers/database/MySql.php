<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;}
/*
 * Database class
 */
 class MySQL implements DBI
 {
   var $conn;
   var $status;

   public function __construct($config="MySql")
   {
     $this->status = new stdClass();
     $this->status->config = "ERROR";
     $conf = json_decode(file_get_contents(CONFIG."drivers/".basename($config,".json").".json"));
     if(!empty((array)$conf)) $this->status->config = "OK";
     self::init($conf);
   }

   function init($conf)
   {
     $this->conn = new mysqli($conf->host ?: "localhost", $conf->user ?: "root", $conf->password, $conf->table);
     $this->conn->set_charset("utf8");
     $this->status->connection = "OK";
     if ($this->conn->connect_errno)
     {
       $this->status->connection = "ERROR";
       $this->status->error      = $this->conn->connect_error;
       $text                     = "Database connection error, check engine settings or database server state.";
       require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;
     }
   }

   public function query($request)
   {
     if(empty($request)) return false;

     if($this->status->id = $this->conn->query($request))
      return true;

     $this->status->error = $this->db->error;
     if(!$GLOBALS['config']->debug)
     {
       $text  = "Error execute the query to a database. Check the settings of the engine and/or connect to the server. For more information, enable the debug mode.";
       require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;
     }
     else
     {
       $text  = "Error execute the query \"{$request}\" to a database.\n{$this->conn->error}";
       require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;
     }
     return false;
   }

   public function select($table, $where = '', $order = '', $limit = '', $cols = '*')
   {
     $add = array();
     if (!empty($where))
     {
       $add[] = "WHERE " . $where;
     }
     if (!empty($order))
     {
       $add[] = "ORDER BY " . $order;
     }
     if (!empty($limit))
     {
       $add[] = "LIMIT " . $limit;
     }
     return self::query("SELECT $cols FROM $table " . implode(" ", $add));
   }

   public function insert($table, $data)
   {
     if (!is_array($data))
     {
       $this->status->error = "Only array allowed";
       return false;
     }
     $request = self::build($data, 'insert');
     $cols = $request['cols'];
     $values = $request['values'];
     return self::query("INSERT INTO `{$table}`({$cols}) VALUES ({$values})");
   }

   public function update($table, $params, $where)
   {
      if(!is_array($params))
      {
        $this->status->error = "Only array allowed";
        return false;
      }
      return self::query("UPDATE `{$table}` SET " . self::build($params, 'update')->data . " WHERE {$where}");
   }

   public function delete($table, $where)
   {
     return self::query("DELETE FROM `{$table}` WHERE {$where}");
   }

   public function numRows()
   {
     return $this->status->id->num_rows;
   }

   public function getObject()
   {
     if($this->status->id->num_rows) return $this->status->id->fetch_object();
     return false;
   }

   public function getRows()
   {
     return $this->status->id->fetch_row();
   }

   public function version()
   {
     return $this->conn->server_info;
   }

   public function free()
   {
     if($this->status->id->num_rows) $this->status->id->close();
     return false;
   }

   function close()
   {
     $this->conn->close();
   }

   public function real_escape_string($str)
   {
     return $this->conn->real_escape_string($str);
   }

   function build($array, $template)
   {
     $return = new stdClass();
     if ($template == 'update')
     {
       foreach($array as $key => $value)
       {
         $data[] = "`$key` = '$value'";
       }
       $return->data = implode(",", $data);
     }
     elseif ($template == 'insert')
     {
       foreach($array as $key => $value)
       {
         $col[] = "`{$key}`";
         $val[] = "'{$value}'";
       }
       $cols = implode(',', $col);
       $values = implode(',', $val);
       $return->data->cols = $cols;
       $return->data->values = $values;
     }
     else return $return->error = true;
     return $return;
   }
 }

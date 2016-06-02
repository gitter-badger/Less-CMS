<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;}
/*
 * Database class
 */
 class MySQL
 {
   var $conn;
   var $status;
   var $request;
   var $numRows;

   public function __construct($config="MySQL")
   {
     $this->status = new stdClass();
     $this->status->config = "ERROR";
     $conf = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/engine/configs/drivers/".basename($config,".json").".json"));
     if(!empty((array)$conf)) $this->status->config = "OK";
     self::init($conf);
   }

   function init($conf)
   {
     $this->status->connection = "OK";
     try
     {
       $this->conn = new PDO("mysql:dbname={$conf->table};host=".$conf->host ?: "localhost;charset=utf8", $conf->user ?: "root", $conf->password ?: "");
       $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
       $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       $this->conn->exec("SET NAMES 'utf8'");
       $this->conn->exec("SET CHARACTER SET 'utf8'");
       $this->conn->exec("SET SESSION collation_connection = 'utf8_general_ci'");
     }
     catch (PDOException $e)
     {
       $this->status->connection = "ERROR";
       $text                     = "Database connection error, check engine settings or database server state.";
       require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;
     }
   }
   public function query($request)
   {
     $this->request = $this->conn->prepare($request, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
     $this->request->execute();
     return true;
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
     $cols = self::build($data, 'insert')->data->cols;
     $values = self::build($data, 'insert')->data->values;
     self::query("INSERT INTO `{$table}`({$cols}) VALUES ({$values})");
     return true;
   }

   public function update($table, $params, $where)
   {
      if(!is_array($params))
      {
        $this->status->error = "Only array allowed";
        return false;
      }
      self::query("UPDATE `{$table}` SET " . self::build($params, 'update')->data . " WHERE {$where}");
      return true;
   }

   public function delete($table, $where)
   {
     self::query("DELETE FROM `{$table}` WHERE {$where}");
     return true;
   }

   public function numRows()
   {
     $c = $this->request->rowCount();
     if($c == 0) return false;
     return $c;
   }

   public function getObject()
   {
     return $this->request->fetchObject();
   }

   public function getRows()
   {
     return $this->request->fetch(PDO::FETCH_ASSOC);
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
       $return = new stdClass();
       $return->data = "";
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

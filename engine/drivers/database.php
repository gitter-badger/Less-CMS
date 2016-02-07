<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php";exit;}
/*
 * Database interface && default database class
 */
 interface DBI
 {
  public function __construct($config);

  function init($conf);

  public function query($request);

  public function select($table, $where = '', $order = '', $limit = '', $cols = '*');

  public function insert($table, $data);

  public function update($table, $params, $where);

  public function delete($table, $where);

  public function numRows();

  public function getObject();

  public function getRows();

  public function version();

  public function free();

  public function close();

  public function real_escape_string($str);

  function build($array, $template);
 }

class Database extends Core
{
  var $db_status;

  function __construct()
  {
    $this->db_status = new stdClass();
    self::initDatabase();
  }

  private function initDatabase()
  {
    foreach(Core::getDatabases() as $key => $value)
    {
      if(include $value)
      {
        $tmp_db = new $key;
        $this->db_status->$key->config = $tmp_db->config_stat;
        $this->db_status->$key->driver = "OK";
      }
      else
      {
        $this->db_status->$key->config = "ERROR";
        $this->db_status->$key->driver = "ERROR";
      }
    }
  }

}
$database = new Database();
?>

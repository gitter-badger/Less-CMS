<?php
if (!defined('LessCMS-Secure')){require $_SERVER['DOCUMENT_ROOT'] . "/engine/errors.php"; exit;}

/*
 * Default registration class
 */
class DefaultReg extends Auth
{
  function __construct()
  {
    $this->db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_NAME);
    $this->db->set_charset("utf8");

    if(empty($_POST['email']) or empty($_POST['password']))
    {
      $this->return->error = true;
      $this->return->reason = 'Email or password can not be empty!';
      return $this->return;
    }

    if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['email']))
    {
      $this->return->error = true;
      $this->return->reason = 'Email may only consist of letters of the alphabet, numbers and symbol @';
      return $this->return;
    }
    mysql::select("members", " email = '".mysql::real_escape_string($_POST['email'])."' ");
    if(mysql::numRows())
    {
      $this->return->error = true;
      $this->return->reason = 'User with this email is already registered.';
      return $this->return;
    }
    mysql::free();

    $data['group_id'] = '0';
    $data['password'] = engine::encode($_POST['password']);
    $data['email'] = mysql::real_escape_string($_POST['email']);
    $data['status'] = '1';

    mysql::insert("members",$data);
    mysql::select("members", " email = '".mysql::real_escape_string($_POST['email'])."' ");
    $row = mysql::getObject();
    mysql::free();
    Engine::addSkey('logged',true);
    Engine::addSkey('member_id',$row->id);
    Engine::addSkey('social',false);
    header("Location: /");
  }
}

/*
 * Social registration class
 */
class SocialReg extends Auth
{
  function __construct()
  {
    $this->db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_NAME);
    $this->db->set_charset("utf8");

    $allowed = array('jpg','jpeg','png');
    $extension = Engine::showExtension($_POST['photo']);
    if(in_array(strtolower($extension), $allowed))
    {
      $dir = ROOT . '/uploads/avatars/' . $_POST['login'] . '/';
      mkdir($dir);
      $photo = Engine::random_string(15, 'lower,upper,numbers') . '.' . $extension;
    	copy($_POST['photo'], $dir . '/' . $photo);
    }
    else $photo='';

    $data['group_id'] = '0';
    $data['password'] = engine::encode($_POST['password']);
    $data['email'] = mysql::real_escape_string($_POST['email']);
    $data['status'] = '1';
    $data['login'] = $_POST['login'];
    $data['fname'] = $_POST['fname'];
    $data['lname'] = $_POST['lname'];
    $data['avatar'] = $photo;
    $data['bdate'] = $_POST['bday'];

    mysql::insert("members", $data);
    mysql::select("members", " email = '".mysql::real_escape_string($_POST['email'])."' ");
    $row = mysql::getObject();
    mysql::free();

    $soc_data['user_id'] = $row->id;
    $soc_data['social_id'] = $_POST['uid'];
    mysql::insert("members_social", $soc_data);

    Engine::addSkey('logged',true);
    Engine::addSkey('member_id',$row->id);
    Engine::addSkey('social',false);
    header("Location: /");
  }
}
